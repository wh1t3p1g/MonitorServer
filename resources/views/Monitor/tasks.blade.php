@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>Hosts</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body" id="tasks-table">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-lg-12">
                            <a class="btn btn-primary" href="{{ url('monitor/add') }}">Add New Tasks</a>
                        </div>
                    </div>
                    <table id="table-tasks" class="table table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>PROJECT NAME</th>
                            <th>HOST</th>
                            <th>MODE</th>
                            <th>STATUS</th>
                            <th>CREATED</th>
                            <th>UPDATED</th>
                            <th>OPTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    {{--/.table--}}
                </div>
                {{-- /.box-body--}}
            </div>
            {{--  /.box--}}
        </div>
    </div>

    <div class="modal fade modal-danger" id="del-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Are You Sure?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label("confirm","Please tap Project Name to Confirm this option") }}
                        {{ Form::text("confirm",'',['class'=>'form-control',"placeholder"=>""]) }}
                        {{ Form::hidden("task_id",'',['class'=>'form-control','id'=>'task_id']) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                    <button @click="delTask" class="btn btn-outline pull-right" data-dismiss="modal">Confirm</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade modal-danger" id="stop-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Are You Sure?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label("confirm-status","Please tap YES to Confirm this option") }}
                        {{ Form::text("confirm-status",'',['class'=>'form-control',"placeholder"=>"YES"]) }}
                        {{ Form::hidden("status_id",'',['class'=>'form-control','id'=>'status_id']) }}
                        {{ Form::hidden("status_value",'',['class'=>'form-control','id'=>'status_value']) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                    <button @click="toggles()" class="btn btn-outline pull-right" data-dismiss="modal" id="status_btn">Confirm</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('tScript')
<script>
    $(document).ready(function() {
        table=$('#table-tasks').DataTable({
            "ordering":false,
            "processing" : true,
            "serverSide" : true,
            "paginationType":"full_numbers",
            "ajax" : "{{ url("monitor/tasks/get") }}",
            "columns" : [
                {"data" : "id"},
                {"data" : "project_name"},
                {"data" : "ip"},
                {"data" : "run_mode"},
                {"data" : "status"},
                {"data" : "created_at"},
                {"data" : "updated_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        if(row["status"]=="running"){
                            return '<div id="td-status-'+row['id']+'"><button onclick="toggleModal(\'running\','+row['id']+')" class="btn btn-xs btn-block btn-primary">Running</button></div>';
                        }else{
                            return '<div id="td-status-'+row['id']+'"><button onclick="toggleModal(\'stopped\','+row['id']+')" class="btn btn-xs btn-block btn-danger">Stopped</button></div>';
                        }
                    },
                    "targets": 4
                },
                {
                    "render":function(data,type,row){
                        return '<div class="row">\
                            <div class="col-lg-6" style="padding-right: 5px">\
                            <a href="{{ url('monitor/task/show/') }}/'+row["id"]+'" class="btn btn-xs btn-block btn-info"><span>Detail</span></a>\
                            </div>\
                            <div class="col-lg-6" style="padding-left: 5px" id="delete-'+row["id"]+'">\
                            <button onclick="del('+row['id']+',\''+row['project_name']+'\')" class="btn btn-xs btn-block btn-danger"><span>Delete</span></button>\
                            </div>\
                            </div>';
                    },
                    "targets":7
                }
            ]
        });
    });

    function del(id,project_name){
        $('#task_id').val(id);
        $('#confirm').attr("placeholder",project_name);
        $("#del-modal").modal("toggle");
    }

    function toggleModal(status,id){
        $('#status_id').val(id);
        $('#status_value').val(status);
        $('#stop-modal').modal();
    }

    new Vue({
        el:"#stop-modal",
        methods:{
            toggles:function(){
                var confirm=$('#confirm-status').val();
                console.info(confirm);
                if(confirm!="YES"){
                    alertModal('Error Message',"Confirm Message Not YES",'modal-danger');
                }else{
                    var id=$('#status_id').val();
                    var status=$('#status_value').val();
                    $.ajax({
                        url:'{{ url("monitor/task/toggle") }}',
                        type:"post",
                        dataType:"json",
                        data:{id:id,status:status},
                        success:function(data){
                            if(data['status']==1){
                                alertModal('Success Message',data['message'],'modal-success');
                                var html="";
                                if(status=="running"){
                                    html="<button onclick=\"toggleModal('stopped',"+id+")\" class=\"btn btn-xs btn-block btn-danger\">Stopped</button>";
                                }else{
                                    html="<button onclick=\"toggleModal('running',"+id+")\" class=\"btn btn-xs btn-block btn-primary\">Running</button>";
                                }
                                $('#td-status-'+id).html(html);
                            }else{
                                alertModal('Error Message',data['message'],'modal-danger');
                            }
                        },
                        error:function(data){
                            var message="";
                            for(var key in data.responseJSON){
                                message+=key+" : "+data.responseJSON[key]+"<br>";
                            }
                            alertModal('Error Message',message,'modal-danger');
                        }
                    })
                }
            }
        }
    });

    new Vue({
        el:"#del-modal",
        methods:{
            delTask:function(){
                var id=$('#task_id').val();
                var confirm=$('#confirm').val();

                $.ajax({
                    url:'{{ url("monitor/task/del") }}',
                    type:"post",
                    dataType:"json",
                    data:{id:id,confirm:confirm},
                    success:function(data){
                        if(data['status']==1){
                            var self=$("#delete-"+id).parent().parent().parent();
                            alertModal('Success Message',data['message'],'modal-success');
                            table.row(self).remove().draw();
                        }else{
                            alertModal('Error Message',data['message'],'modal-danger');
                        }
                    },
                    error:function(data){
                        var message="";
                        for(var key in data.responseJSON){
                            message+=key+" : "+data.responseJSON[key]+"<br>";
                        }
                        alertModal('Error Message',message,'modal-danger');
                    }
                })
            },

        }
    });
</script>
@endsection
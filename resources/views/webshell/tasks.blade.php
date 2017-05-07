@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>WebShell Tasks</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body" id="host-table">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-lg-12">
                            <a class="btn btn-primary" href="{{ url('webshell/add') }}">Add New Scan Tasks</a>
                        </div>
                    </div>
                    <table id="table-tasks" class="table table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>TASK NAME</th>
                            <th>HOST</th>
                            <th>SCAN TYPE</th>
                            <th>SCAN MODE</th>
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
                "ajax" : "{{ url("webshell/tasks/get") }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "task_name"},
                    {"data" : "ip"},
                    {"data" : "type"},
                    {"data" : "mode"},
                    {"data" : "status"},
                    {"data" : "created_at"},
                    {"data" : "updated_at"}
                ],
                "columnDefs": [
                    {
                        "render":function(data,type,row){
                            return row['type']+" Scan";
                        },
                        "targets":3
                    },
                    {
                        "render":function(data,type,row){
                            return row['mode']+" Mode";
                        },
                        "targets":4
                    },
                    {
                        "render": function ( data, type, row ) {
                            if(row['status']==="running"){
                                return '<td id="td-status-'+row['id']+'"><button onclick="toggleModal(\'running\','+row['id']+')" class="btn btn-xs btn-block btn-primary">Running</button></td>';
                            }else{
                                return '<div id="td-status-'+row['id']+'"><button class="btn btn-xs btn-block btn-success">Done</button></div>';
                            }
                        },
                        "targets": 5
                    },
                    {
                        "render":function(data,type,row){
                            return '<div class="row">\
                            <div class="col-lg-4" style="margin:0px">\
                            <a href="{{ url("webshell/task/") }}/'+row['id']+'" class="btn btn-xs btn-block btn-info" >DETAIL</a></div>\
                                <div class="col-lg-4" style="margin:0px;padding:0px">\
                                <a href="{{ url('webshell/discover/') }}/'+row['id']+'/show" class="btn btn-xs btn-block btn-warning">RESULT('+row['webshell_count']+')</a>\
                                </div>\
                                <div class="col-lg-4" style="margin:0px">\
                                <button onclick="del('+row['id']+',\''+row['task_name']+'\')" class="btn btn-xs btn-block btn-danger">DELETE</button>\
                                </div>\
                                </div>';
                        },
                        "targets":8
                    }
                ]
            });
        } );

        function del(id,task_name){
            $('#task_id').val(id);
            $('#confirm').attr("placeholder",task_name);
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
                        $.ajax({
                            url:'{{ url("webshell/task/stop") }}/'+id,
                            type:"get",
                            dataType:"json",
                            success:function(data){
                                if(data['status']==1){
                                    alertModal('Success Message',data['message'],'modal-success');
                                    var html="<button class=\"btn btn-xs btn-block btn-success\">Done</button>";
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
                        url:'{{ url("webshell/task/del") }}',
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
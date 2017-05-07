@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>Hosts</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body">
                    <table id="table-hosts" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>IP</th>
                                <th>PORT</th>
                                <th>STORAGE PATH</th>
                                <th>DELAY</th>
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
                <div class="box-footer clearfix">

                </div>
            </div>
            {{--  /.box--}}
        </div>
    </div>



    <div class="modal fade" id="delay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Modify Host's Delay Attr</h4>
                </div>
                <div class="modal-body">
                        <div class="input-group">
                            <div class="input-group-btn">
                                {{ \Form::button('Delay',['class'=>'btn btn-info btn-flat']) }}
                            </div>
                            {{ \Form::hidden('host_id','',['type'=>"hidden",'id'=>"current-host"]) }}
                            {{ \Form::text('delay','',['id'=>"delay-value",'class'=>'form-control']) }}
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button @click="submit" type="button" class="btn btn-info pull-right" data-dismiss="modal">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
                        {{ Form::label("confirm","Please tap Host IP to Confirm this option") }}
                        {{ Form::text("confirm",'',['class'=>'form-control',"placeholder"=>""]) }}
                        {{ Form::hidden("host_id",'',['class'=>'form-control','id'=>'host_id']) }}
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
@endsection

@section('tScript')
    <script>
        function del(id,ip){
            $('#host_id').val(id);
            $('#confirm').attr("placeholder",ip);
            $("#del-modal").modal("toggle");
        }
        new Vue({
            el:"#delay",
            methods:{
                submit:function(){
                    var id=$("#current-host").val();
                    var delay=$("#delay-value").val();
                    $.ajax({
                        url:"{{ url('configuration/update/delay') }}",
                        type:"post",
                        dataType:"json",
                        data:{id:id,delay:delay},
                        success:function(data){
                            if(data['status']===1){
                                alertModal('Success Message',data['message'],'modal-success');
                                $('#alertModal').on("hidden.bs.modal",function(){
                                    window.location.href="{{ url('hosts') }}";
                                });
                            }else{
                                alertModal('Error Message',data['message'],'modal-danger');
                            }
                        }
                    });
                }
            }
        });
        function delay(id,delay){
            $('#delay-value').val(delay);
            $('#current-host').val(id);
            $('#delay').modal();
        }
        $(document).ready(function() {
            table=$('#table-hosts').DataTable({
                "ordering":false,
                "processing" : true,
                "serverSide" : true,
                "paginationType":"full_numbers",
                "ajax" : "{{ url("hosts/get") }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "ip"},
                    {"data" : "port"},
                    {"data" : "storage_path"},
                    {"data" : "delay"},
                    {"data" : "created_at"},
                    {"data" : "updated_at"}
                ],
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            var delay;
                            if(row["delay"]){
                                delay=row["delay"]+" Min";
                            }else{
                                delay="None";
                            }
                            return '<a class="btn btn-xs btn-block btn-info" onclick="delay(\''+row["id"]+'\',\''+row["delay"]+'\')"><span>'+delay+'</span></a>';
                        },
                        "targets": 4
                    },
                    {
                        "render":function(data,type,row){
                            return '<div class="row">\
                                <div class="col-lg-6" style="padding-right: 0px">\
                                <a href="{{ url('configuration') }}/'+row["id"]+'" class="btn btn-xs btn-block btn-info"><span>Details</span></a>\
                                </div>\
                                <div class="col-lg-6">\
                                <button onclick="del('+row['id']+',\''+row['ip']+'\')" class="btn btn-xs btn-block btn-danger">Delete</button>\
                                </div>\
                                </div>';
                        },
                        "targets":7
                    }
                ]
            });

            new Vue({
                el:"#del-modal",
                methods:{
                    delTask:function(){
                        var id=$('#host_id').val();
                        var confirm=$('#confirm').val();
                        $.ajax({
                            url:'{{ url("hosts/del") }}',
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
        } );



    </script>
@endsection
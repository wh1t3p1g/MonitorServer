@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>Users</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-lg-12">
                            <button class="btn btn-primary" onclick="addUser()">Add New Users</button>
                        </div>
                    </div>
                    <table id="table-users" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>LEVEL</th>
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


    <div class="modal fade" id="user-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add New User</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('name') }}
                        {{ Form::text('name','',['class'=>'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('email') }}
                        {{ Form::email('email','',['class'=>'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('password') }}
                        {{ Form::password('password',['class'=>'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('password-confirm','Password Confirm') }}
                        {{ Form::password('password-confirm',['class'=>'form-control']) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button class="btn btn-info pull-right" @click="createUser()" data-dismiss="modal">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="user-update">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Update User Password</h4>
                </div>
                <div class="modal-body">
                    {{ Form::hidden("update-user-id",'',['id'=>'update-user-id']) }}
                    <div class="form-group">
                        {{ Form::label('old-password',"Old Password") }}
                        {{ Form::password('old-password',['class'=>'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('new-password','New Password') }}
                        {{ Form::password('new-password',['class'=>'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('new-password-confirm','New Password Confirm') }}
                        {{ Form::password('new-password-confirm',['class'=>'form-control']) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button class="btn btn-info pull-right" @click="updateUser()" data-dismiss="modal">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection
@includeIf("layouts.alert")

@section('tScript')
    <script>

        var table=$(document).ready(function() {
            $('#table-users').DataTable({
                "ordering":false,
                "processing" : true,
                "serverSide" : true,
                "paginationType":"full_numbers",
                "ajax" : "{{ url("users/get") }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "name"},
                    {"data" : "email"},
                    {"data" : "level"},
                    {"data" : "created_at"},
                    {"data" : "updated_at"}
                ],
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            return '<div class="row">\
                                <div class="col-lg-6" style="padding-right: 5px">\
                                <button onclick="updateUser('+row['id']+')"  class="btn btn-xs btn-block btn-info"><span>Update</span></button>\
                                </div>\
                                <div class="col-lg-6" style="padding-left: 5px" id="delete-'+row['id']+'">\
                                <button onclick="del('+row['id']+')" class="btn btn-xs btn-block btn-danger"><span>Delete</span></button>\
                                </div>\
                                </div>';
                        },
                        "targets": 6
                    },
                    {
                        "render":function(data,type,row){
                            if(row["email"]=== '{{ env('ADMIN_ACCOUNT') }}' ){
                                return "ADMINISTRATOR";
                            }else{
                                return "GUEST";
                            }
                        },
                        "targets":3
                    }
                ]
            });
        } );


        function del(id){
            $.ajax({
                url: '{{ url('users/delete/') }}/'+id,
                type:'GET',
                dataType:"json",
                success:function(data){
                    alertModal('Success Message',data['message'],'modal-success');
                    $('#alertModal').on("hidden.bs.modal",function(){
                        window.location.href="{{ url('users') }}";
                    });
                },
                error:function(data){
                    var message="";
                    for(var key in data.responseJSON){
                        message+=key+":"+data.responseJSON[key]+"<br>";
                    }
                    alertModal('Error Message',message,'modal-danger');
                }
            });
        }

        function addUser(){
            $('#user-add').modal();
        }

        function updateUser(id){
            $('#update-user-id').val(id);
            $('#user-update').modal();
        }

        new Vue({
           el:"#user-add",
            methods:{
                createUser:function(){
                    var name=$('#name').val();
                    var email=$('#email').val();
                    var password=$('#password').val();
                    var password_confirmation=$('#password-confirm').val();
                    $.ajax({
                        url:'{{ url('users/add') }}',
                        type:'post',
                        dataType:"json",
                        data:{name:name,email:email,password:password,password_confirmation:password_confirmation},
                        success:function(data){
                            if(data['status']===1){
                                alertModal('Success Message',data['message'],'modal-success');
                                $('#alertModal').on("hidden.bs.modal",function(){
                                    window.location.href="{{ url('users') }}";
                                });
                            } else{
                                alertModal('Error Message',data['message'],'modal-danger');
                            }
                        },
                        error:function(data){
                            var message="";
                            for(var key in data.responseJSON){
                                message+=key+":"+data.responseJSON[key]+"<br>";
                            }
                            alertModal('Error Message',message,'modal-danger');
                        }
                    });
                }
            }
        });
        new Vue({
            el:"#user-update",
            methods:{
                updateUser:function(){
                    var id=$('#update-user-id').val();
                    var old_password=$('#old-password').val();
                    var new_password=$('#new-password').val();
                    var password_confirmation=$('#new-password-confirm').val();
                    $.ajax({
                        url:'{{ url('users/update') }}/'+id,
                        type:'post',
                        dataType:"json",
                        data:{
                            old_password:old_password,
                            new_password:new_password,
                            new_password_confirmation:password_confirmation},
                        success:function(data){
                            if(data['status']===1){
                                alertModal('Success Message',data['message'],'modal-success');
                            } else{
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
                    });
                }
            }
        });
    </script>
@endsection
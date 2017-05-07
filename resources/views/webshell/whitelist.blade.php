@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>WebShell White List</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body">
                    <table id="table-webshells" class="table table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>FILE NAME</th>
                            <th>HASH</th>
                            <th>STATUS</th>
                            <th>POC</th>
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
@endsection

@section('tScript')
    <script>
        $(document).ready(function() {
            $('#table-webshells').DataTable({
                "ordering":false,
                "processing" : true,
                "serverSide" : true,
                "paginationType":"full_numbers",
                "ajax" : "{{ url("webshell/discover/get/status/inWhiteList") }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "filename"},
                    {"data" : "hash"},
                    {"data" : "status"},
                    {"data" : "poc"},
                    {"data" : "created_at"},
                    {"data" : "updated_at"}
                ],
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            if(row['status']==="inWhiteList"){
                                return '<button onclick="removeWhiteList('+row['id']+')" class="btn btn-xs btn-block btn-warning"><span>In White List</span></button>';
                            }else if(row['status']==="outWhiteList"){
                                return '<button onclick="addWhiteList('+row['id']+')" class="btn btn-xs btn-block btn-primary"><span>Out White List</span></button>';
                            }
                        },
                        "targets": 3
                    },
                    {
                        "render":function(data,type,row){
                            return '<button onclick="del('+row['id']+')" class="btn btn-danger btn-xs btn-block">DELETE</button>';
                        },
                        "targets":7
                    }
                ]
            });
        } );

        function addWhiteList(id){
            $.ajax({
                url:"{{ url('/webshell/check/') }}/"+id+"/change/inWhiteList",
                type:"get",
                dataType:"json",
                success:function(data){
                    if(data['status']==1){
                        alertModal('Success Message',data['message'],'modal-success');
                        $('#alertModal').on("hidden.bs.modal",function(){
                            window.location.href="{{ url('webshell/discover/whitelist') }}";
                        });
                    }else{
                        alertModal('Error Message',data['message'],'modal-danger');
                    }
                }
            });
        }
        function removeWhiteList(id){
            $.ajax({
                url:"{{ url('/webshell/check/') }}/"+id+"/change/outWhiteList",
                type:"get",
                dataType:"json",
                success:function(data){
                    if(data['status']==1){
                        alertModal('Success Message',data['message'],'modal-success');
                        $('#alertModal').on("hidden.bs.modal",function(){
                            window.location.href="{{ url('webshell/discover/whitelist') }}";
                        });
                    }else{
                        alertModal('Error Message',data['message'],'modal-danger');
                    }
                }
            });
        }
        function del(id){
            $.ajax({
                url:"{{ url('/webshell/discover/delete/') }}/"+id,
                type:"get",
                dataType:"json",
                success:function(data){
                    if(data['status']==1){
                        alertModal('Success Message',data['message'],'modal-success');
                        $('#alertModal').on("hidden.bs.modal",function(){
                            window.location.href="{{ url('webshell/discover/whitelist') }}";
                        });
                    }else{
                        alertModal('Error Message',data['message'],'modal-danger');
                    }
                }
            })
        }

    </script>
@endsection
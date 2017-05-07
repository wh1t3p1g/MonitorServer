@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>WebShells</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body">
                    <table id="table-webshells" class="table table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>FILE NAME</th>
                            <th>TASK ID</th>
                            <th>STATUS</th>
                            <th>POC</th>
                            <th>CREATED</th>
                            <th>UPDATED</th>
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
                "ajax" : "{{ url("webshell/discover/get/status/undo") }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "filename"},
                    {"data" : "tasks_id"},
                    {"data" : "status"},
                    {"data" : "poc"},
                    {"data" : "created_at"},
                    {"data" : "updated_at"}
                ],
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            if(row['status']==="undo"){
                                return '<a href="{{ url("webshell/check/") }}/'+row['id']+'" class="btn btn-xs btn-block btn-danger"><span>Undo</span></a>';
                            }
                        },
                        "targets": 3
                    }
                ]
            });
        } );
    </script>

@endsection
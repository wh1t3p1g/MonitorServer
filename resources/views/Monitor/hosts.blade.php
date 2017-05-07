@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>Messages</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body">
                    <table id="table-tasks" class="table table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>TASK ID</th>
                            <th>PROJECT NAME</th>
                            <th>IP</th>
                            <th>COUNTS</th>
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
            table=$('#table-tasks').DataTable({
                "ordering":false,
                "processing" : true,
                "serverSide" : true,
                "paginationType":"full_numbers",
                "ajax" : "{{ url("monitor/message/task/get") }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "task_name"},
                    {"data" : "project_name"},
                    {"data" : "ip"},
                    {"data" : "message_count"},
                    {"data" : "created_at"},
                    {"data" : "updated_at"}
                ],
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            return '<a class="btn btn-xs btn-block btn-info" href="{{ url('monitor/message/') }}/'+row['task_name']+'/show"><span>'+row['message_count']+' msg</span></a>';
                        },
                        "targets": 4
                    }
                ]
            });
        });
    </script>
@endsection
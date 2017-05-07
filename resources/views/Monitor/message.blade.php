@extends('layouts.table')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>Task {{ "<".$task_name.">'s Message" }}</h3>
                </div>
                {{--/.box-header--}}
                <div class="box-body">
                    <table id="table-messages" class="table table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>IP</th>
                            <th>TYPE</th>
                            <th>CONTENT</th>
                            <th>TIME</th>
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
            $('#table-messages').DataTable({
                "ordering":false,
                "processing" : true,
                "serverSide" : true,
                "paginationType":"full_numbers",
                "ajax" : "{{ url("monitor/message/show/get/".$task_name) }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "ip"},
                    {"data" : "type"},
                    {"data" : "content"},
                    {"data" : "time"},
                ],
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            return '<a href="{{ url('monitor/message/delete/') }}/'+row['id']+'" class="btn btn-xs btn-block btn-danger"><span>Delete</span></a>';
                        },
                        "targets": 5
                    }
                ]
            });
        });
    </script>
@endsection
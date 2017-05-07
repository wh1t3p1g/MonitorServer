@extends("layouts.table")

@section("content")
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">WebShell Rules</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-lg-12">
                    <button onclick="addLocalRules()" class="btn btn-primary">Add Local Rules</button>
                    <button onclick="addRemoteRules()" class="btn btn-primary">Add Remote Rules</button>
                </div>
            </div>
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SESSION</th>
                        <th>NAME</th>
                        <th>VALUE</th>
                        <th>CREATED</th>
                        <th>UPDATED</th>
                        <th>OPTIONS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="modal fade" id="add-local-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add Rules</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label("local-source","Local File Path") }}
                        {{ Form::text("local-source",'',['class'=>'form-control',"placeholder"=>""]) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button @click="submitLocal" class="btn btn-info pull-right" data-dismiss="modal">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="add-remote-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add Rules</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label("ip","Remote IP") }}
                        {{ Form::text("ip",'',['class'=>'form-control',"placeholder"=>""]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label("remote-source","Remote File Path") }}
                        {{ Form::text("remote-source",'',['class'=>'form-control',"placeholder"=>""]) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button @click="submitRemote" class="btn btn-info pull-right" data-dismiss="modal">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section("tScript")
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "ordering":false,
                "processing" : true,
                "serverSide" : true,
                "paginationType":"full_numbers",
                "ajax" : "{{ url("webshell/rules/get") }}",
                "columns" : [
                    {"data" : "id"},
                    {"data" : "session"},
                    {"data" : "name"},
                    {"data" : "value"},
                    {"data" : "created_at"},
                    {"data" : "updated_at"}
                ],
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            return data +' ('+ row['id']+')';
                        },
                        "targets": 6
                    }
                ]
            });

            new Vue({
                el:".modal-footer",
                methods:{
                    submitLocal:function(){
                        var source=$('#local-source').val()
                        $.ajax({
                            url:"{{ url("webshell/rules/local/add") }}",
                            type:"post",
//                            dataType:"json",
                            data:{source:source},
                            success:function(data){
                                alert(data);
                            }
                        });
                    },
                    submitRemote:function(){

                    }
                }
            });
        } );

        function addLocalRules(){
            $('#add-local-modal').modal();
        }
        function addRemoteRules(){
            $('#add-remote-modal').modal();
        }
    </script>
@endsection
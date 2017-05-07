@extends('layouts.admin')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Content <b>{{ "< ".$webshell->filename." >" }}</b> </h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
                {!!  $content !!}
        </div>
        <!-- /.box-body -->
        <div class="box-footer" >
            <button @click="addWhiteList({{ $webshell->id }})" class="btn btn-success pull-right" style="margin-left: 10px"><span>Add To WhiteList</span></button>

            <a href="{{ url("/webshell/discover/update/".$webshell->id) }}" class="btn btn-info pull-right" style="margin-left: 10px"><span>Update File</span></a>
            <button @click="deleteFile({{ $webshell->id }})" class="btn btn-danger pull-right"><span>Delete File</span></button>
        </div>
        <!-- /.box-footer-->
    </div>
@endsection

@section("script")
    <script>
        new Vue({
            el:".box-footer",
            methods:{
                addWhiteList:function(id){
                    $.ajax({
                        url:"{{ url('/webshell/check/') }}/"+id+"/change/inWhiteList",
                        type:"get",
                        dataType:"json",
                        success:function(data){
                            if(data['status']===1){
                                alertModal('Success Message',data['message'],'modal-success');
                                $('#alertModal').on("hidden.bs.modal",function(){
                                    window.location.href="{{ url('webshell/discover') }}";
                                });
                            }else{
                                alertModal('Error Message',data['message'],'modal-danger');
                            }
                        }
                    });
                },
                deleteFile:function(id){
                    $.ajax({
                        url:"{{ url('/webshell/check/') }}/"+id+"/change/deleted",
                        type:"get",
                        dataType:"json",
                        success:function(data){
                            if(data['status']===1){
                                alertModal('Success Message',data['message'],'modal-success');
                                $('#alertModal').on("hidden.bs.modal",function(){
                                    window.location.href="{{ url('webshell/discover') }}";
                                });
                            }else{
                                alertModal('Error Message',data['message'],'modal-danger');
                            }
                        }
                    });
                }
            }
        });
    </script>
@endsection
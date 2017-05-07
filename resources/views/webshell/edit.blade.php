@extends("layouts.admin")

@section("content")
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Edit File
            </h3>
            <!-- tools box -->
            <div class="pull-right box-tools">
                <button type="button" class="btn btn-default btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-default btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
            <!-- /. tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body pad">
            <form>
                <textarea name="editor"  class="textarea" placeholder="Place some text here" style="width: 100%; height: 450px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $content !!}</textarea>
            </form>
        </div>
        <div class="box-footer">
            <button @click="submits({{ $webshell->id }})" class="btn btn-primary pull-right">Update</button>
        </div>
    </div>
@endsection
@section("script")
    <script>
        $(function () {
            new Vue({
                el:".box-footer",
                methods:{
                    submits:function(id){
                        var text=$(".textarea").val();
                        $.ajax({
                            url:"{{ url('/webshell/discover/update') }}",
                            type:"post",
                            dataType:"json",
                            data:{id:id,text:text},
                            success:function(data){
                                if(data['status']==1){
                                    alertModal('Success Message',data['message'],'modal-success');
                                    $('#alertModal').on("hidden.bs.modal",function(){
                                        window.location.href="{{ url('webshell/discover/history') }}";
                                    });
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
                        });
                    }
                }
            });
        });
    </script>
@endsection
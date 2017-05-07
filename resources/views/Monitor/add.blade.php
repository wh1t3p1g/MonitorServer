@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection('css')

@section('content')
    <div class="row" >
        <div class="col-lg-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-lg-1">&nbsp;</div>
                        <div class="col-lg-10">
                            <h3>Add New Monitor Task</h3>

                        </div>
                    </div>
                </div>
                <div class="box-body" >
                    <div class="row">
                        <div class="col-lg-1">&nbsp;</div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                {{ Form::label('project_name','Project Name',['class'=>'control-label']) }}
                                {{ Form::text('project_name','',['class'=>'form-control','placeholder'=>'Enter Project Name...']) }}
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                {{ Form::label('host','Host IP',['class'=>'control-label']) }}
                                <select class="form-control select2" id="host">
                                    @foreach($ips as $ip)
                                        <option value="{{$ip->ip}}">{{$ip->ip}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1">&nbsp;</div>

                    </div>
                    <div class="get-path">
                        <div class="row">
                            <div class="col-lg-1">&nbsp;</div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {{ Form::label('monitor_path','Monitor File Path') }}
                                    {{ Form::text('monitor_path','',['class'=>'form-control','placeholder'=>'Click right Button and Choose Path...']) }}
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    {{ Form::label('','Get Monitor File Path') }}
                                    <button @click="getPath" class="form-control btn btn-info">Get</button>
                                </div>
                            </div>
                            <div class="col-lg-1">&nbsp;</div>

                        </div>

                        <div class="row">
                            <div class="col-lg-1">&nbsp;</div>

                            <div class="col-lg-8">
                                <div class="form-group">
                                    {{ Form::label('white_path','White Path') }}
                                    {{ Form::text('white_path','',
                                        ['class'=>'form-control','placeholder'=>'Click right Button and Choose Path...']) }}
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    {{ Form::label('','Get White Path') }}
                                    <button @click="getPath2" class="form-control btn btn-info">Get</button>
                                </div>
                            </div>
                            <div class="col-lg-1">&nbsp;</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-1">&nbsp;</div>

                        <div class="col-lg-10">
                            <div class="form-group">
                                {{ Form::label('black_extension','Black Extensions List') }}
                                {{ Form::text('black_extension','',
                                    ['class'=>'form-control','placeholder'=>'Monitor file extensions, like php,jsp...']) }}
                            </div>
                        </div>
                        <div class="col-lg-1">&nbsp;</div>

                    </div>

                    <div class="row">
                        <div class="col-lg-1">&nbsp;</div>

                        <div class="col-lg-10">
                            <div class="form-group">
                                {{ Form::label('mode','Monitor Mode') }}
                                {{ Form::select('mode',
                                    ['1'=>'Human Management','2'=>'Machine Management'],
                                    null,
                                    ['class'=>'form-control select2','placeholder'=>'Please pick up...']) }}
                            </div>
                        </div>
                        <div class="col-lg-1">&nbsp;</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-1">&nbsp;</div>

                        <div class="col-lg-10">
                            <div class="form-group">
                                {{ Form::label('description','Monitor Project Description') }}
                                {{ Form::textarea('description','',
                                        ['class'=>'form-control','placeholder'=>'Enter...','id'=>'description']) }}
                            </div>
                        </div>
                        <div class="col-lg-1">&nbsp;</div>

                    </div>

                </div>

                <div class="box-footer" id="submit">
                    <a type="submit" class="btn btn-info pull-right" @click="send()">Submit</a>
                </div>
            <!-- /.box-footer -->
            </div>
        </div>
    </div>


    <div class="modal fade" id="monitor-path-select">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Select Monitor File Path</h4>
                </div>
                <div class="modal-body">
                    <div id="treeview-checkable" class="treeview">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button @click="selectNode" class="btn btn-info pull-right" data-dismiss="modal">Select Path</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="white-path-select">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Select White Path</h4>
                </div>
                <div class="modal-body">
                    <div id="treeview-checkable2" class="treeview">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button @click="selectNode" class="btn btn-info pull-right" data-dismiss="modal">Select Path</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection('content')

@includeIf('layouts.alert')

@section('script')
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-treeview.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.select2').select2({
                placeholder: "Please pick up one...",
                allowClear: true
            });
            list=[];

            new Vue({
                el:"#submit",
                methods:{
                    send:function(){
                        var project_name=$('#project_name').val();
                        var host=$('#host').val();
                        var monitor_path=$('#monitor_path').val();
                        var white_path=$('#white_path').val();
                        var black_extension=$('#black_extension').val();
                        var mode=$('#mode').val();
                        var description=$('#description').val();
                        $.ajax({
                            url: '{{ url('monitor/task/add') }}',
                            type:'POST',
                            data:{
                                project_name:project_name,
                                host:host,
                                monitor_path:monitor_path,
                                white_path:white_path,
                                black_extension:black_extension,
                                mode:mode,
                                description:description
                            },
                            dataType:'json',
                            success:function(data){
                                if(data['status']==1){
                                    alertModal('Success Message',data['message'],'modal-success');
                                    $('#alertModal').on("hidden.bs.modal",function(){
                                        window.location.href="{{ url('monitor/tasks') }}";
                                    });
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

            new Vue({
                el:".get-path",
                methods:{
                    getPath:function(){
                        var host=$('#host').val();
                        $.ajax({
                            url:"{{ url('monitor/path') }}",
                            type:"post",
                            dataType:"json",
                            data:{host:host,path:"init"},
                            success:function(data){
                                list=[];
                                list=createTree(data,list,host,"treeview-checkable","monitor-path-select",true);
                                $('#monitor-path-select').modal();
                            },
                            error:function(data){
                                var message="";
                                for(var key in data.responseJSON){
                                    message+=key+" : "+data.responseJSON[key]+"<br>";
                                }
                                alertModal('Error Message',message,'modal-danger');
                            }
                        });
                    },
                    getPath2:function(){
                        var monitor_path=$('#monitor_path').val();
                        var host=$('#host').val();
                        if(monitor_path==null){
                            alertModal('Error Message',"Monitor File Path Can Not Be Empty",'modal-danger');
                        }
                        var jsonData = [{"text":monitor_path,"href":"root","selectable":true,"nodes":[]}];
                        list=[];
                        list=createTree(jsonData,list,host,"treeview-checkable2","white-path-select",false);
                        $('#white-path-select').modal();
                    }
                }
            });
            new Vue({
                el:"#monitor-path-select",
                methods:{
                    selectNode:function(){
                        $("#monitor_path").val(list.join().replace(/\\/g,"/"));
                    }
                }
            });

            new Vue({
                el:"#white-path-select",
                methods:{
                    selectNode:function(){
                        $("#white_path").val(list.join().replace(/\\/g,"/"));
                    }
                }
            });
        });

        function createTree(data,list,host,divId,modalId,single){
            // var jsonData = [{"text":"WebRoot","href":"root","selectable":true,"nodes":[]}];

            $('#'+divId).treeview({
                data: data,
                showIcon: false,
                showCheckbox: true,
                onNodeChecked: function(event, node) {
                    if(single==true){
                        var check_node = $('#'+divId).treeview('getChecked');
                        for(var i=0;i<check_node.length;i++){
                            if(check_node[i].text!=node.text){
                                check_node[i].state.checked=false;
                            }
                        }
                        list.pop(0);
                    }
                    // 拼凑出路径
                    var string = "";
                    var parentNode =  $("#"+divId).treeview("getParent",node.nodeId);
                    while( parentNode.nodeId != null && parentNode.nodeId != undefined){
                        string = parentNode.text + "/" + string;
                        parentNode =  $("#"+divId).treeview("getParent",parentNode.nodeId);
                    }
                    string += node.text;
                    list.push(string);
                },
                onNodeUnchecked: function (event, node) {
                    var string = "";
                    var parentNode =  $("#"+divId).treeview("getParent",node.nodeId);
                    while( parentNode.nodeId != null && parentNode.nodeId != undefined){
                        string = parentNode.text + "/" + string;
                        parentNode =  $("#"+divId).treeview("getParent",parentNode.nodeId);
                    }
                    string += node.text;
                    for(var i = 0; i < list.length; i++){
                        if(list[i]==string){
                            list.splice(i,1);
                        }
                    }
                },
                onNodeExpanded  : function(event, node) {   // 展开
                    var string = "";
                    var parentNode =  $("#"+divId).treeview("getParent",node.nodeId);
                    while( parentNode.nodeId != null && parentNode.nodeId != undefined){
                        string = parentNode.text + "/" + string;
                        parentNode =  $("#"+divId).treeview("getParent",parentNode.nodeId);
                    }
                    string += node.text;
                    $.ajax({
                        url:"{{ url('monitor/path') }}",
                        type:"post",
                        dataType:"json",
                        data:{host:host,path:string},
                        success:function(data){
                            if(data['status']==1){
                                data=data['node'];
                                for(var p in data){//遍历json数组时，这么写p为索引，0,1
                                    if(data[p].nodes!=null&&data[p].nodes!=undefined ){
                                        $("#"+divId).treeview("addNode", [node.nodeId, { node: { text: data[p].text,nodes: [],selectable: data[p].selectable } }]);
                                    }else{
                                        $("#"+divId).treeview("addNode", [node.nodeId, { node: { text: data[p].text,selectable: data[p].selectable } }]);
                                    }
                                }
                            }else{
                                $('#'+modalId).modal('toggle');
                                alertModal('Error Message',data['message'],'modal-danger');
                            }

                        }
                    });
                }
            });
            return list;
        }
    </script>
@endsection('script')
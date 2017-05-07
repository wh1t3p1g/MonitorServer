@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <!-- interactive chart -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-bar-chart-o"></i>

                    <h3 class="box-title">Heart Beats</h3>
                </div>
                <div class="box-body">
                    <div id="interactive" style="height: 300px;"></div>
                </div>
                <!-- /.box-body-->
            </div>
            <!-- /.box -->

        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Configurations</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        {{ Form::hidden('host_id',''.$host->id,['id'=>"host_id"]) }}
                        <div class="col-lg-1">&nbsp;</div>
                        <div class="col-lg-5">
                            <div class="input-group-lg">
                                {{ Form::label('web_root_path',"Web Root Path") }}
                                {{ Form::text('web_root_path',$host->web_root_path,['class'=>'form-control','id'=>'web_root_path']) }}
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group-lg">
                                {{ Form::label('delay',"Heart Beat Delay(Minute)") }}
                                {{ Form::text('delay',$host->delay,['class'=>'form-control','id'=>'delay']) }}
                            </div>
                        </div>
                        <div class="col-lg-1">&nbsp;</div>
                    </div>

                </div>
                <div class="box-footer">
                    <button @click="submit()" type="submit" class="btn btn-info pull-right">Update Configuration</button>
                </div>
                <!-- /.box-body-->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('script')
    <!-- FLOT CHARTS -->
    <script src="{{ asset('js/echarts.common.min.js') }}"></script>
    <script>
        myChart=echarts.init(document.getElementById('interactive'));

        option = {
            title: {
                left: 'center',
                text: 'Heart Beat Line'
            },
            tooltip: {
                trigger: 'none',
                axisPointer: {
                    type: 'cross'
                }
            },
            xAxis: {
                type: 'time',
                name: "Time",
                splitLine: {
                    show: false
                }
            },
            yAxis: {
                name:"Status",
                type: 'category',
                boundaryGap: [0, '100%'],
                splitLine: {
                    show: false
                },
                data:["stopped","running"]
            },
            series: [{
                showSymbol: false,
                hoverAnimation: false,
                type: 'line',
                data: []
            }]
        };

        $.ajax({
            url:'{{ url('/configuration/get/date') }}/{{ $host->id }}',
            type:"GET",
            dataType:'json',
            success:function(data){
                option.series=[{
                    type: 'line',
                    data: data
                }];
                myChart.setOption(option);
            }
        });

        setInterval(function () {

            $.ajax({
                url:'{{ url('/configuration/get/date') }}/{{ $host->id }}',
                type:"GET",
                dataType:'json',
                success:function(data){
                    myChart.setOption({
                        series: [{
                            type: 'line',
                            data: data
                        }]
                    });
                }
            });

        }, {{ 60000*intval($host->delay) }});

        new Vue({
            el:".box-footer",
            methods:{
                submit:function(){
                    var id=$('#host_id').val();
                    var web_root_path=$('#web_root_path').val();
                    var delay=$('#delay').val();

                    $.ajax({
                        url:"{{ url('configuration/update/all') }}",
                        type:"post",
                        dataType:"json",
                        data:{id:id,web_root_path:web_root_path,delay:delay},
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

    </script>
@endsection

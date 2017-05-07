@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $monitor_task_count or "0" }}</h3>

                    <p>Monitor Tasks</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-clipboard"></i>
                </div>
                <a href="{{ url('monitor/tasks') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $host_count or 0 }}</h3>

                    <p>Hosts</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ url('hosts') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $user_count or 0 }}</h3>

                    <p>User Registrations</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ url('users') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $webshell_count or 0 }}</h3>

                    <p>Undo WebShells</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ url('webshell/discover') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <!-- solid sales graph -->
    <div class="box box-solid bg-teal-gradient">
        <div class="box-header">
            <i class="fa fa-th"></i>

            <h3 class="box-title">Message Graph</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="box-body border-radius-none">
            <div class="chart" id="line-chart" style="height: 250px;"></div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-border">
            <div class="row">
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                    <input type="text" class="knob" data-readonly="true" value="2" data-width="60" data-height="60" data-fgColor="#39CCCC">

                    <div class="knob-label">Tasks</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                    <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC">

                    <div class="knob-label">Online</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="2" data-width="60" data-height="60" data-fgColor="#39CCCC">

                    <div class="knob-label">Hosts</div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
@endsection

@section('script')
<!-- FLOT CHARTS -->
<script src="{{ asset('js/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset('plugins/knob/jquery.knob.js') }}"></script>
{{--<script src="{{ asset('plugins/flot/jquery.flot.min.js') }}"></script>--}}
<script>
    $(".knob").knob();
    $.ajax({
        url:"{{ url('message/get') }}",
        type:"get",
        dataType:"json",
        success:function(data){
            var line = new Morris.Line({
                element: 'line-chart',
                resize: true,
                data: data,
                xkey: 'time',
                ykeys: ['monitor', 'webshell'],
                labels: ['monitor', 'webshell'],
                lineColors: ['#efefef', '#8f9d8e'],
                lineWidth: 2,
                hideHover: 'auto',
                gridTextColor: "#fff",
                gridStrokeWidth: 0.4,
                pointSize: 4,
                pointStrokeColors: ["#efefef", '#8f9d8e'],
                gridLineColor: "#efefef",
                gridTextFamily: "Open Sans",
                gridTextSize: 10
            });
        }
    });
</script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/morris/morris.css') }}">
@endsection

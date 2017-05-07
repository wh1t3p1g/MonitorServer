<?php

namespace App\Http\Controllers;

use App\Eloquents\Hosts;
use App\Eloquents\Messages;
use App\Eloquents\MonitorTasks;
use App\Eloquents\Webshells;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userCount=User::all()->count();
        $webshellCount=Webshells::where("status",'=',0)->count();
        $hostCount=Hosts::all()->count();
        $monitorTaskCount=MonitorTasks::all()->count();
        $type=$request->session()->get('type')[0];
        $name=$request->session()->get('name')[0];
        $current='Home';
        return view('home')
                    ->withType($type)
                    ->withName($name)
                    ->withCurrent($current)
                    ->withUserCount($userCount)
                    ->withHostCount($hostCount)
                    ->withWebshellCount($webshellCount)
                    ->withMonitorTaskCount($monitorTaskCount);
    }




    public function messageInfo(){
        $messages=Messages::orderBy('time','asc')->get();
        $total=array();
        foreach($messages as $message){
            $time=new Carbon($message->time);
            $str_time=$time->year."-".$time->month."-".$time->day;
            if(array_key_exists($str_time,$total)){
                $total[$str_time][0]+=1;
            }else{
                $total[$str_time]=[1,0];
            }
        }
        $webshells=Webshells::orderBy('time','asc')->get();
        foreach($webshells as $webshell){
            $time=new Carbon($webshell->time);
            $str_time=$time->year."-".$time->month."-".$time->day;
            if(array_key_exists($str_time,$total)){
                $total[$str_time][1]+=1;
            }else{
                $total[$str_time]=[0,1];
            }
        }
        $results=array();
        foreach ($total as $key=>$value){
            $temp=array(
                "time"=>$key,
                "monitor"=>$value[0],
                "webshell"=>$value[1]
            );
            $results[]=$temp;
        }
        return $results;

    }



}

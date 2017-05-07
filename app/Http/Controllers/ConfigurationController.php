<?php

namespace App\Http\Controllers;

use App\Eloquents\Hosts;
use App\Http\Controllers\Api\HeartBeatController as HeartBeat;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\CommunicateController as Communicate;

class ConfigurationController extends Controller
{
    /**
     * ConfigurationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request,$id){
        $host=Hosts::findOrfail($id);
        $type=$request->session()->get('type')[0];
        $name=$request->session()->get('name')[0];
        return view('configuration')->withHost($host)
                                        ->withType($type)
                                        ->withName($name)
                                        ->withCurrent('Configuration');
    }

    /**
     * @description host delay update
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author wh1t3P1g
     */
    public function updateHostDelay(Request $request){
        $id=$request->get('id');
        $delay=$request->get('delay');
        $host=Hosts::find($id);
        if($delay<=0.5||$delay>60){
            return array('status'=>-1,'message'=>'delay limit from 0.5 to 60');
        }
        if($host){
            Hosts::where('id','=',$id)
                    ->update(['delay'=>$delay]);
            $data=["delay"=>$delay];
            Communicate::setDelay($host,$data);
            return array("status"=>1,"message"=>"Update Configuration Success");
        }else{
            return array('status'=>-1,'message'=>'host not found');
        }
    }

    /**
     * @description
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author wh1t3P1g
     */
    public function updateConfiguration(Request $request){
        $host=Hosts::findOrfail($request->get('id'));
        $delay=$request->get('delay');
        $webRootPath=$request->get('web_root_path') or "";
        if($delay<=0.5||$delay>60){
            return array('status'=>-1,'message'=>'delay limit from 0.5 to 60');
        }
        if($host->delay!=$delay){
            $host->delay=$delay;//todo send client
            $data=["delay"=>$delay];
            Communicate::setDelay($host,$data);
        }
        $host->web_root_path=$webRootPath;
        $host->update();
        return array("status"=>1,"message"=>"Update Configuration Success");
    }

    public function getDate($id){
        $host=Hosts::findOrfail($id);
        $data=json_decode($host->data);
        $data=HeartBeat::fixTimeArray($data,$host->delay,0);
        return $data;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Messages;
use App\Eloquents\MonitorTasks;
use App\Eloquents\Webshells;
use Carbon\Carbon;
use Faker\Provider\zh_CN\DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Eloquents\Hosts;
use App\Http\Controllers\Api\CommunicateController as Communicate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

/**
 * Class HeartBeatController
 * @package App\Http\Controllers\Api
 * @author wh1t3P1g
 */
class HeartBeatController extends Controller
{
    /**
     * @description heart beat DONE
     *              insert new hosts and update old hosts
     * @param \Illuminate\Http\Request $request
     * @return array
     * @author wh1t3P1g
     */
    public function index(Request $request){
        $ip=$request->get('ip');
        $heartBeat=Hosts::where('ip','=',$ip)->count();
        if($heartBeat){

            $token=$_COOKIE["_"];
            $host=Hosts::where('ip','=',$ip)->first();
//            if(!Communicate::check($token,$host->key,$host->iv))
//                return array('status'=>-1,'message'=>'error message');
            $data=HeartBeatController::fixTimeArray(json_decode($host->data),$host->delay,1);
            $web_root_path=
                Communicate::decrypto($request->get('web_root_path'),$host->key,$host->iv);
            $storage_path=
                Communicate::decrypto($request->get('storage_path'),$host->key,$host->iv);
            $heartBeat=Hosts::where('ip','=',$ip)
                    ->update([
                        'data'=>json_encode($data),
                        'delay'=>floatval($request->get('delay')),
                        'web_root_path'=>$web_root_path,
                        'storage_path'=>$storage_path,
                        'port'=>$request->get('port')]);
            $taskData=\GuzzleHttp\json_decode(Communicate::decrypto($request->get("data"),$host->key,$host->iv));
            foreach ($taskData as $key=>$value){
                MonitorTasks::where('task_name','=',$key)->update(['status'=>$value==1?"running":"stopped"]);
            }
            if($heartBeat){
                return array('status'=>1,'message'=>'update old host success');
            }else{
                return array('status'=>-1,'message'=>'update old host failed');
            }
        }else{
            $host=new Hosts();
            $host->ip=$request->get('ip');
            $host->port=$request->get('port');
            $host->delay=floatval($request->get('delay'));
            if($request->exists('web_root_path'))
                $host->web_root_path=urldecode($request->get('web_root_path'));
            $set=Communicate::generate();
            $host->key=$set[0];
            $host->iv=$set[1];
            $host->data=json_encode(
                [
                    [date('Y/m/d H:i:s',time()),1]
                ]
            );
            $host->storage_path=urldecode($request->get('storage_path'));
            if($host->save()){
                return array('status'=>3,'message'=>'insert new host success',"_"=>$set[0].$set[1]);
            }else{
                return array('status'=>-2,'message'=>'insert new host failed');
            }
        }
    }


    public static function fixTimeArray($data,$delay,$flag){
        $last=end($data);
        $last_time=strtotime($last[0]);
        $current=time();
        $interval=$current-$last_time;
        $threshold=$delay*60;
        while($interval>=$threshold){
            if(count($data)==60){
                array_shift($data);
            }
            $temp=array();
            $last_time+=$threshold;
            $interval=$current-$last_time;
            $temp[0]=date('Y/m/d H:i:s',$last_time);
            if($interval>$threshold){
                $temp[1]=0;
            }else{
                $temp[1]=$flag;
            }
            array_push($data,$temp);
        }
        return $data;
    }
    public function test(Request $request){
        $eTime=time();
        var_dump($eTime);
        $data="4WGxD9%2BT%2FgLRkY68jORvGkvzHsja0UxO8lHXuxyUapb702Ti7KtuQmuR3QUT9RWGzMXI7UftdRqx%0D%0AXcgzcMQe%2FdUmXue8z6t3C7klB%2Ff4R48qbdWzuiVCjcy1SnNaLt0UFChn2fxuA8McuiN9Hj3Ldg%3D%3D";
        $sTime=urldecode(Communicate::decrypto(urldecode($data),"qMVBpMg5zagmaimw","7pbem7ETy5E1LpCi"));
        echo "\n";
        echo $sTime;
    }
}

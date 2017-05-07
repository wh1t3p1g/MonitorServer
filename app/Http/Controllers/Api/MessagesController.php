<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Hosts;
use App\Eloquents\Messages;
use App\Eloquents\Webshells;
use App\Eloquents\WebshellTasks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CommunicateController as Communicate;

/**
 * 交流控件
 * 负责消息的接收
 * Class MessagesController
 * @package App\Http\Controllers\Api
 * @author wh1t3P1g
 */
class MessagesController extends Controller
{

    /**
     * @description add new message
     * @param \Illuminate\Http\Request $request
     * @param $ip
     * @return array
     * @author wh1t3P1g
     */
    public function add(Request $request,$ip){
        $host=Hosts::where('ip','=',$ip)->firstOrFail();
        $token=$_COOKIE["_"];
        if(!Communicate::check($token,$host->key,$host->iv))
            return array('status'=>-1,"message"=>"error message");
        $message=new Messages();
        $message->hosts_id=$host->id;
        $message->type=$request->get('type');
        $message->time=$request->get('time');
        $message->content=Communicate::decrypto($request->get('content'),$host->key,$host->iv);
        $message->task_name=$request->get('task_name');
        return array('status'=>$message->save());
    }

    public function scanMessage(Request $request,$ip){
        $host=Hosts::where('ip','=',$ip)->firstOrfail();
        $token=$_COOKIE["_"];
        if(!Communicate::check($token,$host->key,$host->iv))
            return array('status'=>-1,"message"=>"error message");
        $size=intval($request->get('size'));
        $task_id=$request->get('task_id');
        if($size>0){
            $time=$request->get('time');
            $data=Communicate::decrypto($request->get('data'),$host->key,$host->iv);
            $data=\GuzzleHttp\json_decode($data);
            foreach ($data as $key => $value){
                $webshell=new Webshells();
                $webshell->hosts_id=$host->id;
                $webshell->tasks_id=$task_id;
                $webshell->time=$time;
                $hash=self::getHash($host,$key);
                if(Webshells::where([["hash",'=',$hash],["status","=","inWhiteList"]])->count())continue;
                $webshell->hash=$hash;
                $webshell->fullpath=$key;
                if(strpos($key,":\\")!==false){
                    $temp=preg_split("/\\\\/",$key);
                    $key=$temp[count($temp)-1];
                }

                $webshell->filename=basename($key);
                $webshell->poc=$value;
                $webshell->status="undo";
                $webshell->save();
            }
        }
        WebshellTasks::where('task_id','=',$task_id)->update(['status'=>"done"]);
        return "store success";
    }

    public function getHash($host,$filepath){
        $data=["filepath"=>$filepath];
        $response=CommunicateController::getFileHash($host,$data);
        if(is_array($response)){
            return "";
        }else{
            return $response->getContents();
        }
    }
}

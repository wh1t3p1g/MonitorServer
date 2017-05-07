<?php

namespace App\Http\Controllers\Webshell;

use App\Eloquents\Webshells;
use App\Http\Controllers\Api\CommunicateController as Communicate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index($id){
        $webshell=Webshells::findOrfail($id);
        $host=$webshell->host;
        $data=["filepath"=>$webshell->fullpath];
        $content=Communicate::getFile($host,$data);
        if(is_array($content)){
            $content=\GuzzleHttp\json_encode($content);
        } else{
            $content=$content->getContents();

            $hash=sha1($content);
            if($webshell->hash!=$hash){
                $webshell->hash=$hash;
                $webshell->update();
            }

            $content=htmlspecialchars($content);
            $content=preg_replace("/\r\n|\n/","<br>",$content);
            $content=preg_replace("/\t/","&nbsp;&nbsp;&nbsp;&nbsp;",$content);
        }
        $type=session('type')[0];
        $name=session('name')[0];
        return view("webshell.check")
                ->withType($type)
                ->withName($name)
                ->withCurrent("Check Files")
                ->withContent($content)
                ->withWebshell($webshell);
    }

    public function changeStatus($id,$status){
        $webshell=Webshells::find($id);
        $message=["WebShell Not Found","","Delete WebShell Success","Add To White List Success","Remove from White List Success"];
        if(!$webshell){
            return array('status'=>-1,'message'=>$message[0]);
        }
        if($status==="deleted"){
            $host=$webshell->task->host;
            $data=["indexPath"=>$webshell->fullpath,"taskName"=>$webshell->tasks_id];
            $response=Communicate::delFile($host,$data);
            if(is_array($response)){
                return $response;
            }else{
                $response=\GuzzleHttp\json_decode($response->getContents());
                if($response->status!==1){
                    return $response;
                }
            }
        }
        $webshell->status=$status;
        $result=$webshell->update();
        if($result){
            if($status==="inWhiteList"){
                return array('status'=>1,'message'=>$message[3]);
            }elseif($status==="outWhiteList"){
                return array('status'=>1,'message'=>$message[4]);
            }elseif($status==="deleted"){
                return array('status'=>1,'message'=>$message[2]);
            }elseif($status==="modified"){
                return array('status'=>1,'message'=>$message[1]);
            }
        }else{
            return array('status'=>0,'message'=>'Update Status Failed');
        }
    }
}

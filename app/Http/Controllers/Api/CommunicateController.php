<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

/**
 * 交流控件
 * 负责发送http请求
 * Class CommunicateController
 * @package App\Http\Controllers\Api
 * @author wh1t3P1g
 */
class CommunicateController extends Controller
{
    public static function startMonitor($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["taskName"]=self::encrypto($data["taskName"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/startMonitor");
        return self::post($url,$data);
    }

    public static function stopMonitor($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["taskName"]=self::encrypto($data["taskName"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/stopMonitor");
        return self::post($url,$data);
    }

    public static function addTask($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["taskName"]=self::encrypto($data["taskName"],$host->key,$host->iv);
        $data["projectName"]=self::encrypto($data["projectName"],$host->key,$host->iv);
        $data["monitorPath"]=self::encrypto($data["monitorPath"],$host->key,$host->iv);
        $data["whiteList"]=self::encrypto($data["whiteList"],$host->key,$host->iv);
        $data["blackList"]=self::encrypto($data["blackList"],$host->key,$host->iv);
        $data["remark"]=self::encrypto($data["remark"],$host->key,$host->iv);
        $data["RunMode"]=self::encrypto($data["RunMode"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/addMonitor");
        return self::post($url,$data);
    }

    public static function updateTask($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["taskName"]=self::encrypto($data["taskName"],$host->key,$host->iv);
        $data["projectName"]=self::encrypto($data["projectName"],$host->key,$host->iv);
        $data["monitorPath"]=self::encrypto($data["monitorPath"],$host->key,$host->iv);
        $data["whiteList"]=self::encrypto($data["whiteList"],$host->key,$host->iv);
        $data["blackList"]=self::encrypto($data["blackList"],$host->key,$host->iv);
        $data["remark"]=self::encrypto($data["remark"],$host->key,$host->iv);
        $data["RunMode"]=self::encrypto($data["RunMode"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/editMonitor");
        return self::post($url,$data);
    }

    public static function deleteTask($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["taskName"]=self::encrypto($data["taskName"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/deleteMonitor");
        return self::post($url,$data);
    }

    public static function getPath($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["rootPath"]=self::encrypto($data["rootPath"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/getPath");
        return self::get($url,$data);
    }

    public static function getFile($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["filepath"]=self::encrypto($data["filepath"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/getSuspiciousFile");
        return self::post($url,$data);
    }

    public static function getFileHash($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["filepath"]=self::encrypto($data["filepath"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/getSuspiciousFileSHA1");
        return self::post($url,$data);
    }

    public static function delFile($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["indexPath"]=self::encrypto($data["indexPath"],$host->key,$host->iv);
        $data["taskName"]=self::encrypto($data["taskName"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/removeFile");
        return self::post($url,$data);
    }

    public static function addWebShellTask($host,$data=null){

        $data=self::fixData($data,$host->key,$host->iv);
        $data["task_name"]=self::encrypto($data["task_name"],$host->key,$host->iv);
        $data["task_id"]=self::encrypto($data["task_id"],$host->key,$host->iv);
        $data["file_path"]=self::encrypto($data["file_path"],$host->key,$host->iv);
        $data["except_path"]=self::encrypto($data["except_path"],$host->key,$host->iv);
        $data["except_extension"]=self::encrypto($data["except_extension"],$host->key,$host->iv);
        $data["filter"]=self::encrypto($data["filter"],$host->key,$host->iv);
        $data["type"]=self::encrypto($data["type"],$host->key,$host->iv);
        $data["mode"]=self::encrypto($data["mode"],$host->key,$host->iv);
        $data["script_extension"]=self::encrypto($data["script_extension"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/webshell/task/new");
        return self::post($url,$data);
    }

    public static function stopWebShellTask($host,$data=null){

        $data=self::fixData(array("_"=>1),$host->key,$host->iv);
        $url=self::fixUrl($host,"/webshell/task/stop");
        return self::get($url,$data);
    }

    public static function updateFile($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $data["indexPath"]=self::encrypto($data["indexPath"],$host->key,$host->iv);
        $url=self::fixUrl($host,"/editFile");
        return self::post($url,$data);
    }

    public static function setDelay($host,$data=null){
        $data=self::fixData($data,$host->key,$host->iv);
        $url=self::fixUrl($host,"/setDelay");
        return self::post($url,$data);
    }

    public static function get($url,$data=null){
        try{
            $client=new Client();
            $response=$client->request('GET',$url,['query'=>$data,"connect_timeout"=>2]);
            return $response->getBody();
        }catch (ConnectException $e){
            return array("status"=>-1,"message"=>$e->getMessage());
        }
    }

    public static function post($url,$data=null){
        try{
            $client=new Client();
            $response=$client->request('POST',$url,['form_params'=>$data,"connect_timeout"=>2]);
            return $response->getBody();
        }catch (ConnectException $e){
            return array("status"=>-1,"message"=>$e->getMessage());
        }
    }

    public static function emptyFiles($task_name){
        $files=Storage::disk('public')->files();
        foreach ($files as $file) {
            if(strpos($file,$task_name)!==false){
                Storage::disk('public')->delete($file);
            }
        }
    }

    public static function fixUrl($host,$path){
        return "http://".$host->ip.":".$host->port.$path;
    }

    public static function fixData($data,$key,$iv){
        if($data==null)
            return null;
        $data["_"]=self::encrypto(time(),$key,$iv);
        return $data;
    }

    public static function encrypto($data,$key,$iv){
        return urlencode(urlencode(base64_encode(openssl_encrypt(urlencode($data),"AES-128-CFB",$key,OPENSSL_RAW_DATA,$iv))));
    }

    public static function decrypto($data,$key,$iv){
        return urldecode(openssl_decrypt(base64_decode(urldecode($data)),"AES-128-CFB",$key,OPENSSL_RAW_DATA,$iv));
    }

    public static function generate(){
        return [str_random(16),str_random(16)];
    }

    public static function check($data,$key,$iv){
        $eTime=time();
        $sTime=intval(self::decrypto($data,$key,$iv));
        return abs($eTime-$sTime)<=60;
    }

}

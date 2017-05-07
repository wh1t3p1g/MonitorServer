<?php

namespace App\Http\Controllers\Webshell;

use App\Eloquents\Webshells;
use App\Eloquents\WebshellTasks;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CommunicateController as Communicate;

class DiscoveredController extends Controller
{
    //
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('webshell.discovered')
            ->withType($type)
            ->withName($name)
            ->withCurrent('WebShells');
    }

    public function history(){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('webshell.history')
                ->withType($type)
                ->withName($name)
                ->withCurrent('Option History');
    }

    public function whiteList(){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('webshell.whitelist')
            ->withType($type)
            ->withName($name)
            ->withCurrent('WebShell WhiteList');
    }

    public function getWebshellByStatus(Request $request,$status){
        global $data;
        $data=$request->all();
        if($status==="history"){
            $count=Webshells::whereIn("status",["inWhiteList","outWhiteList","modified","deleted"])
                ->where(function($query){
                    global $data;
                    $query->Where("filename","like","%".$data['search']['value']."%")
                        ->orWhere("tasks_id","like","%".$data['search']['value']."%")
                        ->orWhere("status","like","%".$data['search']['value']."%")
                        ->orWhere("created_at","like","%".$data['search']['value']."%");
                })
                ->count();
            $webshells=Webshells::whereIn("status",["inWhiteList","outWhiteList","modified","deleted"])
                ->where(function($query){
                    global $data;
                    $query->Where("filename","like","%".$data['search']['value']."%")
                        ->orWhere("tasks_id","like","%".$data['search']['value']."%")
                        ->orWhere("status","like","%".$data['search']['value']."%")
                        ->orWhere("created_at","like","%".$data['search']['value']."%");
                })
                ->skip($data['start'])->take($data['length'])
                ->get();
        }else{
            $count=Webshells::where("status",'=',$status)
                ->where(function($query){
                    global $data;
                    $query->Where("filename","like","%".$data['search']['value']."%")
                        ->orWhere("tasks_id","like","%".$data['search']['value']."%")
                        ->orWhere("status","like","%".$data['search']['value']."%")
                        ->orWhere("created_at","like","%".$data['search']['value']."%");
                })
                ->count();
            $webshells=Webshells::where("status",'=',$status)
                ->where(function($query){
                    global $data;
                    $query->Where("filename","like","%".$data['search']['value']."%")
                        ->orWhere("tasks_id","like","%".$data['search']['value']."%")
                        ->orWhere("status","like","%".$data['search']['value']."%")
                        ->orWhere("created_at","like","%".$data['search']['value']."%");
                })
                ->skip($data['start'])->take($data['length'])
                ->get();
        }

        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$webshells->toArray();
        return $results;
    }

    public function show($id){
        $task=WebshellTasks::findOrfail($id);
        $total=$task->webshell->count();
        $type=session('type')[0];
        $name=session('name')[0];
        return view('webshell.show')
            ->withType($type)
            ->withName($name)
            ->withTotal($total)
            ->withCurrent('WebShell Tasks')
            ->withTask($task);
    }

    public function getWebshells(Request $request,$id){
        $task=WebshellTasks::findOrfail($id);
        $data=$request->all();
        $count=Webshells::where("tasks_id",$task->task_id)
                        ->where(function($query){
                            global $data;
                            $query->Where("filename","like","%".$data['search']['value']."%")
                                ->orWhere("tasks_id","like","%".$data['search']['value']."%")
                                ->orWhere("status","like","%".$data['search']['value']."%")
                                ->orWhere("created_at","like","%".$data['search']['value']."%");
                        })
                        ->count();
        $webshells=Webshells::where("tasks_id",$task->task_id)
            ->where(function($query){
                global $data;
                $query->Where("filename","like","%".$data['search']['value']."%")
                    ->orWhere("tasks_id","like","%".$data['search']['value']."%")
                    ->orWhere("status","like","%".$data['search']['value']."%")
                    ->orWhere("created_at","like","%".$data['search']['value']."%");
            })
            ->skip($data['start'])->take($data['length'])
            ->get();
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$webshells->toArray();
        return $results;
    }

    public function delete($id){
        $webshell=Webshells::where("id",'=',$id)->delete();
        if(!$webshell)
            return array("status"=>-1,"message"=>"Webshell Not Found,Delete Failed");
        else
            return array("status"=>1,"message"=>"Webshell Delete Success");

    }

    public function updatePage($id){
        $webshell=Webshells::findOrfail($id);
        $host=$webshell->task->host;
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
        }
        $type=session('type')[0];
        $name=session('name')[0];
        return view('webshell.edit')
            ->withType($type)
            ->withName($name)
            ->withCurrent('Edit File')
            ->withWebshell($webshell)
            ->withContent($content);
    }

    public function update(Request $request){
        $this->validate($request,[
            "id"=>"required",
            "text"=>"required"
        ]);
        $text=$request->get("text");
        $webshell=Webshells::findOrfail($request->get("id"));

        $text=htmlspecialchars_decode($text);
        $data=["indexPath"=>$webshell->fullpath,"content"=>$text];
        $host=$webshell->task->host;
        $response=Communicate::updateFile($host,$data);
        if(is_array($response)){
            return $response;
        } else{
            $response=\GuzzleHttp\json_decode($response->getContents());
            if($response->status!==1){
                return $response;
            }
        }
        $webshell->status="modified";
        $result=$webshell->update();
        if($result){
            return ["status"=>1,"message"=>"edit file success"];
        }else{
            return ["status"=>-1,"message"=>"edit file failed"];
        }
    }
}

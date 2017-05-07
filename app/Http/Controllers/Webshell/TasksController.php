<?php

namespace App\Http\Controllers\Webshell;

use App\Eloquents\Hosts;
use App\Eloquents\Webshells;
use App\Eloquents\WebshellTasks;
use App\Http\Controllers\Api\CommunicateController as Communicate;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;

class TasksController extends Controller
{
    //
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * @description
     * @param \Illuminate\Http\Request $request
     * @param int $start
     * @author wh1t3P1g
     */
    public function index(){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('webshell.tasks')
                        ->withType($type)
                        ->withName($name)
                        ->withCurrent('WebShell Tasks');
    }

    public function getTasks(Request $request){
        $data=$request->all();
        $count=WebshellTasks::join('hosts', 'hosts_id', '=', 'hosts.id')
            ->orWhere("task_name","like","%".$data['search']['value']."%")
            ->orWhere("type","like","%".$data['search']['value']."%")
            ->orWhere("mode","like","%".$data['search']['value']."%")
            ->orWhere("status","like","%".$data['search']['value']."%")
            ->orWhere("ip","like","%".$data['search']['value']."%")
            ->count();
        $messages=WebshellTasks::join('hosts', 'hosts_id', '=', 'hosts.id')
            ->orWhere("task_name","like","%".$data['search']['value']."%")
            ->orWhere("type","like","%".$data['search']['value']."%")
            ->orWhere("mode","like","%".$data['search']['value']."%")
            ->orWhere("status","like","%".$data['search']['value']."%")
            ->orWhere("ip","like","%".$data['search']['value']."%")
            ->skip($data['start'])->take($data['length'])
            ->get(["webshell_tasks.id","task_id","task_name","ip","type","mode","status",
                "webshell_tasks.created_at","webshell_tasks.updated_at"]);
        foreach ($messages as $message) {
            $message->webshell_count=$message->webshell->count();
        }
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$messages->toArray();
        return $results;
    }

    /**
     * @description
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author wh1t3P1g
     */
    public function addPage(Request $request){
        $type=$request->session()->get('type')[0];
        $name=$request->session()->get('name')[0];
        $ips=Hosts::get(['ip']);
        return view('webshell.add')
                        ->withType($type)
                        ->withName($name)
                        ->withCurrent('Add Task')
                        ->withIps($ips);
    }

    /**
     * @description
     * @param \Illuminate\Http\Request $request
     * @return array
     * @author wh1t3P1g
     */
    public function add(Request $request){
        $this->validate($request,[
            'task_name'=>'bail|required',
            'host'=>'required',
            'file_path'=>'required',
            'type'=>'required',
            'mode'=>'required',
            'description'=>'required',
            "script_extension"=>'required'
        ]);
        $host=Hosts::where('ip','=',$request->get('host'))->firstOrfail();
        $task=new WebshellTasks();
        $task->task_name=$request->get('task_name');
        $task->hosts_id=$host->id;
        $task->task_id=Uuid::generate()->string;
        $task->file_path=$request->get('file_path');
        $task->except_path=$request->get('except_path') or "";
        $task->except_extension=$request->get('except_extension') or "";
        $task->type=$request->get('type');
        $task->mode=$request->get('mode');
        $task->status="running";
        $task->script_extension=$request->get('script_extension');
        $task->description=$request->get('description');
        $map=config("common.webshell");
        $data=array(
            'task_name'=>$task->task_name,
            "task_id"=>$task->task_id,
            "file_path"=>$task->file_path,
            "except_path"=>$task->except_path,
            "except_extension"=>$task->except_extension,
            "filter"=>"true",
            "type"=>$map["type"][$task->type],
            "mode"=>$map["mode"][$task->mode],
            "script_extension"=>$task->script_extension
        );
        $response=Communicate::addWebShellTask($host,$data);
        if(strpos($response,"running")!==false)
            return array('status'=>-1,"message"=>$response->getContents()." One Client Only Allow One Scan Task");
        $result=$task->save();
        if($result){
            return array('status'=>1,'message'=>'Add New Scan Task Success');
        }else{
            return array('status'=>0,'message'=>'Add New Scan Task Failed');
        }
    }

    /**
     * @description
     * @param $id
     * @return array
     * @author wh1t3P1g
     */
    public function stop($id){
        $task=WebshellTasks::findOrfail($id);

        $response=Communicate::stopWebShellTask($task->host);
        if(strpos($response,"Success")!==false){
            $result= array("status"=>1,"message"=>"Stop Task <".$task->task_name."> Success");
        }elseif($task->status===0){
            $result= array("status"=>-1,"message"=>"Stop Task <".$task->task_name."> Failed,Nothing to Stop");
        }
        $task->status=0;
        $task->update();
        return $result;
    }


    public function delete(Request $request){
        $this->validate($request,[
            'id'=>'required',
            'confirm'=>'required'
        ]);
        $id=$request->get('id');
        $confirm=$request->get('confirm');
        $task=WebshellTasks::findOrfail($id);
        if($task->task_name!==$confirm){
            return array("status"=>-1,"message"=>"Delete Project ".$confirm." Error, Project Name Not Matched");
        }
        $result=$task->delete();
        if($result){
            Communicate::emptyFiles($task->task_name);
            return array("status"=>1,"message"=>"Delete Project ".$confirm." Success");
        }else{
            return array("status"=>-1,"message"=>"Delete Project ".$confirm." Failed");
        }
    }
}

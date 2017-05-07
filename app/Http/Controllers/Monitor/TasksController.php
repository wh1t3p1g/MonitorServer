<?php

namespace App\Http\Controllers\Monitor;

use App\Eloquents\Hosts;
use App\Eloquents\MonitorTasks;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;
use App\Http\Controllers\Api\CommunicateController as Communicate;


class TasksController extends Controller
{
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
        return view('monitor.tasks')
                    ->withType($type)
                    ->withName($name)
                    ->withCurrent('Tasks');
    }

    public function getTasks(Request $request){
        $data=$request->all();
        $count=MonitorTasks::join('hosts', 'hosts_id', '=', 'hosts.id')
            ->orWhere("project_name","like","%".$data['search']['value']."%")
            ->orWhere("ip","like","%".$data['search']['value']."%")
            ->orWhere("monitor_tasks.created_at","like","%".$data['search']['value']."%")
            ->orWhere("status","like","%".$data['search']['value']."%")
            ->count();
        $messages=MonitorTasks::join('hosts', 'hosts_id', '=', 'hosts.id')
            ->orWhere("project_name","like","%".$data['search']['value']."%")
            ->orWhere("ip","like","%".$data['search']['value']."%")
            ->orWhere("monitor_tasks.created_at","like","%".$data['search']['value']."%")
            ->orWhere("status","like","%".$data['search']['value']."%")
            ->skip($data['start'])->take($data['length'])
            ->get(["monitor_tasks.id","ip","project_name","run_mode","status","monitor_tasks.created_at","monitor_tasks.updated_at"]);
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$messages->toArray();
        return $results;
    }

    public function addPage(Request $request){
        $ips=Hosts::get(['ip']);
        $type=$request->session()->get('type')[0];
        $name=$request->session()->get('name')[0];
        return view('monitor.add')
                    ->withType($type)
                    ->withName($name)
                    ->withCurrent('Add Task')
                    ->withIps($ips);
    }

    public function updatePage(Request $request,$id){
        $task=MonitorTasks::findOrfail($id);
        $ips=Hosts::get(['ip']);
        $type=$request->session()->get('type')[0];
        $name=$request->session()->get('name')[0];
        return view('monitor.update')
            ->withType($type)
            ->withName($name)
            ->withTask($task)
            ->withCurrent('Add Task')
            ->withIps($ips);
    }

    public function add(Request $request){
        $this->validate($request,[
            'project_name'=>"required",
            'host'=>"required",
            'monitor_path'=>"required",
            'white_path'=>"required",
            'black_extension'=>"required",
            'mode'=>"required",
            'description'=>"required",
        ]);
        $host=Hosts::where('ip','=',$request->get('host'))->first();
        if(!$host)
            return array('status'=>-1,'message'=>"host id not found, check host ip...");
        $task=new MonitorTasks();
        $task->project_name=$request->get('project_name');
        $task->task_name=Uuid::generate()->string;
        $task->hosts_id=$host->id;
        $task->monitor_path=$request->get('monitor_path');
        $task->white_list=$request->get('white_path');
        $task->black_list=$request->get('black_extension');
        $task->run_mode=$request->get('mode');
        $task->description=$request->get('description');
        $task->status="stopped";
        $data=array(
            'taskName'=>$task->task_name,
            "projectName"=>$task->project_name,
            "monitorPath"=>$task->monitor_path,
            "whiteList"=>$task->white_list,
            "blackList"=>$task->black_list,
            "remark"=>$task->description,
            "RunMode"=>$task->run_mode
        );
        $response=Communicate::addTask($host,$data);
        if(strpos($response,"Failed"))
            return $response;
        $result=$task->save();
        if($result){
            return array('status'=>1,'message'=>'Add New Monitor Task Success');
        }else{
            return array('status'=>0,'message'=>'Add New Monitor Task Failed');
        }
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'project_name'=>"required",
            'host'=>"required",
            'monitor_path'=>"required",
            'white_path'=>"required",
            'black_extension'=>"required",
            'mode'=>"required",
            'description'=>"required",
        ]);

        $task=MonitorTasks::findOrfail($id);
        $task->project_name=$request->get('project_name');
        $task->monitor_path=$request->get('monitor_path');
        $task->white_list=$request->get('white_path');
        $task->black_list=$request->get('black_extension');
        $task->run_mode=$request->get('mode');
        $task->description=$request->get('description');
        $task->status="0";

        $data=array(
            'taskName'=>$task->task_name,
            "projectName"=>$task->project_name,
            "monitorPath"=>$task->monitor_path,
            "whiteList"=>$task->white_list,
            "blackList"=>$task->black_list,
            "remark"=>$task->description,
            "RunMode"=>$task->run_mode
        );
        $response=Communicate::updateTask($task->host,$data);
        if(strpos($response,"Failed"))
            return $response;
        $result=$task->update();
        if($result){
            return array('status'=>1,'message'=>'Update Monitor Task Success');
        }else{
            return array('status'=>0,'message'=>'Update Monitor Task Failed');
        }
    }

    public function getSubPath(Request $request){
        $this->validate($request,[
            'host'=>"required",
            'path'=>'required'
        ]);
        $host=Hosts::where('ip','=',$request->get('host'))->firstOrfail();
        $path=$request->get('path');
        if($path==="init"){
            $web_root_path=explode(",",$host->web_root_path);
            $results=array();
            foreach($web_root_path as $path){
                $temp=array(
                    "text"=>$path,
                    "nodes"=>[]
                );
                $results[]=$temp;
            }
            return $results;
        }else{
            $data=['rootPath'=>$path];
            $response=Communicate::getPath($host,$data);
            return $response;
        }
    }

    public function delete(Request $request){
        $this->validate($request,[
            'id'=>'required',
            'confirm'=>'required'
        ]);
        $id=$request->get('id');
        $confirm=$request->get('confirm');
        $task=MonitorTasks::findOrfail($id);
        if($task->project_name!==$confirm){
            return array("status"=>-1,"message"=>"Delete Project ".$confirm." Error, Project Name Not Matched");
        }
        $data=["taskName"=>$task->task_name];
        $response=Communicate::deleteTask($task->host,$data);
        if(is_array($response)){
            return $response;
        }else{
            $temp=\GuzzleHttp\json_decode($response->getContents());
            if($temp->status!==1){
                return $response;
            }
        }
        $result=MonitorTasks::where('id','=',$id)->delete();
        if($result){
            Communicate::emptyFiles($task->task_name);
            return array("status"=>1,"message"=>"Delete Project ".$confirm." Success");
        }else{
            return array("status"=>-1,"message"=>"Delete Project ".$confirm." Failed");
        }
    }

    public function toggle(Request $request){
        $this->validate($request,[
            'id'=>'required',
            'status'=>'required'
        ]);
        $id=$request->get('id');
        $status=$request->get('status');
        $task=MonitorTasks::find($id);
        if(!$task){
            return array("status"=>-1,"message"=>"Not Found Task");
        }
        $data=["taskName"=>$task->task_name];
        if($status==="stopped"){// stop > run
            $response=Communicate::startMonitor($task->host,$data);
        }else{
            $response=Communicate::stopMonitor($task->host,$data);
        }
        if(is_array($response)){
            return $response;
        }else{
            $temp=\GuzzleHttp\json_decode($response->getContents());
            if($temp->status!==1){
                return $response;
            }
        }
        $task->status=$status=="running"?"stopped":"running";
        $result=$task->update();
        if($result){
            return array("status"=>1,"message"=>($status=="running"?"Stop":"Start")." Task Success");
        }else{
            return array("status"=>-1,"message"=>($status=="running"?"Stop":"Start")." Task Failed");
        }
    }
}

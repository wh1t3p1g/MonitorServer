<?php

namespace App\Http\Controllers\Monitor;

use App\Eloquents\Hosts;
use App\Eloquents\Messages;
use App\Eloquents\MonitorTasks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @description list messages
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author wh1t3P1g
     */
    public function index(){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('monitor.hosts')
                                    ->withType($type)
                                    ->withName($name)
                                    ->withCurrent('Messages');
    }



    public function get(Request $request){
        $data=$request->all();
        $count=MonitorTasks::join('hosts', 'hosts_id', '=', 'hosts.id')
            ->orWhere("task_name","like","%".$data['search']['value']."%")
            ->orWhere("project_name","like","%".$data['search']['value']."%")
            ->orWhere("ip","like","%".$data['search']['value']."%")
            ->count();
        $tasks=MonitorTasks::join('hosts', 'hosts_id', '=', 'hosts.id')
            ->orWhere("task_name","like","%".$data['search']['value']."%")
            ->orWhere("project_name","like","%".$data['search']['value']."%")
            ->orWhere("ip","like","%".$data['search']['value']."%")
            ->skip($data['start'])->take($data['length'])
            ->get();
        foreach ($tasks as $task) {
            $task->message_count=$task->message->count();
        }
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$tasks->toArray();
        return $results;
    }

    /**
     * @description show specific ip's messages
     * @param \Illuminate\Http\Request $request
     * @param $hosts_id
     * @param int $start
     * @return mixed
     * @author wh1t3P1g
     */
    public function show($task_name){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('monitor.message')
                                    ->withType($type)
                                    ->withName($name)
                                    ->withCurrent('Message')
                                    ->withTaskName($task_name);
    }

    public function getShow(Request $request,$task_name){
        global $data;
        $data=$request->all();
        $count=Messages::join('hosts', 'hosts_id', '=', 'hosts.id')
                        ->where("task_name","=",$task_name)
                        ->where(function($query){
                            global $data;
                            $query->where("task_name","like","%".$data['search']['value']."%")
                                ->orWhere("type","like","%".$data['search']['value']."%")
                                ->orWhere("time","like","%".$data['search']['value']."%")
                                ->orWhere("content","like","%".$data['search']['value']."%")
                                ->orWhere("ip","like","%".$data['search']['value']."%");

                        })
                        ->count();
        $messages=Messages::join('hosts', 'hosts_id', '=', 'hosts.id')
            ->where("task_name","=",$task_name)
            ->where(function($query){
                global $data;
                $query->where("task_name","like","%".$data['search']['value']."%")
                    ->orWhere("type","like","%".$data['search']['value']."%")
                    ->orWhere("time","like","%".$data['search']['value']."%")
                    ->orWhere("content","like","%".$data['search']['value']."%")
                    ->orWhere("ip","like","%".$data['search']['value']."%");

            })->orderBy("time","desc")
            ->orderBy("messages.id","desc")
            ->skip($data['start'])->take($data['length'])
            ->get(["messages.id","ip","type","content","time"]);
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$messages->toArray();
        return $results;
    }

    public function delete($id){
        if(Messages::where('id','=',$id)->delete()){
            return redirect('monitor/message');
        }
    }
}

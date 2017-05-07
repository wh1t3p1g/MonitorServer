<?php

namespace App\Http\Controllers;

use App\Eloquents\Hosts;
use Illuminate\Http\Request;

class HostsController extends Controller
{

    /**
     * HostsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @description show hosts
     * @param \Illuminate\Http\Request $request
     * @author wh1t3P1g
     */
    public function index(){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('hosts')->withType($type)
                                 ->withName($name)
                                 ->withCurrent("Hosts");
    }

    /**
     * @description json table
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author wh1t3P1g
     */
    public function getHosts(Request $request){
        $data=$request->all();
        $count=Hosts::Where("ip","like","%".$data['search']['value']."%")
            ->orWhere("port","like","%".$data['search']['value']."%")
            ->orWhere("storage_path","like","%".$data['search']['value']."%")
            ->count();
        $hosts=Hosts::Where("ip","like","%".$data['search']['value']."%")
                    ->orWhere("port","like","%".$data['search']['value']."%")
                    ->orWhere("storage_path","like","%".$data['search']['value']."%")
                    ->skip($data['start'])->take($data['length'])
                    ->get(["id","ip","port","storage_path","delay","created_at","updated_at"]);
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$hosts->toArray();
        return $results;
    }

    public function delete(Request $request){
        $this->validate($request,[
            'id'=>'required',
            'confirm'=>'required'
        ]);
        $id=$request->get('id');
        $confirm=$request->get('confirm');
        $host=Hosts::findOrfail($id);
        if($host->ip!==$confirm){
            return array("status"=>-1,"message"=>"Delete Host ".$confirm." Error, Host IP Not Matched");
        }
        if(count($host->monitorTasks)>0){
            return array("status"=>-1,"message"=>"Delete Host ".$confirm." Error, Monitor Task IS NOT DELETED");
        }
        $result=$host->delete();
        if($result){
            return array("status"=>1,"message"=>"Delete Host ".$confirm." Success");
        }else{
            return array("status"=>-1,"message"=>"Delete Host ".$confirm." Failed");
        }
    }
}

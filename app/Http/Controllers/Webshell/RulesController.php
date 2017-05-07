<?php

namespace App\Http\Controllers\Webshell;

use App\Eloquents\Rules;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RulesController extends Controller
{
    //
    use ValidatesRequests;
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(){
        $type=session('type')[0];
        $name=session('name')[0];
        return view('webshell.rules')
            ->withType($type)
            ->withName($name)
            ->withCurrent('WebShell Rules');
    }

    public function getRules(Request $request){
        $data=$request->all();
        $count=Rules::where("session","like","%".$data['search']['value']."%")
                    ->orWhere("name","like","%".$data['search']['value']."%")
                    ->orWhere("value","like","%".$data['search']['value']."%")
                    ->count();
        $rules=Rules::where("session","like","%".$data['search']['value']."%")
            ->orWhere("name","like","%".$data['search']['value']."%")
            ->orWhere("value","like","%".$data['search']['value']."%")
            ->skip($data['start'])->take($data['length'])
            ->get();
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$rules->toArray();
        return $results;
    }

    public function addLocalSource(Request $request){
        $this->validate($request,[
            "source"=>"required"
        ]);
        $source=storage_path().'/'.$request->get('source');
        if(file_exists($source)){
            $rules=parse_ini_file($source);
            var_dump($rules);
        }else{
            echo "no file";
        }

    }

    public function addRemoteSource(Request $request){

    }
}

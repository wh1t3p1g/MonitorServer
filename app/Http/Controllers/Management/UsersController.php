<?php

namespace App\Http\Controllers\Management;

use Illuminate\Foundation\Auth\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class UsersController extends Controller
{
    //
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware(["auth","auth.level"]);
    }

    /**
     * @description
     * @param int $start
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author wh1t3P1g
     */
    public function index($start=0){
        $pages=User::all()->count();
        if(($pages%10)==0){
            $pages=intval($pages/10);
        }else{
            $pages=intval($pages/10)+1;
        }
        $users=User::skip($start)->take(10)->get();
        $type=session('type')[0];
        $name=session('name')[0];
        $current='Users';
        return view('users')->withStart($start)
                            ->withPages($pages)
                            ->withUsers($users)
                            ->withType($type)
                            ->withName($name)
                            ->withCurrent($current);
    }

    public function getUsers(Request $request){
        $data=$request->all();
        $count=User::where("name","like","%".$data['search']['value']."%")
                    ->orWhere("email","like","%".$data['search']['value']."%")
                    ->count();
        $users=User::where("name","like","%".$data['search']['value']."%")
            ->orWhere("email","like","%".$data['search']['value']."%")
            ->skip($data['start'])->take($data['length'])
            ->get(["id","name","email","type","created_at","updated_at"]);
        $results["recordsFiltered"]=$count;
        $results["draw"]=$data['draw'];
        $results["recordsTotal"]=$count;
        $results["data"]=$users->toArray();
        return $results;

    }

    /**
     * @description
     * @param \Illuminate\Http\Request $request
     * @return array
     * @author wh1t3P1g
     */
    public function add(Request $request){
        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        $user=new User();
        $user->name=$request->get("name");
        $user->email=$request->get("email");
        $user->password=bcrypt($request->get("password"));
        if($user->save()){
            return array("status"=>1,"message"=>"Add User ".$user->name." Success");
        }else{
            return array("status"=>0,"message"=>"Add User ".$user->name." Failed");
        }
    }

    /**
     * @description
     * @param $id
     * @return array
     * @author wh1t3P1g
     */
    public function delete($id){
        $user=User::findOrfail($id);
        $name=$user->name;
        $result=$user->delete();
        if($result){
            return array("status"=>1,"message"=>"Delete User ".$name." Success");
        }else{
            return array("status"=>0,"message"=>"Delete User ".$name." Failed");
        }
    }

    /**
     * @description
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return array
     * @author wh1t3P1g
     */
    public function update(Request $request,$id){
        $this->validate($request,[
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',
        ]);
        $user=User::where('id','=',$id)->firstOrfail();
        if(!Hash::check($request->get('old_password'),$user->password)){
            return array("status"=>-1,"message"=>"Update User Failed, Old Password wrong");
        }
        $result=User::where('id','=',$id)->update(['password'=>bcrypt($request->get('new_password'))]);
        if($result){
            return array("status"=>1,"message"=>"Update User ".$user->name." Success");
        }else{
            return array("status"=>0,"message"=>"Update User ".$user->name." Failed");
        }
    }
}

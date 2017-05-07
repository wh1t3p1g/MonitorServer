<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class InstallController extends Controller
{
    //

    public function checkInstalled(){
        $lock=public_path()."/lock.file";
        if(file_exists($lock)){
            return redirect("home");
        }else{
            $user=new User();
            $user->name=env("ADMIN_NAME");
            $user->email=env("ADMIN_ACCOUNT");
            $user->password=bcrypt(env("ADMIN_PASSWORD"));
            $user->save();
            fputs(fopen($lock,"w"),"1");
            return redirect("login");
        }
    }
}

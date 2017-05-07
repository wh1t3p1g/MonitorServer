<?php

namespace App\Http\Controllers\Webshell;

use App\Eloquents\WebshellTasks;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;

class TaskDetailController extends Controller
{
    //
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index($id){
        $task=WebshellTasks::findOrfail($id);
        $type=session("type")[0];
        $name=session("name")[0];

        return view("webshell.detail")
                ->withType($type)
                ->withName($name)
                ->withCurrent("Scan Task Detail")
                ->withTask($task);
    }


}

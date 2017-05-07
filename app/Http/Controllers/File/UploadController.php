<?php

namespace App\Http\Controllers\File;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('upload');
    }

    /**
     * file upload
     * allow (rar,ind) file extension
     * @param Request $request
     */
    public function up(Request $request){
        $file=$request->file('file');
        $extension=array('rar','ind');
        if(in_array($file->getClientOriginalExtension(),$extension)){
            $path=$file->move(storage_path().'/app/public/',$file->getClientOriginalName());
            return array('status'=>1,'message'=>'upload '.$path.' success');
        }else{
            return array('status'=>0,'message'=>'extension error');
        }
    }
}

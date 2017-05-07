<?php

namespace App\Http\Controllers\File;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DownloadController extends Controller
{
    //
    public function index($filename){
        if(\Storage::disk('public')->exists($filename)){
            $contents = \Storage::disk('public')->get($filename);
            return $contents;
        }else{
            return "not found";
        }
    }
}

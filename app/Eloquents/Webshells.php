<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Webshells extends Model
{
    //
    public function task(){
        return $this->belongsTo("App\Eloquents\WebshellTasks","tasks_id","task_id");
    }

    public function host(){
        return $this->belongsTo("App\Eloquents\Hosts","hosts_id");
    }

    public function getFilenameAttribute($value){
        return strip_tags($value);
    }
    public function getFullpathAttribute($value){
        return strip_tags($value);
    }
    public function getHashAttribute($value){
        return strip_tags($value);
    }
    public function getPocAttribute($value){
        return strip_tags($value);
    }
    public function getStatusAttribute($value){
        return strip_tags($value);
    }
    public function getTasksIdAttribute($value){
        return strip_tags($value);
    }

}

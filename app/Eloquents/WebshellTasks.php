<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;
class WebshellTasks extends Model
{
    //

    /**
     * @description
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author wh1t3P1g
     */
    public function host()
    {
        return $this->belongsTo('App\Eloquents\Hosts','hosts_id',"id");
    }

    public function webshell(){
        return $this->hasMany('App\Eloquents\Webshells',"tasks_id","task_id");
    }

    public function getTaskNameAttribute($value){
        return strip_tags($value);
    }

    public function getFilePathAttribute($value){
        return strip_tags($value);
    }

    public function getExceptExtensionAttribute($value){
        return strip_tags($value);
    }

    public function getExceptPathAttribute($value){
        return strip_tags($value);
    }

    public function getScriptExtensionAttribute($value){
        return strip_tags($value);
    }

    public function getTypeAttribute($value){
        return strip_tags($value);
    }
    public function getModeAttribute($value){
        return strip_tags($value);
    }
    public function getStatusAttribute($value){
        return strip_tags($value);
    }public function getDescriptionAttribute($value){
        return strip_tags($value);
    }

}

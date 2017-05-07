<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class MonitorTasks extends Model
{

    /**
     * @description
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author wh1t3P1g
     */
    public function host()
    {
        return $this->belongsTo('App\Eloquents\Hosts','hosts_id');
    }

    public function message(){
        return $this->hasMany("App\Eloquents\Messages","task_name","task_name");
    }

    public function getProjectNameAttribute($value){
        return strip_tags($value);
    }
    public function getMonitorPathAttribute($value){
        return strip_tags($value);
    }
    public function getWhiteListAttribute($value){
        return strip_tags($value);
    }
    public function getBlackListAttribute($value){
        return strip_tags($value);
    }
    public function getStatusAttribute($value){
        return strip_tags($value);
    }
    public function getBcModeAttribute($value){
        return strip_tags($value);
    }

    public function getRunModeAttribute($value){
        return strip_tags($value);
    }

    public function getDescriptionAttribute($value){
        return strip_tags($value);
    }


}

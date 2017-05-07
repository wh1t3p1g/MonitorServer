<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Hosts extends Model
{
    /**
     * table name
     * @var string
     */
    protected $table = 'hosts';

    /**
     * @description
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author wh1t3P1g
     */
    public function messages()
    {
        return $this->hasMany('App\Eloquents\Messages');
    }

    /**
     * @description
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author wh1t3P1g
     */
    public function webshellTasks(){
        return $this->hasMany('App\Eloquents\WebshellTasks');
    }

    /**
     * @description
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author wh1t3P1g
     */
    public function monitorTasks(){
        return $this->hasMany('App\Eloquents\MonitorTasks');
    }


    public function getIpAttribute($value){
        return strip_tags($value);
    }

    public function getDelayAttribute($value){
        return strip_tags($value);
    }

    public function getPortAttribute($value){
        return strip_tags($value);
    }

    public function getStoragePathAttribute($value){
        return strip_tags($value);
    }

    public function getWebRootPathAttribute($value){
        return strip_tags($value);
    }

}

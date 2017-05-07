<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    /**
     * @var string
     * @author wh1t3P1g
     */
    protected $table = 'messages';

    /**
     * @description
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author wh1t3P1g
     */
    public function host()
    {
        return $this->belongsTo('App\Eloquents\Hosts',"hosts_id");
    }


    public function getTaskNameAttribute($value){
        return strip_tags($value);
    }

    public function getTypeAttribute($value){
        return strip_tags($value);
    }

    public function getTimeAttribute($value){
        return strip_tags($value);
    }

    public function getContentAttribute($value){
        return strip_tags($value);
    }

}

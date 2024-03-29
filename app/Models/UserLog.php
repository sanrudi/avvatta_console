<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'user_logs';

    public function loggable(){
        return $this->morphTo();
    }

    public function erosnow()
    {
        return $this->hasOne('App\Models\AvErosNows', 'content_id', 'loggable_id');
    }

    public function avvatta_user()
    {
        return $this->hasOne('App\Models\AvvattaUser', 'id', 'user_id');
    }

    // public function video_content()
    // {
    //     return $this->hasOne('App\Models\SubCategory', 'id', 'cat_id');      
    // }
}

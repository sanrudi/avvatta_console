<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoContent extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'video_content';

    public function user_log()
    {
        return $this->morphMany('App\Models\UserLog', 'loggable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvErosNows extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'av_eros_nows';

    public function user_log()
    {
        return $this->morphMany('App\Models\UserLog', 'loggable');
    }
}

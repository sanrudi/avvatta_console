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
}

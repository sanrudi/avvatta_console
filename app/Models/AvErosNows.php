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
    
    public function erosnow()
    {
        return $this->hasMany('App\Models\UserLog', 'content_id', 'content_id');
    }

    public function watches()
    {
        return $this->hasMany('App\Models\UserLog', 'content_id', 'content_id');      
    }

    public function unique_watches()
    {
        $query = $this->hasMany('App\Models\UserLog', 'content_id', 'content_id');
        return $query->groupBy('user_id');
    }

    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist', 'content_id', 'content_id');      
    }

    public function avg_watch()
    {
        return $this->eros_watches()
        ->selectRaw('avg(duration) as aggregate')
        ->first('content_id');
    }
}

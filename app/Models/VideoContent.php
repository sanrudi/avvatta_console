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

    public function watches()
    {
        return $this->hasMany('App\Models\UserLog', 'loggable_id','content_id');      
    }

    public function unique_watches()
    {
        $query = $this->hasMany('App\Models\UserLog', 'loggable_id', 'content_id');
        return $query->groupBy('user_id');
    }

    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist', 'content_id', 'loggable_id');      
    }

    public function avg_watch()
    {
        return $this->watches()
        ->selectRaw('avg(duration) as aggregate')
        ->first('content_id');
    }
}

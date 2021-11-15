<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvErosNowsPrefer extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'av_eros_nows_prefer';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'prefer_content_id',
        'prefer',
    ];
    public $timestamps = false;

    public function erosnow_data()
    {
        return $this->hasOne('App\Models\AvErosNows', 'content_id', 'prefer_content_id');      
    }
}

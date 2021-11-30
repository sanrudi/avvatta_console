<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionKid extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'promotion_video_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'prefer_content_id',
        'prefer',
        'category',
        'main_cat',
        'main_sub_cat',
        'content_type',
    ];
    public $timestamps = false;

    public function kid_data()
    {
        return $this->hasOne('App\Models\VideoContent', 'id', 'prefer_content_id');      
    }
}

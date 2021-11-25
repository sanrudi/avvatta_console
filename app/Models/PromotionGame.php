<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionGame extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'promotion_games';

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

    public function game_data()
    {
        return $this->hasOne('App\Models\GameContent', 'id', 'prefer_content_id');      
    }
}

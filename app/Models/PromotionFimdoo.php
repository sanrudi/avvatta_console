<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionFimdoo extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql2';

    protected $table = 'promotion_filmdoo';

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

    public function filmdoo_data()
    {
        return $this->hasOne('App\Models\FilmdooContent', 'id', 'prefer_content_id');      
    }
}

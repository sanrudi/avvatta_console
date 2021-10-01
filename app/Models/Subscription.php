<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'subscriptions';

    public function user_payments()
    {
        return $this->hasMany('App\Models\UserPayment', 'subscription_id','id');      
    }
}

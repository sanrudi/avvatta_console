<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'user_payments';

    public function user_payments_avvatta_users()
    {
        return $this->hasOne('App\Models\AvvattaUser', 'id','user_id');      
    }

    public function user_payments_subscriptions()
    {
        return $this->hasOne('App\Models\Subscription', 'id','subscription_id');      
    }
}

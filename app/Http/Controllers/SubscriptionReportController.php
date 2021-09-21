<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SubscriptionReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $subscriptions = DB::connection('mysql2')
        ->table('user_payments')
        ->groupBy('subscription_id')
        ->select('subscription_id',count('subscription_id'))
        ->orderBy('subscription_id')
        ->get();
        foreach($subscriptions as $subscription){
            print_r($subscription->subscription_id." - ".$subscription->subscription_id."<br>");
        }
        //dd($subscriptions);

        dd("test");
        return view('dashboard');
    }
}

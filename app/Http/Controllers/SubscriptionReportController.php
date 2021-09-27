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
        $subscriptionsQuery = DB::connection('mysql2')->table('user_payments');
        $subscriptionsQuery->rightjoin('subscriptions', 'user_payments.subscription_id', '=', 'subscriptions.id');
        $subscriptionsQuery->groupBy('subscriptions.plan');
        $subscriptionsQuery->select('subscriptions.plan',  DB::raw("count('subscriptions.plan') as sub_plan_count"));
        $subscriptionsQuery->orderByRaw('CONVERT(subscriptions.plan, SIGNED)');
        $subscriptions = $subscriptionsQuery->get();
        return view('subscription-report')
        ->with([
            'subscriptions'=>$subscriptions
        ]);
    }
}

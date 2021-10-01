<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Subscription;

class SubscriptionReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $userLogs = UserLog::all();
    }

    public function subscriptionTotal(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        }

        $subscriptionsQuery = Subscription::select('subscriptions.*');
        $subscriptionsQuery->with(['user_payments' => function ($query) use ($request,$startDate,$endDate) {
            $query->where('status','=', 1);
            $query->where('is_cancelled','=',0);
            if($startDate){
                $query->where('created_at', '>=', $startDate);
            }
            if($endDate){
                $query->where('created_at', '<=', $endDate);
            }
        }]);
        $subscriptions = $subscriptionsQuery->get();
        //dd($subscriptions);
        return view('subscription-total')
        ->with([
            'subscriptions'=>$subscriptions
        ]);

        // $subscriptionsQuery = DB::connection('mysql2')->table('user_payments');
        // $subscriptionsQuery->rightjoin('subscriptions', 'user_payments.subscription_id', '=', 'subscriptions.id');
        // $subscriptionsQuery->groupBy('subscriptions.plan');
        // $subscriptionsQuery->select('subscriptions.plan',  DB::raw("count('subscriptions.plan') as sub_plan_count"));
        // $subscriptionsQuery->orderByRaw('CONVERT(subscriptions.plan, SIGNED)');
        // $subscriptions = $subscriptionsQuery->get();
        // return view('subscription-customers')
        // ->with([
        //     'subscriptions'=>$subscriptions
        // ]);

    }

    
    public function subscriptionCustomer(Request $request)
    {
        // $reportFrom="";$startDate="";$endDate="";
        // $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        // $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        // $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        
        // date_default_timezone_set('Africa/Johannesburg');
        // $today = date("Y-m-d H:m:s");
        // if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
        //     $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        // }

        $subscriptionsQuery = Subscription::select('subscriptions.*');
        $subscriptionsQuery->with(['user_payments' => function ($query) use ($request) {
            $query->where('status','=', 1);
            $query->where('is_cancelled','=',0);
            // if($startDate){
            //     $query->where('created_at', '>=', $startDate);
            // }
            // if($endDate){
            //     $query->where('created_at', '<=', $endDate);
            // }
        }]);
        $subscriptions = $subscriptionsQuery->get();
        //dd($subscriptions);
        return view('subscription-customer')
        ->with([
            'subscriptions'=>$subscriptions
        ]);

    }
}

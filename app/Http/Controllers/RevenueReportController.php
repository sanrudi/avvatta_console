<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RevenueReportController extends Controller
{
    function index(Request $request)
    {
        
        $fromdate = date('Y-m', strtotime('-1 month')).'-01';
        if($request->input('startDate') != null) {
        $fromdate = $request->input('startDate');
        }
        $todate = date('Y-m') .'-00';
        if($request->input('endDate') != null) {
        $todate = $request->input('endDate'); 
        }
        // last month
        // Firstday
        // get catogories
        $cat = DB::connection('mysql2')->table('categories')
                ->select('id','name')
                ->get();
        
        $frequency = ['Daily','Weekly','Monthly'];
        $subscription = DB::connection('mysql2')->table('subscriptions')
                ->select('subscriptions.id', 'categories.name','title','main_cat_id','amount')
                ->join('categories','main_cat_id','=','categories.id')
                ->get();
     /*   
        $todate = DB::table('user_payments')
                ->max('created_at');
         $fromdate = DB::table('user_payments')
                ->min('created_at');       
                var_dump($todate);
          var_dump($fromdate);
       */   
          
        foreach ($subscription as $value) {   
        $all[$value->id] = DB::connection('mysql2')->table('user_payments')     
                ->where('subscription_id','=',$value->id)
                ->whereBetween('user_payments.created_at',array($fromdate,$todate))
                ->get()->sum('amount');
        
        // get subs count
         $subcount[$value->id] = DB::connection('mysql2')->table('user_payments')
                 ->where('subscription_id','=',$value->id)
                 ->whereBetween('user_payments.created_at',array($fromdate,$todate))
                 ->count();
        }
        
         $all['total'] = DB::connection('mysql2')->table('user_payments')
                  ->whereBetween('user_payments.created_at',array($fromdate,$todate))
                            ->get()->sum('amount');
        
         $all['count'] = DB::connection('mysql2')->table('user_payments')
                  ->whereBetween('user_payments.created_at',array($fromdate,$todate))
                            ->get()->count();
         $all['fromdate'] = Carbon::parse($fromdate);     
         $all['todate'] = Carbon::parse($todate);

       // var_dump($all);
        return view('revenue-report',['cat'=>$cat,
                                  'frequency'=>$frequency,
                                  'subs'=>$subscription,
             'subcount'=>$subcount,
                                  'all' =>$all]);
    }
}

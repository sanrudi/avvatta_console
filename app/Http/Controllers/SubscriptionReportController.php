<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Subscription;
use App\Models\UserPayment;
use App\Models\AvvattaUser;
use App\Exports\Subscriptions\DailyTransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class SubscriptionReportController extends Controller
{
    private $country;
    public function __construct(Request $request)
    {
        // $this->middleware('auth');
        // check the domain and set country
        $this->country = env('COUNTRY','SA');
        $server_host = $request->server()['SERVER_NAME'];
                $referer =  request()->headers->get('referer');
                if($server_host=='gh.avvatta.com') {
                 
                    $this->country = 'GH';
                    
                }
              
                if($server_host=='ng.avvatta.com') {
                 
                    $this->country = 'NG';
                    
                }
        
    }


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
            if($startDate){
                $query->whereDate('created_at', '>=', $startDate);
            }
            if($endDate){
                $query->whereDate('created_at', '<=', $endDate);
            }
        }]);
        $subscriptions = $subscriptionsQuery->get();
        return view('subscription-total')
        ->with([
            'subscriptions'=>$subscriptions
        ]);

    }

    
    public function subscriptionCustomer(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        
        $sevenDays = date('Y-m-d H:i:s', strtotime($today.'-7 day'));
        $fourteenDays = date('Y-m-d H:i:s', strtotime($today.'-14 day'));

        $userPaymentQuery = UserPayment::select('user_payments.*')
        ->with('user_payments_avvatta_users')
        ->with('user_payments_subscriptions');
        switch ($this->country) {
            
           case 'SA':
               $userPaymentQuery->where('user_payments.user_country','=', 0);
               break;
           case 'GH':
               $userPaymentQuery->where('user_payments.user_country','=', 1);
               break;
           case 'NG':
               $userPaymentQuery->where('user_payments.user_country','=', 2);
               break;
           default:
               break;
        }
        if($startDate){
            $userPaymentQuery->whereDate('user_payments.created_at', '>=', $startDate);
        }
        if($endDate){
            $userPaymentQuery->whereDate('user_payments.created_at', '<=', $endDate);
        }
        $noOfSubscriptions = $userPaymentQuery->get(); 
         
        $subscriptionList = [];
        foreach($noOfSubscriptions as $noOfSubscription){
            $subscriptionList[] = $noOfSubscription->user_id;
        }

        // Registered Users
        $avvattaActiveUsersQuery = AvvattaUser::whereNotNull('id');
        if($startDate){
            $avvattaActiveUsersQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $avvattaActiveUsersQuery->whereDate('created_at', '<=', $endDate);
        }
        $avvattaUsers = $avvattaActiveUsersQuery->orderBy('created_at','desc')->get();

        $avvattaUserList = [];
        foreach($avvattaUsers as $avvattaUser){
            $avvattaUserList[] = $avvattaUser->id;
        }
        
        // Registered & Subscribed Users
        $subscribedUsersQuery = AvvattaUser::whereIn('id',$subscriptionList);
        if($startDate){
            $subscribedUsersQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $subscribedUsersQuery->whereDate('created_at', '<=', $endDate);
        }
        $subscribedUsers = $subscribedUsersQuery->orderBy('created_at','desc')->get();

        // Registered & Not Subscribed Users
        $avvattaNSUsersQuery = AvvattaUser::whereNotIn('id',$subscriptionList);
        if($startDate){
            $avvattaNSUsersQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $avvattaNSUsersQuery->whereDate('created_at', '<=', $endDate);
        }
        $avvattaNSUsers = $avvattaNSUsersQuery->get(); 

        $avvattaNSUsersQuery = AvvattaUser::whereNotIn('id',$subscriptionList);
        if($startDate){
            $avvattaNSUsersQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $avvattaNSUsersQuery->whereDate('created_at', '<=', $endDate);
        }
        $avvattaNSUsers = $avvattaNSUsersQuery->get(); 
        
        // Registered & Not Subscribed Users
        $cancelledSevenQuery = UserPayment::where('is_cancelled','=',1)->whereDate('created_at', '>=', $sevenDays);
        if($startDate){
            $cancelledSevenQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $cancelledSevenQuery->whereDate('created_at', '<=', $endDate);
        }
        $cancelledSeven = $cancelledSevenQuery->get();

        $cancelledFourteenQuery = UserPayment::where('is_cancelled','=',1)->whereDate('created_at', '>=', $fourteenDays);
        if($startDate){
            $cancelledFourteenQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $cancelledFourteenQuery->whereDate('created_at', '<=', $endDate);
        }
        $cancelledFourteen = $cancelledFourteenQuery->get();
        
        $cancelledQuery = UserPayment::where('is_cancelled','=',1)
        ->with('user_payments_avvatta_users')
        ->with('user_payments_subscriptions');
        switch ($this->country) {
            
           case 'SA':
               $cancelledQuery->where('user_payments.user_country','=', 0);
               break;
           case 'GH':
               $cancelledQuery->where('user_payments.user_country','=', 1);
               break;
           case 'NG':
               $cancelledQuery->where('user_payments.user_country','=', 2);
               break;
           default:
               break;
        }
        if($startDate){
            $cancelledQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $cancelledQuery->whereDate('created_at', '<=', $endDate);
        }
        $cancelled = $cancelledQuery->get();

        $newSubscriptionQuery = UserPayment::whereNotNull('id')
        ->with('user_payments_avvatta_users')
        ->with('user_payments_subscriptions');
        switch ($this->country) {
            
           case 'SA':
               $newSubscriptionQuery->where('user_payments.user_country','=', 0);
               break;
           case 'GH':
               $newSubscriptionQuery->where('user_payments.user_country','=', 1);
               break;
           case 'NG':
               $newSubscriptionQuery->where('user_payments.user_country','=', 2);
               break;
           default:
               break;
        }
        if(empty($startDate) && empty($endDate)  || empty($startDate) && !empty($endDate)){
            $newSubscriptionQuery->whereIn('id', function($query) use ($request,$startDate,$endDate){
                $query->select(DB::raw("MIN(id)"))
                ->from(with(new UserPayment)->getTable());
                if($endDate){
                    $newSubscriptionQuery->whereDate('created_at', '<=', $endDate);
                }
                $query->groupBy('user_id');
            });
        }
        if(!empty($startDate) || !empty($endDate)){
            $newSubscriptionQuery->whereNotIn('user_id', function($query) use ($request,$startDate,$endDate){
                $query->select(DB::raw("user_id"))
                ->from(with(new UserPayment)->getTable());
                if($startDate){
                    $query->whereDate('created_at', '<', $startDate);
                }
                $query->groupBy('user_id');
            });
            if($startDate){
                $newSubscriptionQuery->whereDate('created_at', '>=', $startDate);
            }
            if($endDate){
                $newSubscriptionQuery->whereDate('created_at', '<=', $endDate);
            }
            $newSubscriptionQuery->groupBy('user_id');
        }
        $newSubscriptions = $newSubscriptionQuery->get();
        
        return view('subscription-customer')
        ->with([
            'subscribedUsers'=>$subscribedUsers,
            'avvattaUsers'=>$avvattaUsers,
            'noOfSubscriptions'=>$noOfSubscriptions,
            'avvattaNSUsers'=>$avvattaNSUsers,
            'cancelledSeven'=>$cancelledSeven,
            'cancelledFourteen'=>$cancelledFourteen,
            'cancelled'=>$cancelled,
            'newSubscriptions'=>$newSubscriptions,
        ]);

    }

    public function dailyTransactions(Request $request)
    {
        $paginateSize = 20;$export = 0;$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        }

        // Subscription Data
        $tranQuery = UserPayment::with('user_payments_subscriptions','user_payments_avvatta_users');
        
        switch ($this->country) {
            
           case 'SA':
               $tranQuery->where('user_payments.user_country','=', 0);
               break;
           case 'GH':
               $tranQuery->where('user_payments.user_country','=', 1);
               break;
           case 'NG':
               $tranQuery->where('user_payments.user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($startDate){
            $tranQuery->whereDate('created_at', '>=', $startDate);
        }
        if($endDate){
            $tranQuery->whereDate('created_at', '<=', $endDate);
        }
        $tranQuery->orderBy('created_at','desc');
        
        if($export){
            $transactions = $tranQuery->get()->toArray();
            return Excel::download(new DailyTransactionExport($transactions), 'transactions-export.xlsx');
        }

        if(!$export){
            $transactions =  $tranQuery->paginate($paginateSize);
            return view('subscription-daily-transactions', [
                'transactions' => $transactions
            ]);
        }
    }
}

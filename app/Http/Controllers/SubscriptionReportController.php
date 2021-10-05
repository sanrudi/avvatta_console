<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Subscription;
use App\Models\UserPayment;
use App\Models\AvvattaUser;

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
        return view('subscription-total')
        ->with([
            'subscriptions'=>$subscriptions
        ]);

    }

    
    public function subscriptionCustomer(Request $request)
    {
        $userPaymentQuery = UserPayment::select('user_payments.user_id');
        $userPaymentQuery->join('users', 'users.id', '=', 'user_payments.user_id');
        $userPaymentQuery->groupBy('user_payments.user_id');
        $userPayment = $userPaymentQuery->get()->toArray();
        $avvattaUsersQuery = AvvattaUser::whereNotIn('id',$userPayment);
        $avvattaNSUsersCount = $avvattaUsersQuery->count();

        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        $sevenDays = date('Y-m-d H:i:s', strtotime($today.'-7 day'));
        $fourteenDays = date('Y-m-d H:i:s', strtotime($today.'-14 day'));
        
        $avvattaActiveUsers = AvvattaUser::count();
        $cancelledSeven = UserPayment::where('is_cancelled','=',1)->where('created_at', '>=', $sevenDays)->count();
        $cancelledFourteen = UserPayment::where('is_cancelled','=',1)->where('created_at', '>=', $fourteenDays)->count();
        $cancelled = UserPayment::where('is_cancelled','=',1)->count();
        return view('subscription-customer')
        ->with([
            'avvattaActiveUsers'=>$avvattaActiveUsers,
            'subscribedUsers'=>count($userPayment),
            'avvattaNSUsersCount'=>$avvattaNSUsersCount,
            'cancelledSeven'=>$cancelledSeven,
            'cancelledFourteen'=>$cancelledFourteen,
            'cancelled'=>$cancelled,
        ]);

    }
}

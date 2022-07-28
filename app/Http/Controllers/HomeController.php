<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\UserPayment;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $fromdate = date('Y-m-d', strtotime('-10 day'));
        $todate = date('Y-m-d', strtotime('+1 day'));
        $period = CarbonPeriod::create($fromdate, $todate);
        $chartDate = !empty($request['chartDate']) ? $request['chartDate'] : date('Y-m-d');
        $dayWiseSubscriptions = [];
        $subscriptionData = [];
        if(Auth::user()->is_cp){
            return view('dashboard')
            ->with([
                'subscriptions'=>$subscriptionData,
                'period'=>$period,
                'dayWiseSubscriptions'=>$dayWiseSubscriptions,
                'chartDate'=>$chartDate,
            ]);
        }
        $subscriptionsQuery = Subscription::select('subscriptions.*');
        $subscriptions = $subscriptionsQuery->get();
        $i = 1;
        $uc = 0;
        switch ($this->country) {

               case 'SA':
                   $uc = 0;
                   break;
               case 'GH':
                   $uc = 1;
                   break;
               case 'NG':
                    $uc = 2;
                   break;
               default:
                   break;
            } 
        foreach ($subscriptions as $subscription)
        {
            $userPaymentData = [];
            foreach ($period as $date) {
                
                  
                $userPayment = UserPayment::where('subscription_id','=',$subscription->id)
                ->with('user_payments_subscriptions','user_payments_avvatta_users')
                ->join('users', 'users.id', '=','user_payments.user_id')
                ->where('user_payments.user_country','=', $uc)
                ->where('user_payments.status', '=', 1)
                ->whereDate('user_payments.created_at',$date->format('Y-m-d'))
                ->count();
                $userPaymentData[] = $userPayment;
            }
           
            $subscriptionData[$i]['title'] = $subscription->title;
            $subscriptionData[$i]['subscription_count'] = implode(",",$userPaymentData);

            // Start - Day wise subscription count
            $userPayment = UserPayment::where('subscription_id','=',$subscription->id)
                ->with('user_payments_subscriptions','user_payments_avvatta_users')
                ->join('users', 'users.id', '=','user_payments.user_id')
                ->where('user_payments.user_country','=', $uc)
                ->where('user_payments.status', '=', 1)
                ->whereDate('user_payments.created_at', $chartDate)
                ->count();
            $dayWiseSubscriptions[$i-1] = $userPayment;
            $dayWiseTitles[$i-1] = $subscription->title;
            // End - Day wise subscription count

            $i++;
        }

        if($request->ajax()){
            return response()->json([
                'subscriptions'=>$subscriptionData,
                'period'=>$period,
                'dayWiseSubscriptions'=>$dayWiseSubscriptions,
                'dayWiseTitles'=>$dayWiseTitles,
                'chartDate'=>$chartDate,
            ]);
        }

        return view('dashboard')
        ->with([
            'subscriptions'=>$subscriptionData,
            'period'=>$period,
            'dayWiseSubscriptions'=>$dayWiseSubscriptions,
            'dayWiseTitles'=>$dayWiseTitles,
            'chartDate'=>$chartDate,
        ]);
    }
}

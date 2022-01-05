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
    public function index()
    {
        $fromdate = date('Y-m-d', strtotime('-10 day'));
        $todate = date('Y-m-d', strtotime('+1 day'));
        $period = CarbonPeriod::create($fromdate, $todate);
        $subscriptionData = [];
        if(Auth::user()->is_cp){
            return view('dashboard')
            ->with([
                'subscriptions'=>$subscriptionData,'period'=>$period
            ]);
        }
        $subscriptionsQuery = Subscription::select('subscriptions.*');
        $subscriptions = $subscriptionsQuery->get();
        $i = 1;
        foreach ($subscriptions as $subscription)
        {
            $userPaymentData = [];
            foreach ($period as $date) {
                $userPayment = UserPayment::where('subscription_id','=',$subscription->id);
                 switch ($this->country) {

               case 'SA':
                   $userPayment->where('user_payments.user_country','=', 0);
                   break;
               case 'GH':
                   $userPayment->where('user_payments.user_country','=', 1);
                   break;
               case 'NG':
                   $userPayment->where('user_payments.user_country','=', 2);
                   break;
               default:
                   break;
            }       
                        
                $userPayment->whereDate('created_at',$date->format('Y-m-d'))
                ->count();
                $userPaymentData[] = $userPayment;
            }

            $subscriptionData[$i]['title'] = $subscription->title;
            $subscriptionData[$i]['subscription_count'] = implode(",",$userPaymentData);
            $i++;
        }
        return view('dashboard')
        ->with([
            'subscriptions'=>$subscriptionData,'period'=>$period
        ]);
    }
}

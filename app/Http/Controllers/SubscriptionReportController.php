<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Subscription;
use App\Models\UserPayment;
use App\Models\AvvattaUser;
use App\Models\UserLog;
use App\Exports\Subscriptions\DailyTransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class SubscriptionReportController extends Controller
{
    private $country;
    public function __construct(Request $request)
    {
        $tzFromZone = config('global.TIMEZONEFROM');
        date_default_timezone_set($tzFromZone);
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
               
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
            $startDate = $this->convertTimeToZone($startDate);
            $endDate = $this->convertTimeToZone(date('Y-m-d H:i:s'));
        }
        // \DB::connection('mysql2')->enableQueryLog();
        $subscriptionsQuery = Subscription::join('user_payments', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                            ->selectRaw("subscriptions.title,SUM(CASE WHEN user_payments.is_renewal=0 THEN 1 ELSE 0 END) as count,SUM(CASE WHEN user_payments.is_renewal=1 THEN 1 ELSE 0 END) as count_renew");
        if(!empty($startDate) && !empty($endDate)){
            $subscriptionsQuery = $subscriptionsQuery->whereBetween(DB::raw('DATE(user_payments.created_at)'),[$startDate, $endDate]);
        }
        $subscriptions = $subscriptionsQuery->groupBY('user_payments.subscription_id')
                                            ->orderBY('subscriptions.title','ASC')->get();
        // $queries = \DB::connection('mysql2')->getQueryLog();
        // dd($queries);
             
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
        
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
            $startDate = $this->convertTimeToZone($startDate);
            $endDate = $this->convertTimeToZone(date('Y-m-d H:i:s'));
        } 
             
        $sevenDays = date('Y-m-d H:i:s', strtotime($today.'-7 day'));
        $fourteenDays = date('Y-m-d H:i:s', strtotime($today.'-14 day'));
        $thirtyDays = date('Y-m-d H:i:s', strtotime($today.'-30 day'));
        $request->session()->put('subscriptionCustomerReport.startDate',$startDate);
        $request->session()->put('subscriptionCustomerReport.endDate',$endDate);
        $noOfSubscriptionsCount = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                            ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                            //->whereDate('user_payments.expiry_date','>=', $today)
                                            ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate])
                                            ->count();

        $registerCustomerCount = AvvattaUser::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                                             ->where('status',1)->count(); 

        $registerSubscriptionCustomerCount = AvvattaUser::join('user_payments', 'users.id', '=', 'user_payments.user_id')
                                                        ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                                        //->whereDate('user_payments.expiry_date','>=', $today)
                                                        ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                                        ->where('users.status',1)->groupBy('users.id')->get();

        $registerNotSubscriptionCustomerCount = AvvattaUser::leftjoin('user_payments', 'users.id', '=', 'user_payments.user_id')
                                                        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                                        ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                                        //->whereDate('user_payments.expiry_date','>=', $today)
                                                        ->whereNull('user_payments.user_id')
                                                        ->where('users.status',1)->groupBy('users.id')->get();  

        $cancelledSevenDaysCount = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                                ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                                //->whereDate('user_payments.expiry_date','>=', $today)
                                                ->where('user_payments.is_cancelled','=',1)
                                                ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$sevenDays, $endDate])
                                                ->count();
        $cancelledfourteenDaysCount = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                                    ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                                    //->whereDate('user_payments.expiry_date','>=', $today)
                                                    ->where('user_payments.is_cancelled','=',1)
                                                    ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$fourteenDays, $endDate])
                                                    ->count();
        $cancelledthirtyDaysCount = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                                ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                                //->whereDate('user_payments.expiry_date','>=', $today)
                                                ->where('user_payments.is_cancelled','=',1)
                                                ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$thirtyDays, $endDate])
                                                ->count();
        // \DB::connection('mysql2')->enableQueryLog();
        // UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
        //                             ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
        //                             //->whereDate('user_payments.expiry_date','>=', $today)
        //                             ->where('user_payments.is_cancelled','=',1)
        //                             ->whereBetween('user_payments.created_at', [$startDate, $endDate])
        //                             ->count();
        // $queries = \DB::connection('mysql2')->getQueryLog();
        // dd($queries);
        
        
        return view('subscription-customer')
        ->with([
            'noOfSubscriptionsCount'=>$noOfSubscriptionsCount,
            'registerCustomerCount'=>$registerCustomerCount,
            'registerSubscriptionCustomerCount'=>count($registerSubscriptionCustomerCount),
            'registerNotSubscriptionCustomerCount'=>count($registerNotSubscriptionCustomerCount),
            'cancelledSevenDaysCount'=>$cancelledSevenDaysCount,
            'cancelledfourteenDaysCount'=>$cancelledfourteenDaysCount,
            'cancelledthirtyDaysCount'=>$cancelledthirtyDaysCount
        ]);

    }

    
    /* Process ajax request for Get Registered Customer*/
    public function getRegisteredCustomer(Request $request)
    {
        $startDate = $request->session()->get('subscriptionCustomerReport.startDate');
        $endDate   = $request->session()->get('subscriptionCustomerReport.endDate');
                
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        
        // Total records
        $totalRecords = AvvattaUser::select('count(*) as allcount')
                                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                                    ->where('status',1)->count();
                                
        $totalRecordswithFilter = AvvattaUser::select('count(*) as allcount')->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                                                ->where('status',1);
        if($searchValue){
            $totalRecordswithFilter = $totalRecordswithFilter->where('firstname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('lastname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('email', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('mobile', 'like', '%' . $searchValue . '%');
        }                                        
                                                
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Get records, also we have included search filter as well
        $records = AvvattaUser::orderBy($columnName, $columnSortOrder)
                            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                            ->where('status',1);
        if($searchValue){
            $records = $records->where('firstname', 'like', '%' . $searchValue . '%')
                                ->orWhere('lastname', 'like', '%' . $searchValue . '%')
                                ->orWhere('email', 'like', '%' . $searchValue . '%')
                                ->orWhere('mobile', 'like', '%' . $searchValue . '%');
        }
                            
        $records = $records->select('firstname','lastname','email','mobile','created_at')
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "name" => $record->firstname.' '.$record->lastname,
                "email" => $record->email,
                "mobile" => $record->mobile,
                "created_at" => date("Y-m-d H:i:s", strtotime($record->created_at)),
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }
    
    /* Process ajax request for Get Registered Subscribed Customer*/
    public function getRegisteredSubscribedCustomer(Request $request)
    {
        $startDate = $request->session()->get('subscriptionCustomerReport.startDate');
        $endDate   = $request->session()->get('subscriptionCustomerReport.endDate');
                
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        
        // Total records
        $totalRecords = AvvattaUser::select(DB::raw('count(*) as total'))
                                    ->join('user_payments', 'users.id', '=', 'user_payments.user_id')
                                    ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                    //->whereDate('user_payments.expiry_date','>=', $today)
                                    ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                    ->where('users.status',1)
                                    ->groupBy('users.id')
                                    ->get();
                               
        $totalRecordswithFilter = AvvattaUser::select(DB::raw('count(*) as total'))
                                                ->join('user_payments', 'users.id', '=', 'user_payments.user_id')
                                                ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                                //->whereDate('user_payments.expiry_date','>=', $today)
                                                ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                                ->where('users.status',1);
        if($searchValue){
            $totalRecordswithFilter = $totalRecordswithFilter->where('users.firstname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                                        
                                                
        $totalRecordswithFilter = $totalRecordswithFilter->groupBy('users.id')
                                                         ->get();

        // Get records, also we have included search filter as well
        $records = AvvattaUser::orderBy($columnName, $columnSortOrder)
                                ->join('user_payments', 'users.id', '=', 'user_payments.user_id')
                                ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                //->whereDate('user_payments.expiry_date','>=', $today)
                                ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                ->where('users.status',1);
        if($searchValue){
            $records = $records->where('users.firstname', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                        
                                
        $records = $records->select('users.firstname','users.lastname','users.email','users.mobile','users.created_at')
                            ->skip($start)
                            ->take($rowperpage)
                            ->groupBy('users.id')
                            ->get();

                            
        $data_arr = array();

        foreach ($records as $record) {
            $data_arr[] = array(
                "firstname" => $record->firstname.' '.$record->lastname,
                "email" => $record->email,
                "mobile" => $record->mobile,
                "created_at" => date("Y-m-d H:i:s", strtotime($record->created_at)),
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => count($totalRecords),
            "iTotalDisplayRecords" => count($totalRecordswithFilter),
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }

    /* Process ajax request for Get Registered Not Subscribed Customer*/
    public function getRegisteredNotSubscribedCustomer(Request $request)
    {
        $startDate = $request->session()->get('subscriptionCustomerReport.startDate');
        $endDate   = $request->session()->get('subscriptionCustomerReport.endDate');
                
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        
        // Total records
        $totalRecords = AvvattaUser::select(DB::raw('count(*) as total'))
                                    ->leftjoin('user_payments', 'users.id', '=', 'user_payments.user_id')
                                    ->leftjoin('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                    //->whereDate('user_payments.expiry_date','>=', $today)
                                    ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                    ->whereNull('user_payments.user_id')
                                    ->where('users.status',1)
                                    ->groupBy('users.id')
                                    ->get();
                               
        $totalRecordswithFilter = AvvattaUser::select(DB::raw('count(*) as total'))
                                                ->leftjoin('user_payments', 'users.id', '=', 'user_payments.user_id')
                                                ->leftjoin('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                                //->whereDate('user_payments.expiry_date','>=', $today)
                                                ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                                ->whereNull('user_payments.user_id')
                                                ->where('users.status',1);
        if($searchValue){
            $totalRecordswithFilter = $totalRecordswithFilter->where('users.firstname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                                        
                                                
        $totalRecordswithFilter = $totalRecordswithFilter->groupBy('users.id')
                                                         ->get();

        // Get records, also we have included search filter as well
        $records = AvvattaUser::orderBy($columnName, $columnSortOrder)
                                ->leftjoin('user_payments', 'users.id', '=', 'user_payments.user_id')
                                ->leftjoin('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                //->whereDate('user_payments.expiry_date','>=', $today)
                                ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                ->whereNull('user_payments.user_id')
                                ->where('users.status',1);
        if($searchValue){
            $records = $records->where('users.firstname', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                        
                                
        $records = $records->select('users.firstname','users.lastname','users.email','users.mobile','users.created_at')
                            ->skip($start)
                            ->take($rowperpage)
                            ->groupBy('users.id')
                            ->get();

                            
        $data_arr = array();

        foreach ($records as $record) {
            $data_arr[] = array(
                "firstname" => $record->firstname.' '.$record->lastname,
                "email" => $record->email,
                "mobile" => $record->mobile,
                "created_at" => date("Y-m-d H:i:s", strtotime($record->created_at)),
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => count($totalRecords),
            "iTotalDisplayRecords" => count($totalRecordswithFilter),
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }

    /* Process ajax request for Get Subscriber List*/
    public function getSubscriberList(Request $request)
    {
        $startDate = $request->session()->get('subscriptionCustomerReport.startDate');
        $endDate   = $request->session()->get('subscriptionCustomerReport.endDate');
                
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        
        // Total records
        $totalRecords = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                    ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                    //->whereDate('user_payments.expiry_date','>=', $today)
                                    ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate])
                                    ->count();
                               
        $totalRecordswithFilter = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                            ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                            //->whereDate('user_payments.expiry_date','>=', $today)
                                            ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate]);
        if($searchValue){
            $totalRecordswithFilter = $totalRecordswithFilter->where('users.firstname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('subscriptions.title', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                                        
                                                
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Get records, also we have included search filter as well
        $records = UserPayment::orderBy($columnName, $columnSortOrder)
                                ->join('users', 'users.id', '=', 'user_payments.user_id')
                                ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                //->whereDate('user_payments.expiry_date','>=', $today)
                                ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate]);
        if($searchValue){
            $records = $records->where('users.firstname', 'like', '%' . $searchValue . '%')
                                ->orWhere('subscriptions.title', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                        
                                
        $records = $records->select('subscriptions.title','users.firstname','users.lastname','users.email','users.mobile','user_payments.created_at')
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();

                            
        $data_arr = array();

        foreach ($records as $record) {
            $data_arr[] = array(
                "title" => $record->title,
                "name" => $record->firstname.' '.$record->lastname.' '.$record->email.' '.$record->mobile,
                "created_at" => date("Y-m-d H:i:s", strtotime($record->created_at)),
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }
    
    /* Process ajax request for Get Subscriber Cancelled List*/
    public function getSubscriberCancelledList(Request $request)
    {
        $startDate = $request->session()->get('subscriptionCustomerReport.startDate');
        $endDate   = $request->session()->get('subscriptionCustomerReport.endDate');
                
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        
        // Total records
        $totalRecords = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                    ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                    //->whereDate('user_payments.expiry_date','>=', $today)
                                    ->where('user_payments.is_cancelled','=',1)
                                    ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate])
                                    ->count();
                               
        $totalRecordswithFilter = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                            ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                            //->whereDate('user_payments.expiry_date','>=', $today)
                                            ->where('user_payments.is_cancelled','=',1)
                                            ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate]);
        if($searchValue){
            $totalRecordswithFilter = $totalRecordswithFilter->where('users.firstname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('user_payments.cancel_reason', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('subscriptions.title', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                                        
                                                
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Get records, also we have included search filter as well
        $records = UserPayment::orderBy($columnName, $columnSortOrder)
                                ->join('users', 'users.id', '=', 'user_payments.user_id')
                                ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                //->whereDate('user_payments.expiry_date','>=', $today)
                                ->where('user_payments.is_cancelled','=',1)
                                ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate]);
        if($searchValue){
            $records = $records->where('users.firstname', 'like', '%' . $searchValue . '%')
                                ->orWhere('subscriptions.title', 'like', '%' . $searchValue . '%')
                                ->orWhere('user_payments.cancel_reason', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                        
                                
        $records = $records->select('subscriptions.title','users.firstname','users.lastname','users.email','users.mobile','user_payments.created_at','user_payments.cancel_reason')
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();

                            
        $data_arr = array();

        foreach ($records as $record) {
            $data_arr[] = array(
                "title" => $record->title,
                "name" => $record->firstname.' '.$record->lastname.' '.$record->email.' '.$record->mobile,
                "days" =>($record->created_at)?$record->created_at->diffForHumans():'',
                "created_at" => date("Y-m-d H:i:s", strtotime($record->created_at)),
                "reason" =>($record->cancel_reason)?$record->cancel_reason:'',
                
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }
    
    
    /* Process ajax request for Get New Subscriber List*/
    public function getNewSubscriberList(Request $request)
    {
        $startDate = $request->session()->get('subscriptionCustomerReport.startDate');
        $endDate   = $request->session()->get('subscriptionCustomerReport.endDate');
                
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        
        // Total records
        $totalRecords = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                    ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                    //->whereDate('user_payments.expiry_date','>=', $today)
                                    ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                    ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate])
                                    ->count();
                               
        $totalRecordswithFilter = UserPayment::join('users', 'users.id', '=', 'user_payments.user_id')
                                            ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                            //->whereDate('user_payments.expiry_date','>=', $today)
                                            ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                            ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate]);
        if($searchValue){
            $totalRecordswithFilter = $totalRecordswithFilter->where('users.firstname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('subscriptions.title', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                                            ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                                        
                                                
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Get records, also we have included search filter as well
        $records = UserPayment::orderBy($columnName, $columnSortOrder)
                                ->join('users', 'users.id', '=', 'user_payments.user_id')
                                ->join('subscriptions', 'subscriptions.id', '=', 'user_payments.subscription_id')
                                //->whereDate('user_payments.expiry_date','>=', $today)
                                ->whereBetween(DB::raw('DATE(users.created_at)'), [$startDate, $endDate])
                                ->whereBetween(DB::raw('DATE(user_payments.created_at)'), [$startDate, $endDate]);
        if($searchValue){
            $records = $records->where('users.firstname', 'like', '%' . $searchValue . '%')
                                ->orWhere('subscriptions.title', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.lastname', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                                ->orWhere('users.mobile', 'like', '%' . $searchValue . '%');
        }                        
                                
        $records = $records->select('subscriptions.title','users.firstname','users.lastname','users.email','users.mobile','user_payments.created_at')
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();

                            
        $data_arr = array();

        foreach ($records as $record) {
            $data_arr[] = array(
                "name" => $record->firstname.' '.$record->lastname.' '.$record->email.' '.$record->mobile,
                "title" => $record->title,
                "created_at" => date("Y-m-d H:i:s", strtotime($record->created_at)),
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }
    public function dailyTransactions(Request $request)
    {
        $paginateSize = 20;$export = 0;$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $search = ($request->input('search'))?$request->input('search'):"";
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$this->convertTimeToZone($request->input('endDate')):"";
        
        $today = date("Y-m-d H:m:s");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
            $startDate = $this->convertTimeToZone($startDate);
        }

        // Subscription Data
        $tranQuery = UserPayment::select('user_payments.*')->with('user_payments_subscriptions','user_payments_avvatta_users');
        $tranQuery->join('users', 'users.id', '=','user_payments.user_id');
        
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

        if($search){
            $tranQuery->where('users.firstname', 'like', "%$search%");
            $tranQuery->orWhere('users.lastname', 'like', "%$search%");
            $tranQuery->orWhere('users.email', 'like', "%$search%");
            $tranQuery->orWhere('users.mobile', 'like', "%$search%");
        }
        if($startDate){
            $tranQuery->whereDate('user_payments.created_at', '>=', $startDate);
        }
        if($endDate){
            $tranQuery->whereDate('user_payments.created_at', '<=', $endDate);
        }
        $tranQuery->where('user_payments.status', '=', 1);
        $tranQuery->orderBy('user_payments.created_at','desc');
        switch ($request->input('type'))
        {
            case "New":
                $tranQuery->where('user_payments.is_renewal', '=', 0);
                break;
            case "Renewal":
                $tranQuery->where('user_payments.is_renewal', '=', 1);
                break;
            default:
               break;
        }
        
        switch ($request->input('packagetype'))
        {
            case "game":
                $userReport = UserLog::where('user_logs.category', '=', 'game');
                break;
            case "erosnow":
                $userReport = UserLog::where('user_logs.category', '=', 'erosnow');
                break;
            case "kids":
                $userReport = UserLog::where('user_logs.category', '=', 'kids');
                break;
            default:
                $userReport = UserLog::select('users.firstname','users.lastname','users.email','users.mobile','user_logs.user_id', 'user_logs.loggable_id', 'user_logs.content_id', 'user_logs.type', 'user_logs.category', 'user_logs.action', 'user_logs.date_time','user_logs.device','user_logs.os','user_logs.age');
        }
        
        
        
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

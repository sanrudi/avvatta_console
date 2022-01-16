<?php

namespace App\Http\Controllers;

use App\Exports\User\UserArticleExport;
use App\Exports\Subscriptions\IdleSubscriptionExport;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Models\UserLog;
use App\Models\VideoContent;
use App\Models\AvvattaUser;
use App\Models\SubProfile;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class UserReportController extends Controller
{

    private $country;
    public function __construct(Request $request)
    {
        // $this->middleware('auth');
        // check the domain and set country
        $this->country = env('COUNTRY','SA');
        $server_host = $request->server()['SERVER_NAME'];
                $referer =  request()->headers->get('referer');
                if($referer=='https://gh.avvatta.com/') {
                 
                    $this->country = 'GH';
                    
                }
              
                if($referer=='https://ng.avvatta.com/') {
                 
                    $this->country = 'NG';
                    
                }
        
        $this->country = env('COUNTRY','SA');
    }
    
    
    public function userReport(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d', strtotime($today.'-'.$reportFrom.' day'));
        }

        $device = "";$device = ($request->input('device'))?$request->input('device'):"";
        $os = "";$os = ($request->input('os'))?$request->input('os'):"";

        $user_contents = [];
        switch ($request->input('type'))
        {
            case "game":
                $userReport = UserLog::where('category', '=', 'game');
                break;
            case "erosnow":
                $userReport = UserLog::where('category', '=', 'erosnow');
                break;
            case "kids":
                $userReport = UserLog::where('category', '=', 'kids');
                break;
            default:
                $userReport = UserLog::select('user_id', 'loggable_id', 'content_id', 'type', 'category', 'action', 'date_time','device','os','age');
        }
        if($startDate && $request->input('reportFrom') != "custom"){
            $userReport->whereBetween('date_time', [$startDate, $today]);
        } elseif($request->input('reportFrom') == "custom") {
            $userReport->whereBetween('date_time', [$startDate, $endDate]);
        }
        
        switch ($this->country) {
            
           case 'SA':
               $userReport->where('user_country','=', 0);
               break;
           case 'GH':
               $userReport->where('user_country','=', 1);
               break;
           case 'NG':
               $userReport->where('user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($device){
            $userReport->where('device', '=', $device);
        }
        if($os){
            $userReport->where('os', '=', $os);
        }  
        $userReport->orderBy('date_time','desc');
        $i = 1;
        
        
        
        if(!$export) {
        echo $export;
        DB::enableQueryLog(); 
        $userPageData = $userReport->paginate($paginateSize);
        dd(DB::getQueryLog());
        }
        
        
        
        if($export){
            $userPageData = $userReport->get();
        }
        foreach ($userPageData as $user)
        {
            switch ($user->category) {
                case "kids":
                    $content_name = VideoContent::where('id', $user->loggable_id)
                        ->value('content_name');
                    break;
                case "game":
                    $content_name = GameContent::where('id', $user->loggable_id)->value('game_name');
                    break;
                case "erosnow":
                    $content_name = AvErosNows::where('content_id', $user->content_id)->value('title');
                    break;
                case "fun":
                    $content_name = VideoContent::where('id', $user->loggable_id)
                        ->value('content_name');
                    break;
                case "siy":
                    $content_name = VideoContent::where('id', $user->loggable_id)
                        ->value('content_name');
                    break;
                default:
                $content_name = "-";
            }
            $content_name = !empty($content_name)?$content_name:"-";
            $userData = AvvattaUser::find($user->user_id);
            $username ="";
            if(!empty($userData->firstname) && !empty($userData->lastname)){
                $username =$userData->firstname." ".$userData->lastname;
            }
            if(empty($username) && !empty($userData->email)){
                $username =$userData->email;
            }
            if(empty($username) && !empty($userData->mobile)){
                $username =$userData->mobile;
            }
            $username = !empty($username)?$username:"-";
            $user_contents[$i]['id'] = $user->id;
            $user_contents[$i]['user_name'] = $username;
            $user_contents[$i]['content_name'] = isset($content_name)?$content_name:"";
            $user_contents[$i]['type'] = $user->type;
            $user_contents[$i]['action'] = ($user->type != "user")?$user->action:$user->category;
            $user_contents[$i]['date_time'] = $user->date_time;
            $user_contents[$i]['device'] = $user->device;
            $user_contents[$i]['os'] = $user->os;
            $user_contents[$i]['age'] = ($user->age)?$user->age:"";
            $i++;
        }

        if($export){
            $userArticles = $user_contents;
            return Excel::download(new UserArticleExport($userArticles), 'user-article-export.xlsx');
        }

        return view('user-report')
            ->with([
                'user_contents'=>$user_contents,
                'userPageData' => $userPageData
            ]);
    }

    public function userRegistrationReport(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d', strtotime($today.'-'.$reportFrom.' day'));
        }

        // Registered User By Monthly
        $registerUsersMonthlyQuery = AvvattaUser::select(DB::raw('YEAR(created_at) year, MONTH(created_at) month'),DB::raw('count(Date(created_at)) as count'));
        $registerUsersMonthlyQuery->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'));
        $registerUsersMonthlyQuery->orderBy('created_at','desc');
        if($startDate){
            $registerUsersMonthlyQuery->whereDate('users.created_at', '>=', $startDate);
        }
        if($endDate){
            $registerUsersMonthlyQuery->whereDate('users.created_at', '<=', $endDate);
        } 
        $registerUsersMonthly = $registerUsersMonthlyQuery->get();
        
        // Registered User By Daily
        $registerUsersDailyQuery = AvvattaUser::select(DB::raw('Date(created_at) as date, count(Date(created_at)) as count'));
        $registerUsersDailyQuery->groupBy(DB::raw('Date(created_at)'));
        $registerUsersDailyQuery->orderBy('created_at','desc');
        if($startDate){
            $registerUsersDailyQuery->whereDate('users.created_at', '>=', $startDate);
        }
        if($endDate){
            $registerUsersDailyQuery->whereDate('users.created_at', '<=', $endDate);
        } 
        $registerUsersDaily = $registerUsersDailyQuery->get();

        // signed-up on web 
        $signedupQuery = AvvattaUser::select(DB::raw('Date(created_at) as date, count(Date(created_at)) as count'));
        $signedupQuery->where('device_type','=','web');
        $signedupQuery->groupBy(DB::raw('Date(created_at)'));
        $signedupQuery->orderBy('created_at','desc');
        if($startDate){
            $signedupQuery->whereDate('users.created_at', '>=', $startDate);
        }
        if($endDate){
            $signedupQuery->whereDate('users.created_at', '<=', $endDate);
        } 
        $signedUpOnWeb = $signedupQuery->get();
        
        return view('user-registration-report')
            ->with([
                'registerUsersMonthly'=>$registerUsersMonthly,
                'registerUsersDaily'=>$registerUsersDaily,
                'signedUpOnWeb'=>$signedUpOnWeb,
            ]);
    }

    public function userLoginReport(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d', strtotime($today.'-'.$reportFrom.' day'));
        }

        // Logged in User By Monthly
        $loggedInUsersMonthlyQuery = UserLog::where('type', '=', 'user');
        $loggedInUsersMonthlyQuery->where('category', '=', 'login');
        $loggedInUsersMonthlyQuery->groupBy(DB::raw('YEAR(date_time)'), DB::raw('MONTH(date_time)'));
        $loggedInUsersMonthlyQuery->select(DB::raw('YEAR(date_time) year, MONTH(date_time) month'),DB::raw('count(Date(date_time)) as count'));
        
        switch ($this->country) {
            
           case 'SA':
               $loggedInUsersMonthlyQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $loggedInUsersMonthlyQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $loggedInUsersMonthlyQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        
        
        if($startDate){
            $loggedInUsersMonthlyQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $loggedInUsersMonthlyQuery->whereDate('user_logs.date_time', '<=', $endDate);
        } 
        $loggedInUsersMonthly = $loggedInUsersMonthlyQuery->get();
        
        // Logged in User By Daily
        $loggedInUsersQuery = UserLog::where('type', '=', 'user');
        $loggedInUsersQuery->where('category', '=', 'login');
        $loggedInUsersQuery->groupBy(DB::raw('Date(date_time)'));
        $loggedInUsersQuery->select(DB::raw('Date(date_time) as date, count(Date(date_time)) as count'));
        
        switch ($this->country) {
            
           case 'SA':
               $loggedInUsersQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $loggedInUsersQuery->where('user_country','=', 1);
               break;
           case 'GH':
               $loggedInUsersQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($startDate){
            $loggedInUsersQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $loggedInUsersQuery->whereDate('user_logs.date_time', '<=', $endDate);
        } 
        $loggedInUsersDaily = $loggedInUsersQuery->get();
        
        return view('user-login-report')
            ->with([
                'loggedInUsersMonthly'=>$loggedInUsersMonthly,
                'loggedInUsersDaily'=>$loggedInUsersDaily,
            ]);
    }

    public function userSubProfileReport(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d', strtotime($today.'-'.$reportFrom.' day'));
        }

        $subProfileQuery = SubProfile::join('users', 'users.id', '=', 'sub_profiles.user_id');
        $subProfileQuery->groupBy('sub_profiles.user_id');
        $subProfileQuery->select('users.*',DB::raw('count(sub_profiles.user_id) as count'));
        if($startDate){
            $subProfileQuery->whereDate('sub_profiles.created_at', '>=', $startDate);
        }
        if($endDate){
            $subProfileQuery->whereDate('sub_profiles.created_at', '<=', $endDate);
        } 
        $subProfileQuery->orderBy('count','desc');
        $subProfile = $subProfileQuery->get();
        
        return view('user-sub-profile-report')
            ->with([
                'subProfile'=>$subProfile,
            ]);
    }

    public function idleSubscribers(Request $request)
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
        $tranQuery = UserPayment::with('user_payments_avvatta_users');
        $tranQuery->join('user_logs','user_logs.user_id','=','user_payments.user_id');
        if($startDate){
            $tranQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $tranQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }
        
        switch ($this->country) {
            
           case 'SA':
               $tranQuery->where('user_logs.user_country','=', 0);
               break;
           case 'GH':
               $tranQuery->where('user_logs.user_country','=', 1);
               break;
           case 'NG':
               $tranQuery->where('user_logs.user_country','=', 2);
               break;
           default:
               break;
        }
        
        $tranQuery->select(DB::raw("DATEDIFF(CURDATE(), user_logs.date_time) as idleDays,user_payments.user_id,user_logs.date_time"));
        $tranQuery->groupBy('user_payments.user_id');
        $tranQuery->orderBy('user_payments.created_at','desc');

        if($export){
            $transactions = $tranQuery->get()->toArray();
            return Excel::download(new IdleSubscriptionExport($transactions), 'transactions-export.xlsx');
        }

        if(!$export){
            $transactionsData =  $tranQuery->paginate($paginateSize);
            //dd($transactionsData);
            return view('user-idle-subscribers', [
                'transactions' => $transactionsData
            ]);
        }
    }
    
}

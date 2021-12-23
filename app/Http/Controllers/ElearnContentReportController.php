<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\UserLog;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Models\VideoContent;
use App\Exports\Videos\VideoArticleExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;


class ElearnContentReportController extends Controller
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
   
    public function elearnReport(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";$multiDate = "";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        $ageRange = ($request->input('ageRange'))?$request->input('ageRange'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        if($reportFrom == "multiple" && $request->input('multiDate')){
            $multiDate = explode(",",$request->input('multiDate'));
        }

        $device = "";$device = ($request->input('device'))?$request->input('device'):"";
        $os = "";$os = ($request->input('os'))?$request->input('os'):"";

        $logQuery = UserLog::with(['avvatta_user','loggable','loggable.video_category','loggable.video_sub_category']);
        $logQuery->select('user_logs.*',DB::raw("GROUP_CONCAT( DISTINCT category) as category_list"),DB::raw('count(DISTINCT category) as category_count'));
        $logQuery->where('type','=', 'video');
        $logQuery->where('action','=', 'play');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        switch ($this->country) {
            
           case 'SA':
               $logQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 1);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
           
        if($startDate){
            $logQuery->where('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->where('date_time', '<=', $endDate);
        }    
        if($device){
            $logQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $logQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $logQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }
        if($ageRange){
            $logQuery->where('user_logs.age', '>=', $ageRange);
            $logQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $logQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        $logQuery->groupBy('user_id');
        //$logQuery->having(DB::raw('count(category)'), '>', 1);
        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        if(!$multiDate){
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get();
        }
        if($multiDate){
            $logs = $logQuery->get();
        }
        
        return view('elearn-report')
        ->with([
            'logs'=>$logs
        ]);

    }

    public function mostWatchedElearnContent(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";$multiDate = "";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        $ageRange = ($request->input('ageRange'))?$request->input('ageRange'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        if($reportFrom == "multiple" && $request->input('multiDate')){
            $multiDate = explode(",",$request->input('multiDate'));
        }
        $device = "";$device = ($request->input('device'))?$request->input('device'):"";
        $os = "";$os = ($request->input('os'))?$request->input('os'):"";
        $provider = null;
        $providerRequest = $request->input('provider');
        if(Auth::user()->is_cp == 1){
            $providerRequest = Auth::user()->roles->first()->name;
        }
        $provider = ($providerRequest)?$providerRequest:$provider;

        $logQuery = UserLog::with(['avvatta_user','loggable','loggable.video_category','loggable.video_sub_category']);
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('action','=', 'play');
        switch ($this->country) {
            
           case 'SA':
               $logQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 1);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
          
        if(!is_null($provider)){
            $logQuery->join('video_content', 'user_logs.loggable_id','=', 'video_content.id');
            $logQuery->where('video_content.owner','=', $provider);
        }
        if($startDate){
            $logQuery->where('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->where('date_time', '<=', $endDate);
        }
        if($device){
            $logQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $logQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $logQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }
        if($ageRange){
            $logQuery->where('user_logs.age', '>=', $ageRange);
            $logQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $logQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        $logQuery->groupBy('user_logs.loggable_id');
        $logQuery->groupBy('user_logs.user_id');

        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        if(!$multiDate){
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get();
        }
        if($multiDate){
            $logs = $logQuery->get()->take(100);
        }
        
        return view('elearn-most-watched-report')
        ->with([
            'logs'=>$logs
        ]);
    }
    
    public function topTenElearnContent(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";$multiDate = "";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        $ageRange = ($request->input('ageRange'))?$request->input('ageRange'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        if($reportFrom == "multiple" && $request->input('multiDate')){
            $multiDate = explode(",",$request->input('multiDate'));
        }
        
        $device = "";$device = ($request->input('device'))?$request->input('device'):"";
        $os = "";$os = ($request->input('os'))?$request->input('os'):"";
        $provider = null;
        $providerRequest = $request->input('provider');
        if(Auth::user()->is_cp == 1){
            $providerRequest = Auth::user()->roles->first()->name;
        }
        $provider = ($providerRequest)?$providerRequest:$provider;

        $logQuery = UserLog::with(['avvatta_user','loggable','loggable.video_category','loggable.video_sub_category']);
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('type','=', 'video');
        $logQuery->where('action','=', 'play');
        switch ($this->country) {
            
           case 'SA':
               $logQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 1);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
          
        if(!is_null($provider)){
            $logQuery->join('video_content', 'user_logs.loggable_id','=', 'video_content.id');
            $logQuery->where('video_content.owner','=', $provider);
        }
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }
        if($device){
            $logQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $logQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $logQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }
        if($ageRange){
            $logQuery->where('user_logs.age', '>=', $ageRange);
            $logQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $logQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        $logQuery->groupBy('user_logs.user_id');
        $logQuery->groupBy('user_logs.loggable_id');
        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        if(!$multiDate){
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get();
        }
        if($multiDate){
            $logs = $logQuery->get()->take(10);
        }
        return view('elearn-top-ten-report')
        ->with([
            'logs'=>$logs
        ]);
    }

    public function topGenreWatched(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";$multiDate = "";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        $ageRange = ($request->input('ageRange'))?$request->input('ageRange'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        if($reportFrom == "multiple" && $request->input('multiDate')){
            $multiDate = explode(",",$request->input('multiDate'));
        }
        
        $device = "";$device = ($request->input('device'))?$request->input('device'):"";
        $os = "";$os = ($request->input('os'))?$request->input('os'):"";

        $logQuery = UserLog::select('user_logs.*',DB::raw('count(user_logs.genre) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('action','=', 'play');
        $logQuery->whereNotNull('genre');
        switch ($this->country) {
            
           case 'SA':
               $logQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 1);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
          
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }
        if($device){
            $logQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $logQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $logQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }
        if($ageRange){
            $logQuery->where('user_logs.age', '>=', $ageRange);
            $logQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $logQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        $logQuery->groupBy('user_logs.category');
        $logQuery->groupBy('user_logs.genre');
        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        if(!$multiDate){
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logQuery->orderBy(DB::raw('count(user_logs.genre)'),'desc');
            $logs = $logQuery->get();
        }
        if($multiDate){
            $logQuery->orderBy(DB::raw('count(user_logs.genre)'),'desc');
            $logs = $logQuery->get();
        }
        return view('elearn-top-genre-watched-report')
        ->with([
            'logs'=>$logs
        ]);
    }

    public function topRepeatedBySingleUser(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";$multiDate = "";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        $ageRange = ($request->input('ageRange'))?$request->input('ageRange'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        if($reportFrom == "multiple" && $request->input('multiDate')){
            $multiDate = explode(",",$request->input('multiDate'));
        }
        
        $device = "";$device = ($request->input('device'))?$request->input('device'):"";
        $os = "";$os = ($request->input('os'))?$request->input('os'):"";
        $provider = null;
        $providerRequest = $request->input('provider');
        if(Auth::user()->is_cp == 1){
            $providerRequest = Auth::user()->roles->first()->name;
        }
        $provider = ($providerRequest)?$providerRequest:$provider;

        $logQuery = UserLog::with(['avvatta_user','loggable','loggable.video_category','loggable.video_sub_category']);
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('action','=', 'play');
        switch ($this->country) {
            
           case 'SA':
               $logQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 1);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
          
        if(!is_null($provider)){
            $logQuery->join('video_content', 'user_logs.loggable_id','=', 'video_content.id');
            $logQuery->where('video_content.owner','=', $provider);
        }
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }
        if($device){
            $logQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $logQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $logQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }
        if($ageRange){
            $logQuery->where('user_logs.age', '>=', $ageRange);
            $logQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $logQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        $logQuery->groupBy('user_logs.user_id');
        $logQuery->groupBy('user_logs.loggable_id');
        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        if(!$multiDate){
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get();
        }
        if($multiDate){
            $logs = $logQuery->get()->take(10);
        }
        return view('elearn-top-repeat-user-report')
        ->with([
            'logs'=>$logs
        ]);
    }

    public function allCategoryUsers(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";$multiDate = "";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        $ageRange = ($request->input('ageRange'))?$request->input('ageRange'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        if($reportFrom == "multiple" && $request->input('multiDate')){
            $multiDate = explode(",",$request->input('multiDate'));
        }
        
        $device = "";$device = ($request->input('device'))?$request->input('device'):"";
        $os = "";$os = ($request->input('os'))?$request->input('os'):"";

        $logQuery = UserLog::with(['avvatta_user','loggable','loggable.video_category','loggable.video_sub_category']);
        $logQuery->select('user_logs.*');
        $logQuery->where('type','=', 'video');
        $logQuery->where('action','=', 'play');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        switch ($this->country) {
            
           case 'SA':
               $logQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 1);
               break;
           case 'GH':
               $logQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
          
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }    
        if($device){
            $logQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $logQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $logQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }
        if($ageRange){
            $logQuery->where('user_logs.age', '>=', $ageRange);
            $logQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $logQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        $logQuery->groupBy('user_id');
        $logQuery->having(DB::raw('count(category)'), '>', 1);
        $logs = $logQuery->get();
        return view('video-all-category-user-report')
        ->with([
            'logs'=>$logs
        ]);
    }
    
}

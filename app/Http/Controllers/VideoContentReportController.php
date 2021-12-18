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


class VideoContentReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $userLogs = UserLog::all();
    }

    public function videoArticles(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";
        $export = ($request->input('export'))?1:0;
        $reportFrom="";$startDate="";$endDate="";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 

        if($request->input('type') && ($request->input('type') == "erosnow" || $request->input('type') == "kids" )){
            $report = $request->input('type');
        }
        $provider = null;
        $providerRequest = $request->input('provider');
        if(Auth::user()->is_cp == 1){
            $providerRequest = Auth::user()->roles->first()->name;
        }
        $provider = ($providerRequest)?$providerRequest:$provider;
        if($provider == "erosnow")
        {
            $report = "erosnow";
        }else{
            $report = "kids";
        }
        // Erosnow Data
        if($report == "erosnow"){
        $videoArticlesQuery = AvErosNows::select('av_eros_nows.content_id','av_eros_nows.title as article','av_eros_nows.categories as category','av_eros_nows.created_date as added_at','av_eros_nows.duration',DB::raw('avg(user_logs.duration)
        as avg'),DB::raw("'Erosnow' as provider"));
        $videoArticlesQuery->with(['watches' => function ($query) use ($request,$startDate,$endDate) {
            $query->where('action','=', 'play');
            $query->where('type','=', 'video');
            if($startDate){
                $query->whereDate('date_time', '>=', $startDate);
            }
            if($endDate){
                $query->whereDate('date_time', '<=', $endDate);
            }
        }]);
        $videoArticlesQuery->with(['unique_watches' => function ($query) use ($request,$startDate,$endDate) {
            $query->where('action','=', 'play');
            $query->where('type','=', 'video');
            if($startDate){
                $query->whereDate('date_time', '>=', $startDate);
            }
            if($endDate){
                $query->whereDate('date_time', '<=', $endDate);
            }
        }]);
        $videoArticlesQuery->with('wishlist');
        $videoArticlesQuery->leftjoin("user_logs",function($join) use ($request,$startDate,$endDate) {
            $join->on(function ($query) use ($request,$startDate,$endDate)  {
                $query->on('user_logs.content_id','=','av_eros_nows.content_id');
                if($startDate){
                    $query->whereDate('user_logs.date_time', '>=', $startDate);
                }
                if($endDate){
                    $query->whereDate('user_logs.date_time', '<=', $endDate);
                }
            });
        });
        $videoArticlesQuery->groupBy('av_eros_nows.content_id');
        }

        // Kids Data
        if($report == "kids"){
            $videoArticlesQuery = VideoContent::select('video_content.id as content_id','video_content.content_name as article',DB::raw("'' as category"),'video_content.owner as provider','video_content.created_at as added_at',DB::raw("'' as duration"),DB::raw('avg(user_logs.duration)
            as avg'));
            $videoArticlesQuery->with(['watches' => function ($query) use ($request,$startDate,$endDate) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'video');
                if($startDate){
                    $query->whereDate('date_time', '>=', $startDate);
                }
                if($endDate){
                    $query->whereDate('date_time', '<=', $endDate);
                }
            }]);
            $videoArticlesQuery->with(['unique_watches' => function ($query) use ($request,$startDate,$endDate) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'video');
                if($startDate){
                    $query->whereDate('date_time', '>=', $startDate);
                }
                if($endDate){
                    $query->whereDate('date_time', '<=', $endDate);
                }
            }]);
            if(!is_null($provider) && $provider !="erosnow"){
                $videoArticlesQuery->where('video_content.owner','=', $provider);
            }
            $videoArticlesQuery->with('wishlist');
            $videoArticlesQuery->leftjoin('user_logs','user_logs.loggable_id','=','video_content.id');
            $videoArticlesQuery->groupBy('video_content.id');
        }
        
        
        if($export){
            $videoArticles = $videoArticlesQuery->get()->toArray();
            //dd($videoArticles);
            return Excel::download(new VideoArticleExport($videoArticles), 'video-article-export.xlsx');
        }

        if(!$export){
            $videoArticles =  $videoArticlesQuery->paginate($paginateSize);
            //dd($videoArticles);
            return view('video-articles-report', [
                'videoArticles' => $videoArticles
            ]);
        }
    }

    public function mostWatched(Request $request)
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

        $logQuery = UserLog::with(['erosnow','avvatta_user','loggable','loggable.video_category','loggable.video_sub_category']);
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->where('loggable_type','!=', 'App\Models\GameContent');
        $logQuery->where('action','=', 'play');
        if(!is_null($provider)){
            if($provider != "erosnow"){
            $logQuery->join('video_content', 'user_logs.loggable_id','=', 'video_content.id');
            $logQuery->where('video_content.owner','=', $provider);
            }else{
                $logQuery->where('loggable_type','=', 'App\Models\AvErosNows');
            }
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
        
        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }
        $logQuery->groupBy('user_logs.loggable_id');
        $logQuery->groupBy('user_logs.user_id');
        if(!$multiDate){
            $logQuery->orderBy(DB::raw('count(user_logs.user_id)'),'desc');
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get()->take(10);
        }
        if($multiDate){
            $logs = $logQuery->get()->take(100);
        }
        return view('video-most-watched-report')
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

        $logQuery = UserLog::with(['erosnow','avvatta_user','loggable','loggable.video_category','loggable.video_sub_category']);
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->where('loggable_type','!=', 'App\Models\GameContent');
        $logQuery->where('action','=', 'play');
        if(!is_null($provider)){
            if($provider != "erosnow"){
            $logQuery->join('video_content', 'user_logs.loggable_id','=', 'video_content.id');
            $logQuery->where('video_content.owner','=', $provider);
            }else{
                $logQuery->where('loggable_type','=', 'App\Models\AvErosNows');
            }
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

        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        $logQuery->groupBy('user_logs.user_id');
        $logQuery->groupBy('user_logs.loggable_id');
        if(!$multiDate){
            $logQuery->orderBy(DB::raw('count(user_logs.user_id)'),'desc');
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get()->take(10);
        }
        if($multiDate){
            $logs = $logQuery->get()->take(100);
        }
        return view('video-top-repeat-user-report')
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
        $provider = null;
        $providerRequest = $request->input('provider');
        if(Auth::user()->is_cp == 1){
            $providerRequest = Auth::user()->roles->first()->name;
        }
        $provider = ($providerRequest)?$providerRequest:$provider;

        $logQuery = UserLog::select('user_logs.*',DB::raw('count(user_logs.genre) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->where('loggable_type','!=', 'App\Models\GameContent');
        $logQuery->where('action','=', 'play');
        $logQuery->whereNotNull('genre');
        if(!is_null($provider)){
            if($provider != "erosnow"){
            $logQuery->join('video_content', 'user_logs.loggable_id','=', 'video_content.id');
            $logQuery->where('video_content.owner','=', $provider);
            }else{
                $logQuery->where('loggable_type','=', 'App\Models\AvErosNows');
            }
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

        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }
        
        $logQuery->groupBy('user_logs.category');
        $logQuery->groupBy('user_logs.genre');
        $logQuery->orderBy(DB::raw('count(user_logs.genre)'),'desc');
        if(!$multiDate){
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get();
        }
        if($multiDate){
            $logs = $logQuery->get();
        }
        return view('video-top-genre-watched-report')
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

        $logQuery = UserLog::with('avvatta_user');
        $logQuery->select('user_logs.*',DB::raw("GROUP_CONCAT( DISTINCT category) as category_list"),DB::raw('count(DISTINCT category) as category_count'));
        $logQuery->where('type','=', 'video');
        $logQuery->where('loggable_type','!=', 'App\Models\GameContent');
        $logQuery->where('action','=', 'play');
        $logQuery->whereIn('category', ['erosnow', 'kids', 'fun','cod','hig','siy']);
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

        if($multiDate){
            $logQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $logQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        $logQuery->groupBy('user_id');
        $logQuery->having(DB::raw('count(DISTINCT category)'), '>', 1);
        if(!$multiDate){
            $logQuery->addSelect(DB::raw("'-' as dates"));
            $logs = $logQuery->get();
        }
        if($multiDate){
            $logs = $logQuery->get();
        }
        
        return view('video-all-category-user-report')
        ->with([
            'logs'=>$logs
        ]);
    }
    
}

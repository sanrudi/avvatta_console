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


class ElearnContentReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $userLogs = UserLog::all();
    }
   
    public function elearnReport(Request $request)
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

        $logQuery = UserLog::with('avvatta_user');
        $logQuery->select('user_logs.*');
        $logQuery->where('type','=', 'video');
        $logQuery->where('action','=', 'play');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        //$logQuery->whereIn('sub_cat', ['8to13']);
        if($startDate){
            $logQuery->where('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->where('date_time', '<=', $endDate);
        }    
        $logQuery->groupBy('user_id');
        $logQuery->having(DB::raw('count(category)'), '>', 0);
        $logs = $logQuery->get();
        
        return view('elearn-report')
        ->with([
            'logs'=>$logs
        ]);

    }

    public function mostWatchedElearnContent(Request $request)
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

        $logQuery = UserLog::with('loggable');
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('action','=', 'play');
        if($startDate){
            $logQuery->where('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->where('date_time', '<=', $endDate);
        }
        $logQuery->groupBy('user_logs.loggable_id');
        $logQuery->groupBy('user_logs.user_id');
        $logQuery->orderBy(DB::raw('count(user_logs.user_id)'),'desc');
        $logs = $logQuery->get()->take(10);
        return view('elearn-most-watched-report')
        ->with([
            'logs'=>$logs
        ]);
    }
    
    public function topTenElearnContent(Request $request)
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

        $logQuery = UserLog::with('loggable');
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('type','=', 'video');
        $logQuery->where('action','=', 'play');
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }
        $logQuery->groupBy('user_logs.user_id');
        $logQuery->groupBy('user_logs.loggable_id');
        $logQuery->orderBy(DB::raw('count(user_logs.user_id)'),'desc');
        $logs = $logQuery->get()->take(10);
        return view('elearn-top-ten-report')
        ->with([
            'logs'=>$logs
        ]);
    }

    public function topGenreWatched(Request $request)
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

        $logQuery = UserLog::select('user_logs.*',DB::raw('count(user_logs.genre) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('action','=', 'play');
        $logQuery->whereNotNull('genre');
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }
        $logQuery->groupBy('user_logs.category');
        $logQuery->groupBy('user_logs.genre');
        $logQuery->orderBy(DB::raw('count(user_logs.genre)'),'desc');
        $logs = $logQuery->get();
        return view('elearn-top-genre-watched-report')
        ->with([
            'logs'=>$logs
        ]);
    }

    public function topRepeatedBySingleUser(Request $request)
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

        $logQuery = UserLog::with('loggable','avvatta_user');
        $logQuery->select('user_logs.*',DB::raw('count(user_logs.user_id) as count'));
        $logQuery->where('type','=', 'video');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        $logQuery->where('action','=', 'play');
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }
        $logQuery->groupBy('user_logs.user_id');
        $logQuery->groupBy('user_logs.loggable_id');
        $logQuery->orderBy(DB::raw('count(user_logs.user_id)'),'desc');
        $logs = $logQuery->get()->take(10);
        return view('elearn-top-repeat-user-report')
        ->with([
            'logs'=>$logs
        ]);
    }

    public function allCategoryUsers(Request $request)
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

        $logQuery = UserLog::with('avvatta_user');
        $logQuery->select('user_logs.*');
        $logQuery->where('type','=', 'video');
        $logQuery->where('action','=', 'play');
        $logQuery->whereIn('category', ['fun','cod','hig','siy']);
        if($startDate){
            $logQuery->whereDate('date_time', '>=', $startDate);
        }
        if($endDate){
            $logQuery->whereDate('date_time', '<=', $endDate);
        }    
        $logQuery->groupBy('user_id');
        $logQuery->having(DB::raw('count(category)'), '>', 1);
        $logs = $logQuery->get();
        return view('video-all-category-user-report')
        ->with([
            'logs'=>$logs
        ]);
    }
    
}

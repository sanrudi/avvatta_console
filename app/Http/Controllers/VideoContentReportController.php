<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\UserLog;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Exports\Videos\VideoArticleExport;
use Maatwebsite\Excel\Facades\Excel;


class VideoContentReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $userLogs = UserLog::all();
        // print_r($userLogs);
        // foreach($userLogs as $userLog){
        //     if($userLog->loggable_type != ""){
        //         print_r($userLog->loggable);
        //     }
        // }
    }

    public function videoArticles(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";
        $export = ($request->input('export'))?1:0;
        if($request->input('type') && ($request->input('type') == "erosnow" || $request->input('type') == "kids" )){
            $report = $request->input('type');
        }

        // Erosnow Data
        if($report == "erosnow"){
        $videoArticlesQuery = AvErosNows::select('av_eros_nows.content_id','av_eros_nows.title','av_eros_nows.categories','av_eros_nows.created_date','av_eros_nows.duration',DB::raw('avg(user_logs.duration)
        as avg'));
        $videoArticlesQuery->with(['erosnow_watches' => function ($query) use ($request) {
            $query->where('action','=', 'play');
            if($request->input('startDate')){
                $query->where('date_time', '>=', $request->input('startDate'));
            }
            if($request->input('endDate')){
                $query->where('date_time', '<=', $request->input('endDate'));
            }
        }]);
        $videoArticlesQuery->with(['erosnow_unique_watches' => function ($query) use ($request) {
            $query->where('action','=', 'play');
            if($request->input('startDate')){
                $query->where('date_time', '>=', $request->input('startDate'));
            }
            if($request->input('endDate')){
                $query->where('date_time', '<=', $request->input('endDate'));
            }
        }]);
        $videoArticlesQuery->with('erosnow_wishlist');
        $videoArticlesQuery->leftjoin("user_logs",function($join) use ($request) {
            $join->on(function ($query) use ($request)  {
                $query->on('user_logs.content_id','=','av_eros_nows.content_id');
                if($request->input('startDate')){
                    $query->where('user_logs.date_time', '>=', $request->input('startDate'));
                }
                if($request->input('endDate')){
                    $query->where('user_logs.date_time', '<=', $request->input('endDate'));
                }
            });
        });
        $videoArticlesQuery->groupBy('av_eros_nows.content_id');
        }

        // Kids Data
        if($report == "kids"){
            $videoArticlesQuery = AvErosNows::select('av_eros_nows.content_id','av_eros_nows.title','av_eros_nows.categories','av_eros_nows.created_date','av_eros_nows.duration',DB::raw('avg(user_logs.duration)
            as avg'));
            $videoArticlesQuery->with(['erosnow_watches' => function ($query) use ($request) {
                $query->where('action','=', 'play');
                if($request->input('startDate')){
                    $query->where('date_time', '>=', $request->input('startDate'));
                }
                if($request->input('endDate')){
                    $query->where('date_time', '<=', $request->input('endDate'));
                }
            }]);
            $videoArticlesQuery->with(['erosnow_unique_watches' => function ($query) use ($request) {
                $query->where('action','=', 'play');
                if($request->input('startDate')){
                    $query->where('date_time', '>=', $request->input('startDate'));
                }
                if($request->input('endDate')){
                    $query->where('date_time', '<=', $request->input('endDate'));
                }
            }]);
            $videoArticlesQuery->with('erosnow_wishlist');
            $videoArticlesQuery->leftjoin('user_logs','user_logs.content_id','=','av_eros_nows.content_id');
            $videoArticlesQuery->groupBy('av_eros_nows.content_id');
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
}

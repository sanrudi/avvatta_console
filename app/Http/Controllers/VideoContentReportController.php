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
        if($request->input('type') && ($request->input('type') == "erosnow" || $request->input('type') == "kids" )){
            $report = $request->input('type');
        }

        // Erosnow Data
        if($report == "erosnow"){
        $videoArticlesQuery = AvErosNows::select('av_eros_nows.content_id','av_eros_nows.title as article','av_eros_nows.categories as category','av_eros_nows.created_date as added_at','av_eros_nows.duration',DB::raw('avg(user_logs.duration)
        as avg'));
        $videoArticlesQuery->with(['watches' => function ($query) use ($request) {
            $query->where('action','=', 'play');
            if($request->input('startDate')){
                $query->where('date_time', '>=', $request->input('startDate'));
            }
            if($request->input('endDate')){
                $query->where('date_time', '<=', $request->input('endDate'));
            }
        }]);
        $videoArticlesQuery->with(['unique_watches' => function ($query) use ($request) {
            $query->where('action','=', 'play');
            if($request->input('startDate')){
                $query->where('date_time', '>=', $request->input('startDate'));
            }
            if($request->input('endDate')){
                $query->where('date_time', '<=', $request->input('endDate'));
            }
        }]);
        $videoArticlesQuery->with('wishlist');
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
            $videoArticlesQuery = VideoContent::select('video_content.id as content_id','video_content.content_name as article',DB::raw("'' as category"),'video_content.created_at as added_at',DB::raw("'' as duration"),DB::raw('avg(user_logs.duration)
            as avg'));
            $videoArticlesQuery->with(['watches' => function ($query) use ($request) {
                $query->where('action','=', 'play');
                if($request->input('startDate')){
                    $query->where('date_time', '>=', $request->input('startDate'));
                }
                if($request->input('endDate')){
                    $query->where('date_time', '<=', $request->input('endDate'));
                }
            }]);
            $videoArticlesQuery->with(['unique_watches' => function ($query) use ($request) {
                $query->where('action','=', 'play');
                if($request->input('startDate')){
                    $query->where('date_time', '>=', $request->input('startDate'));
                }
                if($request->input('endDate')){
                    $query->where('date_time', '<=', $request->input('endDate'));
                }
            }]);
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
}

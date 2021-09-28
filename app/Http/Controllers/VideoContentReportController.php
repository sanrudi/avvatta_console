<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\UserLog;
use App\Models\AvErosNows;
use App\Models\GameContent;

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

    public function videoArticles()
    {
        $paginateSize = 20;
        $videoArticlesQuery = AvErosNows::select('av_eros_nows.content_id','av_eros_nows.title','av_eros_nows.categories','av_eros_nows.created_date','av_eros_nows.duration',DB::raw('avg(user_logs.duration)
        as avg'));
        $videoArticlesQuery->with('erosnow_watches');
        $videoArticlesQuery->with('erosnow_unique_watches');
        $videoArticlesQuery->with('erosnow_wishlist');
        $videoArticlesQuery->with('erosnow');
        $videoArticlesQuery->leftjoin('user_logs','user_logs.content_id','=','av_eros_nows.content_id');
        $videoArticlesQuery->groupBy('av_eros_nows.content_id');
        //$videoArticlesQuery->groupBy('av_eros_nows.content_id');
        //$videoArticlesQuery->erosnow()->avg();
        $videoArticles =  $videoArticlesQuery->paginate($paginateSize);
        //$videoArticles =  $videoArticlesQuery->get();
        //dd($videoArticles);
        return view('video-articles-report', [
            'videoArticles' => $videoArticles
        ]);
    }
}

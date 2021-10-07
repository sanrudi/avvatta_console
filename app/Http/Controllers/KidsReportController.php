<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class KidsReportController extends Controller
{

    CONST KIDS_CATEGORY = 'kids';

    public function kidsReport()
    {
        $kids_contents = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'video_content.id', '=','user_logs.loggable_id')
        ->join('users', 'users.id', '=', 'user_logs.user_id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select('users.firstname', 'users.lastname', 'video_content.content_name', 'sub_categories.name as category_name', 'user_logs.date_time')
        ->get();
        
        return view('kid-report', ['kids_contents' => $kids_contents]);

    }

    public function mostWatchedKidsContent()
    {
        $mostWatchedKidsContents = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'user_logs.loggable_id','=', 'video_content.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, video_content.content_name,video_content.owner as provider, sub_categories.name as category_name,COUNT(*)"))
        ->groupBy('user_logs.loggable_id')
        ->havingRaw("COUNT(*) > 1")->get();

        return view('kid-report', ['mostWatchedKidsContents' => $mostWatchedKidsContents]);

    }

    public function topKidsContent()
    {

    }

    public function repeatedKidsContentByUser()
    {
        $repeatedKidsContents = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'user_logs.loggable_id','=', 'video_content.id')
        ->join('users', 'user_logs.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, firstname, lastname, video_content.content_name, video_content.owner as provider, sub_categories.name as category_name,COUNT(*)"))
        ->groupBy('user_logs.user_id','user_logs.loggable_id')
        ->havingRaw("COUNT(*) > 1")->get();

        return view('kid-report', ['repeatedKidsContents' => $repeatedKidsContents]);

    }
}
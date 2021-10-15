<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class KidsReportController extends Controller
{

    CONST KIDS_CATEGORY = 'kids';

    public function kidsReport(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 

        $kidsQuery = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'video_content.id', '=','user_logs.loggable_id')
        ->join('users', 'users.id', '=', 'user_logs.user_id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.cat_id')
        ->join('sub_categories as sc', 'sc.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select('users.firstname', 'users.lastname', 'users.email', 'users.mobile', 'video_content.content_name', 'sub_categories.name as category_name','sc.name as sub_cat_name','user_logs.date_time',DB::raw("COUNT(DISTINCT(sub_categories.id)) as count"));
        $kidsQuery->groupBy('user_logs.user_id')->orderBy('count','desc')->havingRaw("count > 4");
        if($startDate){
            $kidsQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $kidsQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  

        $kids_contents = $kidsQuery->get();
        
        return view('kid-report', ['kids_contents' => $kids_contents]);

    }

    public function mostWatchedKidsContent(Request $request)
    {
        $mostWatchedKidsContents = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'user_logs.loggable_id','=', 'video_content.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.cat_id')
        ->join('sub_categories as sc', 'sc.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, video_content.content_name,video_content.owner as provider, sub_categories.name as category_name,sc.name as sub_cat_name,COUNT(*) as count"))
        ->groupBy('user_logs.loggable_id')->orderBy('count','desc')
        ->havingRaw("COUNT(*) > 1")->get();

        return view('kid-report', ['mostWatchedKidsContents' => $mostWatchedKidsContents]);

    }

    public function topKidsContent()
    {

    }

    public function repeatedKidsContentByUser(Request $request)
    {
        $reportFrom="";$startDate="";$endDate="";
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 

        $kidsQuery = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'user_logs.loggable_id','=', 'video_content.id')
        ->join('users', 'user_logs.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.cat_id')
        ->join('sub_categories as sc', 'sc.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, firstname, lastname, email, mobile, video_content.content_name, video_content.owner as provider, sub_categories.name as category_name,sc.name as sub_cat_name, COUNT(*) as count"))
        ->groupBy('user_logs.user_id','user_logs.loggable_id')->orderBy('count','desc')
        ->havingRaw("COUNT(*) > 1");

        if($startDate){
            $kidsQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $kidsQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  

        $repeatedKidsContents = $kidsQuery->get();

        return view('kid-report', ['repeatedKidsContents' => $repeatedKidsContents]);

    }
}
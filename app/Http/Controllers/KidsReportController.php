<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Auth;


class KidsReportController extends Controller
{

    CONST KIDS_CATEGORY = 'kids';
    
    
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
        
        $this->country = env('COUNTRY','SA');
    }
    
    
    

    public function kidsReport(Request $request)
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

        $kidsQuery = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'video_content.id', '=','user_logs.loggable_id')
        ->join('users', 'users.id', '=', 'user_logs.user_id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.cat_id')
        ->join('sub_categories as sc', 'sc.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select('users.firstname','user_logs.user_country', 'users.lastname', 'users.email', 'users.mobile', 'video_content.content_name', 'sub_categories.name as category_name','sc.name as sub_cat_name','user_logs.date_time',DB::raw("GROUP_CONCAT( DISTINCT sub_categories.name) as category_list"),DB::raw("COUNT(DISTINCT(sub_categories.id)) as count"));
        $kidsQuery->groupBy('user_logs.user_id')
        ->orderBy('count','desc')
        ->havingRaw("count > 1");
        echo $this->country;
        
        switch ($this->country) {
            
           case 'SA':
               $kidsQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $kidsQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $kidsQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($startDate){
            $kidsQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $kidsQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  
        if($device){
            $kidsQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $kidsQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $kidsQuery->whereIn(DB::raw("DATE(user_logs.date_time)"), $multiDate);
        }
        if($ageRange){
            $kidsQuery->where('user_logs.age', '>=', $ageRange);
            $kidsQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $kidsQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        if($multiDate){
            $kidsQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $kidsQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        if(!$multiDate){
            $kidsQuery->addSelect(DB::raw("'-' as dates"));
            $kids_contents = $kidsQuery->get();
        }
        if($multiDate){
            $kids_contents = $kidsQuery->get();
        }
        var_dump($kids_contents);
        return view('kid-report', ['kids_contents' => $kids_contents]);

    }

    public function mostWatchedKidsContent(Request $request)
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

        $kidsQuery = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'user_logs.loggable_id','=', 'video_content.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.cat_id')
        ->join('sub_categories as sc', 'sc.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, video_content.content_name,video_content.owner as provider, sub_categories.name as category_name,sc.name as sub_cat_name,COUNT(*) as count"))
        ->groupBy('user_logs.loggable_id')->take(10)->orderBy('count','desc');
        
        switch ($this->country) {
            
           case 'SA':
               $kidsQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $kidsQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $kidsQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        
        if(!is_null($provider)){
            $kidsQuery->where('video_content.owner','=', $provider);
        }
        if($startDate){
            $kidsQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $kidsQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  
        if($device){
            $kidsQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $kidsQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $kidsQuery->whereIn(DB::raw("DATE(user_logs.date_time)"), $multiDate);
        }
        if($ageRange){
            $kidsQuery->where('user_logs.age', '>=', $ageRange);
            $kidsQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $kidsQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));
        if($multiDate){
            $kidsQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $kidsQuery->groupBy(DB::raw("DATE(date_time)"));
        }
        if(!$multiDate){
            $kidsQuery->addSelect(DB::raw("'-' as dates"));
            $mostWatchedKidsContents = $kidsQuery->get();
        }
        if($multiDate){
            $mostWatchedKidsContents = $kidsQuery->get();
        }
        return view('kid-report', ['mostWatchedKidsContents' => $mostWatchedKidsContents]);

    }

    public function repeatedKidsContentByUser(Request $request)
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

        $kidsQuery = DB::connection('mysql2')->table('user_logs')
        ->join('video_content', 'user_logs.loggable_id','=', 'video_content.id')
        ->join('users', 'user_logs.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'video_content.cat_id')
        ->join('sub_categories as sc', 'sc.id', '=', 'video_content.sub_id')
        ->where('type', 'video')
        ->where('category', 'kids')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, firstname, lastname, email, mobile, video_content.content_name, video_content.owner as provider, sub_categories.name as category_name,sc.name as sub_cat_name, COUNT(*) as count"))
        ->groupBy('user_logs.user_id','user_logs.loggable_id')->take(20)->orderBy('count','desc')
        ->havingRaw("COUNT(*) > 1");
        
        switch ($this->country) {
            
           case 'SA':
               $kidsQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $kidsQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $kidsQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        
        if(!is_null($provider)){
            $kidsQuery->where('video_content.owner','=', $provider);
        }
        if($startDate){
            $kidsQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $kidsQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  
        if($device){
            $kidsQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $kidsQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $kidsQuery->whereIn(DB::raw("DATE(user_logs.date_time)"), $multiDate);
        }
        if($ageRange){
            $kidsQuery->where('user_logs.age', '>=', $ageRange);
            $kidsQuery->where('user_logs.age', '<=', $ageRange+9);
        } 

        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $kidsQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));
        if($multiDate){
            $kidsQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $kidsQuery->groupBy(DB::raw("DATE(date_time)"));
        }
        if(!$multiDate){
            $kidsQuery->addSelect(DB::raw("'-' as dates"));
            $repeatedKidsContents = $kidsQuery->get();
        }
        if($multiDate){
            $repeatedKidsContents = $kidsQuery->get();
        }

        return view('kid-report', ['repeatedKidsContents' => $repeatedKidsContents]);

    }
}
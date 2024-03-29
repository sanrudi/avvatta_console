<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\UserLog;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Models\VideoContent;

class RevenueReportController extends Controller
{
    
    public function __construct(Request $request)
    {
        // $this->middleware('auth');
        // check the domain and set country
            $this->country = env('COUNTRY','SA');
            $server_host = $request->server()['SERVER_NAME'];
            // var_dump($server_host);
                $referer =  request()->headers->get('referer');
                if($server_host=='gh.avvatta.com') {
                 
                    $this->country = 'GH';
                    
                }
              
                if($server_host=='ng.avvatta.com') {
                 
                    $this->country = 'NG';
                    
                }
        // var_dump($referer);
    //    $this->country = env('COUNTRY','SA');
             //   echo $this->country ;
    }
    
    function index(Request $request)
    {
        
        $fromdate = date('Y-m', strtotime('-1 month')).'-01';
        if($request->input('startDate') != null) {
        $fromdate = $request->input('startDate');
        }
        $todate = date('Y-m') .'-00';
        if($request->input('endDate') != null) {
        $todate = $request->input('endDate'); 
        }
       
        $todate = date('Y-m-d H:i:s', strtotime($todate . ' +1 day'));
        // last month
        // Firstday
        // get catogories

        // Erosnow Watches
        $erosnowQuery = UserLog::select('user_logs.*');
        $erosnowQuery->where('action','=', 'play');
        $erosnowQuery->where('type','=', 'video');
        $erosnowQuery->where(function ($query) {
                $query->where('category', '=', "erosnow");
        });
            
            
        switch ($this->country) {
            
           case 'SA':
               $erosnowQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $erosnowQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $erosnowQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
            
        if($fromdate){
        $erosnowQuery->whereDate('date_time', '>=', $fromdate);
        }
        if($todate){
        $erosnowQuery->whereDate('date_time', '<=', $todate);
        }
        $erosnowWatches = $erosnowQuery->get()->count();
        
        // Games Watches
        $gameQuery = GameContent::select('game_content.play_for_free',DB::raw('count(user_logs.loggable_id) as count'),DB::raw("(CASE WHEN game_content.play_for_free='0' THEN 'gogames' WHEN game_content.play_for_free='1' THEN 'gamepix' ELSE '' END) as provider"));
        $gameQuery->join('user_logs','user_logs.loggable_id','=','game_content.id');
        $gameQuery->groupBy('game_content.play_for_free');
        $gameQuery->where('user_logs.type','=', 'game');
        
        switch ($this->country) {
            
           case 'SA':
               $gameQuery->where('user_logs.user_country','=', 0);
               break;
           case 'GH':
               $gameQuery->where('user_logs.user_country','=', 1);
               break;
           case 'NG':
               $gameQuery->where('user_logs.user_country','=', 2);
               break;
           default:
               break;
        }
        
        
        if($fromdate){
        $gameQuery->whereDate('date_time', '>=', $fromdate);
        }
        if($todate){
        $gameQuery->whereDate('date_time', '<=', $todate);
        }                                        
        $gameWatches = $gameQuery->get()->toArray();

        // Kids Watches
        $kidQuery = VideoContent::select('video_content.owner as provider',DB::raw('count(user_logs.loggable_id) as count'));
        $kidQuery->join('user_logs','user_logs.loggable_id','=','video_content.id');
        $kidQuery->groupBy('video_content.owner');
        $kidQuery->where('user_logs.type','=', 'video');
        $kidQuery->where('user_logs.category','=', 'kids');
        
        switch ($this->country) {
            
           case 'SA':
               $kidQuery->where('user_logs.user_country','=', 0);
               break;
           case 'GH':
               $kidQuery->where('user_logs.user_country','=', 1);
               break;
           case 'NG':
               $kidQuery->where('user_logs.user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($fromdate){
        $kidQuery->whereDate('date_time', '>=', $fromdate);
        }
        if($todate){
        $kidQuery->whereDate('date_time', '<=', $todate);
        }                                        
        $kidWatches = $kidQuery->get()->toArray();

        // Fun Watches
        $funQuery = VideoContent::select('video_content.owner as provider',DB::raw('count(user_logs.loggable_id) as count'));
        $funQuery->join('user_logs','user_logs.loggable_id','=','video_content.id');
        $funQuery->groupBy('video_content.owner');
        $funQuery->where('user_logs.type','=', 'video');
        $funQuery->where('user_logs.category','=', 'fun');
        
        
        switch ($this->country) {
            
           case 'SA':
               $funQuery->where('user_logs.user_country','=', 0);
               break;
           case 'GH':
               $funQuery->where('user_logs.user_country','=', 1);
               break;
           case 'NG':
               $funQuery->where('user_logs.user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($fromdate){
        $funQuery->whereDate('date_time', '>=', $fromdate);
        }
        if($todate){
        $funQuery->whereDate('date_time', '<=', $todate);
        }                                        
        $funWatches = $funQuery->get()->toArray();
        
        // higher Watches
        $higQuery = VideoContent::select('video_content.owner as provider',DB::raw('count(user_logs.loggable_id) as count'));
        $higQuery->join('user_logs','user_logs.loggable_id','=','video_content.id');
        $higQuery->groupBy('video_content.owner');
        $higQuery->where('user_logs.type','=', 'video');
        $higQuery->where('user_logs.category','=', 'hig');
        
        switch ($this->country) {
            
           case 'SA':
               $higQuery->where('user_logs.user_country','=', 0);
               break;
           case 'GH':
               $higQuery->where('user_logs.user_country','=', 1);
               break;
           case 'NG':
               $higQuery->where('user_logs.user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($fromdate){
        $higQuery->whereDate('date_time', '>=', $fromdate);
        }
        if($todate){
        $higQuery->whereDate('date_time', '<=', $todate);
        }                                        
        $higWatches = $higQuery->get()->toArray();

        // Coding Watchs
        $codQuery = VideoContent::select('video_content.owner as provider',DB::raw('count(user_logs.loggable_id) as count'));
        $codQuery->join('user_logs','user_logs.loggable_id','=','video_content.id');
        $codQuery->groupBy('video_content.owner');
        $codQuery->where('user_logs.type','=', 'video');
        $codQuery->where('user_logs.category','=', 'cod');
        
        switch ($this->country) {
            
           case 'SA':
               $codQuery->where('user_logs.user_country','=', 0);
               break;
           case 'GH':
               $codQuery->where('user_logs.user_country','=', 1);
               break;
           case 'NG':
               $codQuery->where('user_logs.user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($fromdate){
        $codQuery->whereDate('date_time', '>=', $fromdate);
        }
        if($todate){
        $codQuery->whereDate('date_time', '<=', $todate);
        }                                        
        $codWatches = $codQuery->get()->toArray();

        // siying Watchs
        $siyQuery = VideoContent::select('video_content.owner as provider',DB::raw('count(user_logs.loggable_id) as count'));
        $siyQuery->join('user_logs','user_logs.loggable_id','=','video_content.id');
        $siyQuery->groupBy('video_content.owner');
        $siyQuery->where('user_logs.type','=', 'video');
        $siyQuery->where('user_logs.category','=', 'siy');
        
        switch ($this->country) {
            
           case 'SA':
               $siyQuery->where('user_logs.user_country','=', 0);
               break;
           case 'GH':
               $siyQuery->where('user_logs.user_country','=', 1);
               break;
           case 'NG':
               $siyQuery->where('user_logs.user_country','=', 2);
               break;
           default:
               break;
        }
        
        if($fromdate){
        $siyQuery->whereDate('date_time', '>=', $fromdate);
        }
        if($todate){
        $siyQuery->whereDate('date_time', '<=', $todate);
        }                                        
        $siyWatches = $siyQuery->get()->toArray();



        $cat = DB::connection('mysql2')->table('categories')
                ->select('id','name')
                ->get();
        
        $frequency = ['Daily','Weekly','Monthly'];
        $subscription = DB::connection('mysql2')->table('subscriptions')
                ->select('subscriptions.id', 'categories.name','title','main_cat_id','amount')
                ->join('categories','main_cat_id','=','categories.id')
                ->get();
     /*   
        $todate = DB::table('user_payments')
                ->max('created_at');
         $fromdate = DB::table('user_payments')
                ->min('created_at');       
                var_dump($todate);
          var_dump($fromdate);
       */   

        $user_country = 0;
        switch ($this->country) {
            case 'SA':
                $user_country = 0;
                break;
            case 'GH':
                $user_country = 1;
                break;
            case 'NG':
                $user_country = 2;
                break;
            default:
                break;
        }
        
        foreach ($subscription as $value) {  
        $amountQuery = DB::connection('mysql2')->table('user_payments')->where('subscription_id','=',$value->id);
        if($user_country != 2){
            $amountQuery->where('status','=',1)->where('user_country','=', $user_country); 
        }
        $all[$value->id] = $amountQuery->whereBetween('user_payments.created_at',array($fromdate,$todate))->get()->sum('amount');

        // get subs count
        $countQuery = DB::connection('mysql2')->table('user_payments')->where('subscription_id','=',$value->id);
        if($user_country != 2){
            $countQuery->where('status','=',1)->where('user_country','=', $user_country);
        }
        $subcount[$value->id] = $countQuery->whereBetween('user_payments.created_at',array($fromdate,$todate))->count();

        }
        
        $allTotalQuery = DB::connection('mysql2')->table('user_payments');
        if($user_country != 2){
        $allTotalQuery->where('status','=',1)->where('user_country','=', $user_country); 
        }
        $all['total'] = $allTotalQuery->whereBetween('user_payments.created_at',array($fromdate,$todate))->get()->sum('amount');
         
        $allCountQuery = DB::connection('mysql2')->table('user_payments');
        if($user_country != 2){
        $allCountQuery->where('status','=',1)->where('user_country','=', $user_country);
        }
        $all['count'] = $allCountQuery->whereBetween('user_payments.created_at',array($fromdate,$todate))->get()->count();
        
        $all['fromdate'] = Carbon::parse($fromdate);     
        $all['todate'] = Carbon::parse($todate);
       // var_dump($all);
        return view('revenue-report',['cat'=>$cat,
                                'frequency'=>$frequency,
                                'subs'=>$subscription,
                                'subcount'=>$subcount,
                                'all' =>$all, 
                                'erosnowWatches' =>$erosnowWatches,
                                'gameWatches' =>$gameWatches,
                                'kidWatches' =>$kidWatches,
                                'funWatches' =>$funWatches,
                                'higWatches' =>$higWatches,
                                'codWatches' =>$codWatches,
                                'siyWatches' =>$siyWatches]);
    }
}

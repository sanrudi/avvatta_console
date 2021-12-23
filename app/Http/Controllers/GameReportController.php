<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Games\GamesExport;
use App\Exports\Games\MostPlayedGamesExport;
use App\Exports\Games\RepeatedGamesExport;
use App\Models\UserLog;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Exports\Videos\VideoArticleExport;
use App\Exports\Games\GameArticleExport;
use Log;
use Auth;


class GameReportController extends Controller
{
   private $country;
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
    public function index(Request $request)
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

        $gameQuery = DB::connection('mysql2')->table('user_logs')
        ->join('game_content', 'game_content.id', '=','user_logs.loggable_id')
        ->join('users', 'users.id', '=', 'user_logs.user_id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->where('type', 'game');
        $gameQuery->select('users.firstname', 'users.lastname','users.email', 'users.mobile', 'game_name', 'sub_categories.name as category_name', 'user_logs.date_time',DB::raw("GROUP_CONCAT( DISTINCT sub_categories.name) as category_list"),DB::raw("COUNT(DISTINCT(sub_categories.id)) as count"));
        $gameQuery->groupBy('user_logs.user_id')
        ->orderBy('count','desc')
        ->havingRaw("count > 1");
        
         switch ($this->country) {
            
           case 'SA':
               $gameQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $gameQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $gameQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        
        
        if($startDate){
            $gameQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $gameQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  
        if($device){
            $gameQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $gameQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $gameQuery->whereIn(DB::raw("DATE(user_logs.date_time)"), $multiDate);
        }
        if($ageRange){
            $gameQuery->where('user_logs.age', '>=', $ageRange);
            $gameQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $gameQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        if($multiDate){
            $gameQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $gameQuery->groupBy(DB::raw("DATE(date_time)"));
        }

        if(!$multiDate){
            $gameQuery->addSelect(DB::raw("'-' as dates"));
            $game_content = $gameQuery->get();
        }
        if($multiDate){
            $game_content = $gameQuery->get();
        }

        return view('game-report', ['game_contents' => $game_content]);
    }

    public function exportGameContent(Request $request)
    {
           return Excel::download(new GamesExport, 'GameContent.xlsx');

    }

    public function repeatedGameBySingleUser(Request $request)
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
        $provider = ($providerRequest == "gogames")?0:$provider;
        $provider = ($providerRequest == "gamepix")?1:$provider;

        $gameQuery = DB::connection('mysql2')->table('user_logs')
        ->join('game_content', 'user_logs.loggable_id','=', 'game_content.id')
        ->join('users', 'user_logs.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->where('type', 'game')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, firstname, lastname,email,mobile, game_name, sub_categories.name as category_name,COUNT(*) as count"),DB::raw("(CASE WHEN game_content.play_for_free='0' THEN 'gogames' WHEN game_content.play_for_free='1' THEN 'gamepix' ELSE '' END) as provider"));
        switch ($this->country) {
            
           case 'SA':
               $gameQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $gameQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $gameQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        if(!is_null($provider)){
            $gameQuery->where('game_content.play_for_free','=', $provider);
        }
        if($startDate){
            $gameQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $gameQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  
        if($device){
            $gameQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $gameQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $gameQuery->whereIn(DB::raw("DATE(user_logs.date_time)"), $multiDate);
        }
        if($ageRange){
            $gameQuery->where('user_logs.age', '>=', $ageRange);
            $gameQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $gameQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));
        $gameQuery->groupBy('user_logs.user_id','user_logs.loggable_id')->havingRaw("COUNT(*) > 1")->orderBy('count','desc');
        
        if($multiDate){
            $gameQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $gameQuery->groupBy(DB::raw("DATE(date_time)"));
        }
        if(!$multiDate){
            $gameQuery->addSelect(DB::raw("'-' as dates"));
            $repeated_game = $gameQuery->get();
        }
        if($multiDate){
            $repeated_game = $gameQuery->take(20)->get();
        }

        
        return view('game-report', ['repeated_games' => $repeated_game]);
    }

    public function exportRepeatedGameBySingleUser()
    {
        return Excel::download(new RepeatedGamesExport, 'RepeatedGames.xlsx');
    }

    public function mostPlayedGames(Request $request)
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
        $provider = ($providerRequest == "gogames")?0:$provider;
        $provider = ($providerRequest == "gamepix")?1:$provider;

        $gameQuery = DB::connection('mysql2')->table('user_logs')
        ->join('game_content', 'user_logs.loggable_id','=', 'game_content.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->where('type', 'game')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, game_name, sub_categories.name as category_name,COUNT(*) as count"), DB::raw("(CASE WHEN game_content.play_for_free='0' THEN 'gogames' WHEN game_content.play_for_free='1' THEN 'gamepix' ELSE '' END) as provider"));
        $gameQuery->groupBy('user_logs.loggable_id')->orderBy('count','desc');
        switch ($this->country) {
            
           case 'SA':
               $gameQuery->where('user_country','=', 0);
               break;
           case 'GH':
               $gameQuery->where('user_country','=', 1);
               break;
           case 'NG':
               $gameQuery->where('user_country','=', 2);
               break;
           default:
               break;
        }
        if(!is_null($provider)){
            $gameQuery->where('game_content.play_for_free', '=', $provider);
        }

        if($startDate){
            $gameQuery->whereDate('user_logs.date_time', '>=', $startDate);
        }
        if($endDate){
            $gameQuery->whereDate('user_logs.date_time', '<=', $endDate);
        }  
        if($device){
            $gameQuery->where('user_logs.device', '=', $device);
        }
        if($os){
            $gameQuery->where('user_logs.os', '=', $os);
        }  
        if($multiDate){
            $gameQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }
        if($ageRange){
            $gameQuery->where('user_logs.age', '>=', $ageRange);
            $gameQuery->where('user_logs.age', '<=', $ageRange+9);
        } 
        $device = !empty($device)?$device:"All";
        $os = !empty($os)?$os:"All";
        $gameQuery->addSelect(DB::raw("'$device' as device, '$os' as os"));

        if($multiDate){
            $gameQuery->addSelect(DB::raw("DATE(date_time) as dates"));
            $gameQuery->groupBy(DB::raw("DATE(date_time)"));
        }
        if(!$multiDate){
            $gameQuery->addSelect(DB::raw("'-' as dates"));
            $most_played_games = $gameQuery->get();
        }
        if($multiDate){
            $most_played_games = $gameQuery->get();
        }
        return view('game-report', ['most_played_games' => $most_played_games]);
    }

    public function exportMostPlayedGames(Request $request)
    {
        return Excel::download(new MostPlayedGamesExport, 'MostPlayedGames.xlsx');
    }


    public function gameArticles(Request $request)
    {
        $paginateSize = 20;$export = 0;
        $reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') == "")?"7":$request->input('reportFrom');
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 

        $provider = null;
        $providerRequest = $request->input('provider');
        if(Auth::user()->is_cp == 1){
            $providerRequest = Auth::user()->roles->first()->name;
        }
        $provider = ($providerRequest == "gogames")?0:$provider;
        $provider = ($providerRequest == "gamepix")?1:$provider;

        // Games Data
            $gameArticlesQuery = GameContent::select('game_content.id as id','game_content.game_name as article','sub_categories.name as category',DB::raw("(CASE WHEN game_content.play_for_free='0' THEN 'gogames' WHEN game_content.play_for_free='1' THEN 'gamepix' ELSE '' END) as provider"),DB::raw("'' as duration,DATE(game_content.created_at) as added_at"),DB::raw('avg(user_logs.duration) as avg'));
            $gameArticlesQuery->with(['watches' => function ($query) use ($request,$startDate,$endDate) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'game');
                if($startDate){
                    $query->whereDate('date_time', '>=', $startDate);
                }
                if($endDate){
                    $query->whereDate('date_time', '<=', $endDate);
                }
            }]);
            $gameArticlesQuery->with(['unique_watches' => function ($query) use ($request,$startDate,$endDate) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'game');
                if($startDate){
                    $query->whereDate('date_time', '>=', $startDate);
                }
                if($endDate){
                    $query->whereDate('date_time', '<=', $endDate);
                }
            }]);
            if(!is_null($provider)){
                $gameArticlesQuery->where('game_content.play_for_free', '=', $provider);
            }
            $gameArticlesQuery->with('wishlist');
            $gameArticlesQuery->leftjoin('sub_categories','sub_categories.id','=','game_content.sub_cat_id');
            $gameArticlesQuery->leftjoin('user_logs','user_logs.loggable_id','=','game_content.id');
            $gameArticlesQuery->groupBy('game_content.id');
        
        
        if($export){
            $gameArticles = $gameArticlesQuery->get()->toArray();
            return Excel::download(new GameArticleExport($gameArticles), 'game-article-export.xlsx');
        }

        if(!$export){
            $gameArticles =  $gameArticlesQuery->paginate($paginateSize);
            return view('game-articles-report', [
                'gameArticles' => $gameArticles
            ]);
        }
    }

}

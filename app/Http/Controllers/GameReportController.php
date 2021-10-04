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


class GameReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $game_content = DB::connection('mysql2')->table('user_logs')
        ->join('game_content', 'game_content.id', '=','user_logs.loggable_id')
        ->join('users', 'users.id', '=', 'user_logs.user_id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->where('type', 'game')
        ->select('users.firstname', 'users.lastname', 'game_name', 'sub_categories.name as category_name', 'user_logs.date_time')->get();
        
        return view('game-report', ['game_contents' => $game_content]);
    }

    public function exportGameContent(Type $var = null)
    {
           return Excel::download(new GamesExport, 'GameContent.xlsx');

    }

    public function repeatedGameBySingleUser(Type $var = null)
    {
        $repeated_game = DB::connection('mysql2')->table('user_logs')
        ->join('game_content', 'user_logs.loggable_id','=', 'game_content.id')
        ->join('users', 'user_logs.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->where('type', 'game')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, firstname, lastname, game_name, sub_categories.name as category_name,COUNT(*)"))
        ->groupBy('user_logs.user_id','user_logs.loggable_id')
        ->havingRaw("COUNT(*) > 1")->get();

        return view('game-report', ['repeated_games' => $repeated_game]);
    }

    public function exportRepeatedGameBySingleUser()
    {
        return Excel::download(new RepeatedGamesExport, 'RepeatedGames.xlsx');
    }

    public function mostPlayedGames(Type $var = null)
    {
        $most_played_games = DB::connection('mysql2')->table('user_logs')
        ->join('game_content', 'user_logs.loggable_id','=', 'game_content.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->where('type', 'game')
        ->select(DB::raw("user_logs.user_id, user_logs.loggable_id, game_name, sub_categories.name as category_name,COUNT(*)"))
        ->groupBy('user_logs.loggable_id')
        ->havingRaw("COUNT(*) > 2")->get();

        return view('game-report', ['most_played_games' => $most_played_games]);
    }

    public function exportMostPlayedGames(Type $var = null)
    {
        return Excel::download(new MostPlayedGamesExport, 'MostPlayedGames.xlsx');
    }


    public function gameArticles(Request $request)
    {
        $paginateSize = 20;$export = 0;$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        }

        // Games Data
            $gameArticlesQuery = GameContent::select('game_content.id as id','game_content.game_name as article',DB::raw("'' as category"),'game_content.created_at as added_at',DB::raw("'' as duration"),DB::raw('avg(user_logs.duration) as avg'));
            $gameArticlesQuery->with(['watches' => function ($query) use ($request,$startDate,$endDate) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'game');
                if($startDate){
                    $query->where('date_time', '>=', $startDate);
                }
                if($endDate){
                    $query->where('date_time', '<=', $endDate);
                }
            }]);
            $gameArticlesQuery->with(['unique_watches' => function ($query) use ($request,$startDate,$endDate) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'game');
                if($startDate){
                    $query->where('date_time', '>=', $startDate);
                }
                if($endDate){
                    $query->where('date_time', '<=', $endDate);
                }
            }]);
            $gameArticlesQuery->with('wishlist');
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

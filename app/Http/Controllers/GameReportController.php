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
        $paginateSize = 20;$export = 0;$report = "erosnow";
        $export = ($request->input('export'))?1:0;
        if($request->input('type') && ($request->input('type') == "erosnow" || $request->input('type') == "kids" )){
            $report = $request->input('type');
        }

        // Erosnow Data
            $gameArticlesQuery = GameContent::select('game_content.id as id','game_content.game_name as article',DB::raw("'' as category"),'game_content.created_at as added_at',DB::raw("'' as duration"),DB::raw('avg(user_logs.duration) as avg'));
            $gameArticlesQuery->with(['watches' => function ($query) use ($request) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'game');
                if($request->input('startDate')){
                    $query->where('date_time', '>=', $request->input('startDate'));
                }
                if($request->input('endDate')){
                    $query->where('date_time', '<=', $request->input('endDate'));
                }
            }]);
            $gameArticlesQuery->with(['unique_watches' => function ($query) use ($request) {
                $query->where('action','=', 'play');
                $query->where('type','=', 'game');
                if($request->input('startDate')){
                    $query->where('date_time', '>=', $request->input('startDate'));
                }
                if($request->input('endDate')){
                    $query->where('date_time', '<=', $request->input('endDate'));
                }
            }]);
            $gameArticlesQuery->with('wishlist');
            $gameArticlesQuery->leftjoin('user_logs','user_logs.loggable_id','=','game_content.id');
            $gameArticlesQuery->where('type','=', 'game');
            $gameArticlesQuery->groupBy('game_content.id');
        
        
        if($export){
            $gameArticles = $gameArticlesQuery->get()->toArray();
            //dd($gameArticles);
            return Excel::download(new VideoArticleExport($gameArticles), 'video-article-export.xlsx');
        }

        if(!$export){
            $gameArticles =  $gameArticlesQuery->paginate($paginateSize);
            //dd($gameArticles);
            return view('game-articles-report', [
                'gameArticles' => $gameArticles
            ]);
        }
    }

}

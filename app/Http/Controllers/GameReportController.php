<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Games\GamesExport;
use App\Exports\Games\MostPlayedGamesExport;
use App\Exports\Games\RepeatedGamesExport;


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

}

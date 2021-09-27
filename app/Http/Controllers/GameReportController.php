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
        $game_content = DB::connection('mysql2')->table('game_played_content')
        ->join('game_content', 'game_content.id', '=','game_played_content.game_id')
        ->join('users', 'users.id', '=', 'game_played_content.user_id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->select('users.firstname', 'users.lastname', 'game_name', 'sub_categories.name as category_name', 'game_played_content.created_at')->get();
        
        return view('dashboard', ['game_contents' => $game_content]);
    }

    public function exportGameContent(Type $var = null)
    {
           return Excel::download(new GamesExport, 'GameContent.xlsx');

    }

    public function repeatedGameBySingleUser(Type $var = null)
    {
        $repeated_game = DB::connection('mysql2')->table('game_played_content')
        ->join('game_content', 'game_played_content.game_id','=', 'game_content.id')
        ->join('users', 'game_played_content.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->select(DB::raw("game_played_content.user_id, game_played_content.game_id, firstname, lastname, game_name, sub_categories.name as category_name,COUNT(*)"))
        ->groupBy('game_played_content.user_id','game_played_content.game_id')
        ->havingRaw("COUNT(*) > 1")->get();

        return view('dashboard', ['repeated_games' => $repeated_game]);
    }

    public function exportRepeatedGameBySingleUser()
    {
        return Excel::download(new RepeatedGamesExport, 'RepeatedGames.xlsx');
    }

    public function mostPlayedGames(Type $var = null)
    {
        $most_played_games = DB::connection('mysql2')->table('game_played_content')
        ->join('game_content', 'game_played_content.game_id','=', 'game_content.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->select(DB::raw("game_played_content.user_id, game_played_content.game_id, game_name, sub_categories.name as category_name,COUNT(*)"))
        ->groupBy('game_played_content.game_id')
        ->havingRaw("COUNT(*) > 2")->get();

        return view('dashboard', ['most_played_games' => $most_played_games]);
    }

    public function exportMostPlayedGames(Type $var = null)
    {
        return Excel::download(new MostPlayedGamesExport, 'MostPlayedGames.xlsx');
    }

}

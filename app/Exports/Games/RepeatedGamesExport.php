<?php

namespace App\Exports\Games;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Controllers\GameReportController;

class RepeatedGamesExport implements FromCollection, WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $repeated_game = DB::table('game_played_content')
        ->join('game_content', 'game_played_content.game_id','=', 'game_content.id')
        ->join('users', 'game_played_content.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->select(DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name"), 'game_name', 'sub_categories.name as category_name')
        ->groupBy('game_played_content.user_id','game_played_content.game_id')
        ->havingRaw("COUNT(*) > 1")->get();
        
        return $repeated_game;
    }

    public function headings(): array
    {
        return [
            'User',
            'Game',
            'Game Category'
        ];
    }
}
<?php

namespace App\Exports\Games;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Controllers\GameReportController;

class GamesExport implements FromCollection, WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $game_contents = DB::table('game_played_content')
        ->join('game_content', 'game_content.id', '=','game_played_content.game_id')
        ->join('users', 'users.id', '=', 'game_played_content.user_id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->select(DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name"), 'game_name', 'sub_categories.name as category_name', 
        DB::raw('DATE_FORMAT(game_played_content.created_at, "%d-%b-%Y") as played_at'))
        ->get();
        
        return $game_contents;
    }

    public function headings(): array
    {
        return [
            'User',
            'Game',
            'Game Category',
            'Played at'
        ];
    }
}
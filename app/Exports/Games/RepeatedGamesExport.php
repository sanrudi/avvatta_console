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
        $repeated_game = DB::connection('mysql2')->table('user_logs')
        ->join('game_content', 'user_logs.loggable_id','=', 'game_content.id')
        ->join('users', 'user_logs.user_id', '=', 'users.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->select(DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name"), 'game_name', 'sub_categories.name as category_name')
        ->groupBy('user_logs.user_id','user_logs.loggable_id')
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

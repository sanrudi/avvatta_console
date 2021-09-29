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
        $game_contents = DB::connection('mysql2')->table('user_logs')
            ->join('game_content', 'game_content.id', '=','user_logs.loggable_id')
            ->join('users', 'users.id', '=', 'user_logs.user_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
            ->where('sub_cat', 'game')
            ->select(DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name"), 'game_name', 'sub_categories.name as category_name',
            DB::raw('DATE_FORMAT(user_logs.date_time, "%d-%b-%Y") as played_at'))
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

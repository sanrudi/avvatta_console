<?php

namespace App\Exports\Games;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MostPlayedGamesExport implements FromCollection, WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $most_played_games = DB::connection('mysql2')->table('user_logs')
            ->join('game_content', 'user_logs.loggable_id','=', 'game_content.id')
            ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
            ->where('sub_cat', 'game')
            ->select(DB::raw("game_name, sub_categories.name as category_name"))
            ->groupBy('user_logs.loggable_id')
            ->havingRaw("COUNT(*) > 2")->get();

        return $most_played_games;
    }

    public function headings(): array
    {
        return [
            'Game',
            'Game Category'
        ];
    }
}

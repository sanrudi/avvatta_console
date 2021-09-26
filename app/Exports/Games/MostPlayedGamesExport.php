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
        $most_played_games = DB::table('game_played_content')
        ->join('game_content', 'game_played_content.game_id','=', 'game_content.id')
        ->join('sub_categories', 'sub_categories.id', '=', 'game_content.sub_cat_id')
        ->select(DB::raw("game_name, sub_categories.name as category_name"))
        ->groupBy('game_played_content.game_id')
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
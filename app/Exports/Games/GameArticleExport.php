<?php

namespace App\Exports\Games;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\FromCollection;

class GameArticleExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(array $gameArticles)
    {   
        return $this->gameArticles = $gameArticles;     //Inject data 
    }

    public function array(): array
    {
        return $this->gameArticles;
    }

    public function map($gameArticles): array
    {
        return [
            $gameArticles['article'],
            $gameArticles['category'],
            count($gameArticles['watches']),
            count($gameArticles['unique_watches']),
            count($gameArticles['wishlist']),
            $gameArticles['avg'],
            $gameArticles['added_at'],
            $gameArticles['duration']
        ];
    }

    public function headings(): array
    {
        return [
            'Article',
            'Category',
            'Played',
            'Unique Played',
            'Wishlist',
            'Avg',
            'Added At',
            'Duration'
        ];
    }

    public function title(): string
    {
        return 'video articles';
    }

    public function columnFormats(): array
    {
        return [
            'C' => '#,##0',
            'D' => '#,##0',
            'E' => '#,##0',
        ];
    }
}

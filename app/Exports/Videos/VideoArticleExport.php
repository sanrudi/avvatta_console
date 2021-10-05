<?php

namespace App\Exports\Videos;

use App\Models\AvErosNows;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

class VideoArticleExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(array $videoArticles)
    {   
        return $this->videoArticles = $videoArticles;     //Inject data 
    }

    public function array(): array
    {
        return $this->videoArticles;
    }

    public function map($videoArticles): array
    {
        return [
            $videoArticles['article'],
            $videoArticles['category'],
            $videoArticles['provider'],
            count($videoArticles['watches']),
            count($videoArticles['unique_watches']),
            count($videoArticles['wishlist']),
            $videoArticles['avg'],
            $videoArticles['added_at'],
            $videoArticles['duration']
        ];
    }

    public function headings(): array
    {
        return [
            'Article',
            'Category',
            'Provider',
            'Watches',
            'Unique Watches',
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

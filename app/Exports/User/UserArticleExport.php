<?php

namespace App\Exports\User;

use App\Models\AvErosNows;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserArticleExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(array $userArticles)
    {
        return $this->userArticles = $userArticles;     //Inject data
    }

    public function array(): array
    {
        return $this->userArticles;
    }

    public function map($userArticles): array
    {
        return [
            $userArticles['user_name'],
            $userArticles['content_name'],
            $userArticles['type'],
            $userArticles['action'],
            $userArticles['date_time'],
            date('D', strtotime($userArticles['date_time'])),
            $userArticles['device'],
            $userArticles['os']
        ];
    }

    public function headings(): array
    {
        return [
            'User',
            'Content',
            'Activity Type',
            'Activity',
            'Date',
            'Day',
            'Device',
            'OS'
        ];
    }

    public function title(): string
    {
        return 'video articles';
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => '#,##0',
            // 'D' => '#,##0',
            // 'E' => '#,##0',
        ];
    }
}

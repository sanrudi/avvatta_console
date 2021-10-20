<?php

namespace App\Exports\Subscriptions;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Models\UserPayment;

class IdleSubscriptionExport implements  FromArray,WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    protected $userArticles;

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
            $userArticles['username'],
            $userArticles['title'],
            $userArticles['created_at'],
            $userArticles['expiry_at'],
            $userArticles['date_time']
        ];
    }

    public function headings(): array
    {
        return [
            'username',
            'title',
            'created at',
            'expiry at',
            'Activies'
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

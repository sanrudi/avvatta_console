<?php

namespace App\Exports\Error;

use App\Models\AvErosNows;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

class ErosErrorExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(array $erosError)
    {   
        return $this->erosError = $erosError;     //Inject data 
    }

    public function array(): array
    {
        return $this->erosError;
    }

    public function map($erosError): array
    {
        return [
            $erosError['content_id'],
            $erosError['content_type'],
            $erosError['image_type'],
            $erosError['images']
        ];
    }

    public function headings(): array
    {
        return [
            'Content ID',
            'Content Type',
            'Image Type',
            'Images'
        ];
    }

    public function title(): string
    {
        return 'Eros Error Content';
    }

    public function columnFormats(): array
    {
        return [
        ];
    }
}

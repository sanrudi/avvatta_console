<?php

namespace App\Exports\Videos;

use App\Models\AvErosNows;
use Maatwebsite\Excel\Concerns\FromCollection;

class VideoArticleExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AvErosNows::all();
    }
}

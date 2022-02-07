<?php

namespace App\Http\Controllers;

use App\Exports\Error\ErosErrorExport;
use App\Models\AvErosImageMissing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ErosNowErrorReportController extends Controller
{
    public function missingImage(Request $request)
    {
        $export = 0;
        $export = ($request->input('export'))?1:0;
        
        if($export){
            $erosErrorQuery = AvErosImageMissing::select('content_id','content_type','image_type','images');
            $erosError = $erosErrorQuery->get()->toArray();
            return Excel::download(new ErosErrorExport($erosError), 'missing-image-content.xlsx');
        }

        if(!$export){
            return view('eros-missing-content-report');
        }
    }
    
}

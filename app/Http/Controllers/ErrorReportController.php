<?php

namespace App\Http\Controllers;

use App\Exports\User\UserArticleExport;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Models\UserLog;
use App\Models\VideoContent;
use App\Models\AvvattaUser;
use App\Models\SubProfile;
use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ErrorReportController extends Controller
{

    public function errorReport(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";
        $reportFrom="";$startDate="";$endDate="";$multiDate = "";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d H:m:s");
        if($reportFrom != "" && $reportFrom != "custom"){
            $startDate = date('Y-m-d H:i:s', strtotime($today.'-'.$reportFrom.' day'));
        } 
        if($reportFrom == "multiple" && $request->input('multiDate')){
            $multiDate = explode(",",$request->input('multiDate'));
        }

        $errorReportQuery = ErrorLog::orderBy('date_time','desc');
        if($startDate){
            $errorReportQuery->where('date_time', '>=', $startDate);
        }
        if($endDate){
            $errorReportQuery->where('date_time', '<=', $endDate);
        }    
        if($multiDate){
            $errorReportQuery->whereIn(DB::raw("DATE(date_time)"), $multiDate);
        }

        $errorReport = $errorReportQuery->paginate($paginateSize);
        // if($export){
        //     $userArticles = $user_contents;
        //     return Excel::download(new UserArticleExport($userArticles), 'user-article-export.xlsx');
        // }

        return view('error-report')
            ->with([
                'errorReport'=>$errorReport
            ]);
    }
    
}

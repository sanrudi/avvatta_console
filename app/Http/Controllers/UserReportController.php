<?php

namespace App\Http\Controllers;

use App\Exports\User\UserArticleExport;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Models\UserLog;
use App\Models\VideoContent;
use App\Models\AvvattaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class UserReportController extends Controller
{

    public function userReport(Request $request)
    {
        $paginateSize = 20;$export = 0;$report = "erosnow";$reportFrom="";$startDate="";$endDate="";
        $export = ($request->input('export'))?1:0;
        $reportFrom = ($request->input('reportFrom') && ($request->input('reportFrom') != "custom"))?$request->input('reportFrom'):"";
        $startDate = ($request->input('startDate'))?$request->input('startDate'):"";
        $endDate = ($request->input('endDate'))?$request->input('endDate'):"";
        date_default_timezone_set('Africa/Johannesburg');
        $today = date("Y-m-d");
        if($request->input('reportFrom') && ($request->input('reportFrom') != "custom")){
            $startDate = date('Y-m-d', strtotime($today.'-'.$reportFrom.' day'));
        }
        $user_contents = [];
        switch ($request->input('type'))
        {
            case "game":
                $userReport = UserLog::where('category', '=', 'game');
                break;
            case "erosnow":
                $userReport = UserLog::where('category', '=', 'erosnow');
                break;
            case "kids":
                $userReport = UserLog::where('category', '=', 'kids');
                break;
            default:
                $userReport = UserLog::select('user_id', 'loggable_id', 'content_id', 'type', 'category', 'action', 'date_time');
        }
        if($startDate && $request->input('reportFrom') != "custom"){
            $userReport->whereBetween('date_time', [$startDate, $today]);
        } elseif($request->input('reportFrom') == "custom") {
            $userReport->whereBetween('date_time', [$startDate, $endDate]);
        }
        $i = 1;
        foreach ($userReport->get() as $user)
        {
            switch ($user->category) {
                case "kids":
                    $content_name = VideoContent::where('id', $user->loggable_id)
                        ->value('content_name');
                    break;
                case "game":
                    $content_name = GameContent::where('id', $user->loggable_id)->value('game_name');
                    break;
                case "erosnow":
                    $content_name = AvErosNows::where('content_id', $user->content_id)->value('title');
                    break;
                default:
                    //
            }
            $user_contents[$i]['id'] = $user->id;
            $user_contents[$i]['user_name'] = AvvattaUser::where('id', $user->user_id)->value('firstname').' '.AvvattaUser::where('id', $user->user_id)->value('lastname');
            $user_contents[$i]['content_name'] = $content_name;
            $user_contents[$i]['type'] = $user->type;
            $user_contents[$i]['action'] = $user->action;
            $user_contents[$i]['date_time'] = $user->date_time;
            $i++;
        }

        if($export){
            $userArticles = $user_contents;
            return Excel::download(new UserArticleExport($userArticles), 'user-article-export.xlsx');
        }

        return view('user-report')
            ->with([
                'user_contents'=>$user_contents
            ]);
    }

}

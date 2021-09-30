<?php

namespace App\Http\Controllers;

use App\Models\GameContent;
use App\Models\UserLog;
use App\Models\VideoContent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class UserReportController extends Controller
{

    public function userReport()
    {
        $user_contents = [];
        $userReport = UserLog::all();
        $i = 1;
        foreach ($userReport as $user)
        {
            $user_contents[$i]['id'] = $user->id;
            $user_contents[$i]['user_name'] = User::where('id', $user->user_id)->value('firstname').' '.User::where('id', $user->user_id)->value('lastname');
            $user_contents[$i]['content_name'] = $user->type == "video" ? VideoContent::where('id', $user->loggable_id)->value('content_name') : GameContent::
                where('id', $user->loggable_id)->value('game_name');
            $user_contents[$i]['type'] = $user->type;
            $user_contents[$i]['action'] = $user->action;
            $user_contents[$i]['date_time'] = $user->date_time;
            $i++;
        }
        return view('user-report')
            ->with([
                'user_contents'=>$user_contents
            ]);


    }

}

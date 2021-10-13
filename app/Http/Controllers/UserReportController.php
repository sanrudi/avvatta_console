<?php

namespace App\Http\Controllers;

use App\Exports\User\UserArticleExport;
use App\Models\AvErosNows;
use App\Models\GameContent;
use App\Models\UserLog;
use App\Models\VideoContent;
use App\Models\AvvattaUser;
use App\Models\SubProfile;
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
        $userReport->orderBy('date_time','desc');
        $i = 1;
        $userPageData = $userReport->paginate($paginateSize);
        foreach ($userPageData as $user)
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
            $user_contents[$i]['content_name'] = isset($content_name)?$content_name:"";
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
                'user_contents'=>$user_contents,
                'userPageData' => $userPageData
            ]);
    }

    public function userRegistrationReport(Request $request)
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

        // Registered User By Monthly
        $registerUsersMonthlyQuery = AvvattaUser::select(DB::raw('YEAR(created_at) year, MONTH(created_at) month'),DB::raw('count(Date(created_at)) as count'));
        $registerUsersMonthlyQuery->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'));
        $registerUsersMonthlyQuery->orderBy('created_at','desc');
        $registerUsersMonthly = $registerUsersMonthlyQuery->get();
        
        // Registered User By Daily
        $registerUsersDailyQuery = AvvattaUser::select(DB::raw('Date(created_at) as date, count(Date(created_at)) as count'));
        $registerUsersDailyQuery->groupBy(DB::raw('Date(created_at)'));
        $registerUsersDailyQuery->orderBy('created_at','desc');
        $registerUsersDaily = $registerUsersDailyQuery->get();

        // signed-up on web 
        $signedupQuery = AvvattaUser::select(DB::raw('Date(created_at) as date, count(Date(created_at)) as count'));
        $signedupQuery->where('device_type','=','web');
        $signedupQuery->groupBy(DB::raw('Date(created_at)'));
        $signedupQuery->orderBy('created_at','desc');
        $signedUpOnWeb = $signedupQuery->get();
        
        return view('user-registration-report')
            ->with([
                'registerUsersMonthly'=>$registerUsersMonthly,
                'registerUsersDaily'=>$registerUsersDaily,
                'signedUpOnWeb'=>$signedUpOnWeb,
            ]);
    }

    public function userLoginReport(Request $request)
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

        // Logged in User By Monthly
        $loggedInUsersMonthlyQuery = UserLog::where('type', '=', 'user');
        $loggedInUsersMonthlyQuery->where('action', '=', 'login');
        $loggedInUsersMonthlyQuery->groupBy(DB::raw('YEAR(date_time)'), DB::raw('MONTH(date_time)'));
        $loggedInUsersMonthlyQuery->select(DB::raw('YEAR(date_time) year, MONTH(date_time) month'),DB::raw('count(Date(date_time)) as count'));
        $loggedInUsersMonthly = $loggedInUsersMonthlyQuery->get();
        
        // Logged in User By Daily
        $loggedInUsersQuery = UserLog::where('type', '=', 'user');
        $loggedInUsersQuery->where('action', '=', 'login');
        $loggedInUsersQuery->groupBy(DB::raw('Date(date_time)'));
        $loggedInUsersQuery->select(DB::raw('Date(date_time) as date, count(Date(date_time)) as count'));
        $loggedInUsersDaily = $loggedInUsersQuery->get();

        $subProfileQuery = SubProfile::join('users', 'users.id', '=', 'sub_profiles.user_id');
        $subProfileQuery->groupBy('sub_profiles.user_id');
        $subProfileQuery->select('users.*',DB::raw('count(sub_profiles.user_id) as count'));
        $subProfile = $subProfileQuery->get();
        
        
        return view('user-login-report')
            ->with([
                'loggedInUsersMonthly'=>$loggedInUsersMonthly,
                'loggedInUsersDaily'=>$loggedInUsersDaily,
            ]);
    }

    public function userSubProfileReport(Request $request)
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

        $subProfileQuery = SubProfile::join('users', 'users.id', '=', 'sub_profiles.user_id');
        $subProfileQuery->groupBy('sub_profiles.user_id');
        $subProfileQuery->select('users.*',DB::raw('count(sub_profiles.user_id) as count'));
        $subProfileQuery->orderBy('count','desc');
        $subProfile = $subProfileQuery->get();
        
        return view('user-sub-profile-report')
            ->with([
                'subProfile'=>$subProfile,
            ]);
    }
    
}

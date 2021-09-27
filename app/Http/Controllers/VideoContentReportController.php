<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\UserLog;
use App\Models\AvEosNows;
use App\Models\GameContent;

class VideoContentReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $userLogs = UserLog::all();
        // print_r($userLogs);
        // foreach($userLogs as $userLog){
        //     if($userLog->loggable_type != ""){
        //         print_r($userLog->loggable);
        //     }
        // }
    }
}

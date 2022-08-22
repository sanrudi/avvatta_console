<?php

namespace App\Http\Controllers;

#use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DateTime;
use DateTimeZone;

class Controller extends BaseController
{
    # use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use DispatchesJobs, ValidatesRequests;

    //this function convert string to UTC time zone
    public function convertTimeToZone($str, $format = 'Y-m-d H:i:s') {
       $tzFromZone = config('global.TIMEZONEFROM');
       $tzToZone = config('global.TIMEZONETO');
        if(empty($str)){
            return '';
        }
        try{
            $new_str = new DateTime($str, new DateTimeZone($tzFromZone));
            $new_str->setTimeZone(new DateTimeZone($tzToZone));
            
        }
        catch(\Exception $e) {
            print_r( $e); die;
        }
        return $new_str->format( $format);
    }
    //this function converts string from UTC time zone to current user timezone
    public function convertTimeFromZone($str, $format = 'Y-m-d H:i:s') {
        $tzFromZone = config('global.TIMEZONEFROM');
        $tzToZone = config('global.TIMEZONETO');
        if(empty($str)){
            return '';
        }
        try{
            $new_str = new DateTime($str, new DateTimeZone($tzToZone));
            $new_str->setTimeZone(new DateTimeZone($tzFromZone ));
        }
        catch(\Exception $e) {
            // Do Nothing
        }
        return $new_str->format( $format);
    }
}

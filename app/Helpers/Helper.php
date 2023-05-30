<?php

namespace App\Helpers;

class Helper{
    public static  function secondsToHumanReadable(int $duration): string {
        $secondsOfMinute=60;
        $secondsOfHour=3600;
        $secondsOfDay=86400;
        
        $days=floor($duration/$secondsOfDay);
        $duration%=$secondsOfDay;
        
        $hours=floor($duration/$secondsOfHour);
        $duration%=$secondsOfHour;
        
        $minutes=floor($duration/$secondsOfMinute);
        
        $result="";
        
        $result .= $days > 1 ? "days": "d";
        $result .= $hours > 1 ? "hrs": "hr";
        $result .= $minutes > 1 ? "mins": "min";

        return $result;
    }
}
<?php

namespace App\Helpers;

class Helper{
    public static function secondsToHumanReadable($duration) {
        $secondsOfMinute=60;
        $secondsOfHour=3600;
        $secondsOfDay=86400;
        
        $days=floor($duration/$secondsOfDay);
        $duration%=$secondsOfDay;
        
        $hours=floor($duration/$secondsOfHour);
        $duration%=$secondsOfHour;
        
        $minutes=floor($duration/$secondsOfMinute);
        
        $result="";
        
        switch(true){
            case $days>0:
                $result.= $days. "d ";
                break;
        }
        switch(true){
            case $hours==1:
                $result.= $hours. "hr ";
                break;
            case $hours>1:
                $result.= $hours. "hrs ";
                break;
        }
        switch(true){
            case $minutes==1:
                $result.= $minutes. "min ";
                break;
            case $minutes>1:
                $result.= $minutes. "mins ";
                break;
        }

        return $result;
    }
}
<?php

namespace App\Helpers;

class Helper {
    public static function secondsToHumanReadable($duration) {
        $secondsPerMinute = 60;
        $secondsPerHour = $secondsPerMinute * 60;
        $secondsPerDay = $secondsPerHour * 24;
    
        $days = floor($duration / $secondsPerDay);
        $hours = floor(($duration % $secondsPerDay) / $secondsPerHour);
        $minutes = floor(($duration % $secondsPerHour) / $secondsPerMinute);
    
        $result = '';
        if ($days > 0) {
            $result .= $days . 'd ';
        }
        if ($hours > 0) {
            $result .= $hours . 'hr ';
        }
        if ($minutes > 0) {
            $result .= $minutes . 'mins';
        }
    
        return trim($result);
    }
}
 echo Helper::secondsToHumanReadable(3600);
 echo Helper::secondsToHumanReadable(1800);
 echo Helper::secondsToHumanReadable(5400);
echo Helper::secondsToHumanReadable(7200);
?>
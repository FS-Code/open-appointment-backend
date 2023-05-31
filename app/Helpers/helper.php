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
            $result .= $days . ($days == 1 ? 'd ' : 'days ');
        }
        if ($hours > 0) {
            $result .= $hours . ($hours == 1 ? 'hr ' : 'hrs ');
        }
        if ($minutes > 0) {
            $result .= $minutes . ($minutes == 1 ? 'min' : 'mins');
        }
    
        return trim($result);
    }
}

?>

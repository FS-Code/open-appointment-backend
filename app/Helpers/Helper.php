<?php

namespace App\Helpers;

class Helper {
    public static function secondsToHumanReadable(int $duration): string {
        $secondsOfMinute = 60;
        $secondsOfHour = 3600;
        $secondsOfDay = 86400;
    
        if ($duration <= 0) {
            throw new \InvalidArgumentException("Duration must be a positive integer.");
        }
        
        $days = floor($duration / $secondsOfDay);
        $duration %= $secondsOfDay;
        
        $hours = floor($duration / $secondsOfHour);
        $duration %= $secondsOfHour;
        
        $minutes = floor($duration / $secondsOfMinute);
        
        $result = "";
        
        if ($days > 0) {
            $result .= $days . ($days > 1 ? " days " : " day ");
        }
        
        if ($hours > 0) {
            $result .= $hours . ($hours > 1 ? " hrs " : " hr ");
        }
        
        if ($minutes > 0) {
            $result .= $minutes . ($minutes > 1 ? " mins" : " min");
        }
        
        return trim($result);
    }
}

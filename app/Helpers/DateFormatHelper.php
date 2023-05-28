<?php

namespace App\Helpers;

class DateFormatHelper
{
    public static function secondsToHumanReadable(int $seconds): string
    {
        $day = floor($seconds / (24 * 60 * 60));
        $hour = floor(($seconds % (24 * 60 * 60) / 3600));
        $minute = floor(($seconds % 3600) / 60);;


        return ($day > 0 ? $day . 'd ' : '') . ($hour > 0 ? $hour . 'hrs ': '') . ($minute > 0 ? $minute . 'min ' : '') ;
    }
}






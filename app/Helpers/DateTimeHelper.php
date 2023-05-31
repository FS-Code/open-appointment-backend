<?php

namespace App\Helpers;

class DateTimeHelper
{

    public static function secondsToHumanReadable(int $duration): string
    {
        $res = '';

        if ($duration > 0) {
            $days    = 0;
            $hours   = 0;
            $minutes = 0;

            $days     = floor($duration / 86400);
            $duration -= $days * 86400;
            $hours    = floor($duration / 3600);
            $duration -= $hours * 3600;
            $minutes  = floor($duration / 60);

            if ($days != 0)
                $res .= $days > 1 ? $days . "days " : $days . "d ";

            if ($hours != 0)
                $res .= $hours > 1 ? $hours . "hrs " : $hours . "hr ";

            if ($minutes != 0)
                $res .= $minutes > 1 ? $minutes . "mins" : $minutes . "min";

        }

        return $res;

    }
}
?>
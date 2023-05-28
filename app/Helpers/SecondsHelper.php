<?php

namespace App\Helpers;

class SecondsHelper
{

    public static function secondsToHumanReadabl(int $duration): string
    {
        $res = '';

        if ($duration > 0) {
            $days    = 0;
            $hours   = 0;
            $minutes = 0;
            while ((int) ($duration / 86400) > 0) {
                $days++;
                $duration = $duration - 86400;
            }
            while ((int) ($duration / 3600) > 0) {
                $hours++;
                $duration = $duration - 3600;
            }
            while ((int) ($duration / 60) > 0) {
                $minutes++;
                $duration = $duration - 60;
            }
            switch ($days) {
                case 0:
                    break;
                case 1:
                    $res .= $days . "d";
                    break;
                case $days > 1:
                    $res .= $days . "days";
                    break;
            }
            switch ($hours) {
                case 0:
                    break;
                case 1:
                    $res .= " " . $hours . "hr";
                    break;
                case $hours > 1:
                    $res .= " " . $hours . "hrs";
                    break;
            }
            switch ($minutes) {
                case 0:
                    break;
                case 1:
                    $res .= " " . $minutes . "min";
                    break;
                case $minutes > 1:
                    $res .= " " . $minutes . "mins";
                    break;
            }
        }


        return $res;

    }
}
?>
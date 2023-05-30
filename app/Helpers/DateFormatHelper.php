<?php

namespace App\Helpers;

class DateFormatHelper {
      /**
     * Convert seconds to human readable format
     * @var integer duration
     * @retrun string
     */
    public static function secondsToHumanReadable(int $duration): string
    {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        // $seconds = $duration % 60;
        
        $output = '';
        
        if ($hours > 1) {
            $output .= $hours . " " . 'hrs';
        
        }else{
            $output .= $hours . " " . 'hr';
        }
   
        
        if ($minutes > 0) {
            if ($output !== '') {
                $output .= ' ';
            }
            $output .= $minutes . 'min';
        }
        
        // if ($output === '') {
        //     $output = $seconds . 'sec';
        // }
        
        return $output;
    }
}
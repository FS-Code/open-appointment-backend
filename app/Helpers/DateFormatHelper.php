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
        $days = floor($duration / (24 * 3600)); 
        $hours = floor($duration / (3600 * 60));
        $minutes = floor(($duration % 3600) / 60);
        // $seconds = $duration % 60;
        
        $output = '';

        

        if ($days > 0) {
            $output .= $days . ($days > 1 ? ' days ' : ' day ');
        }

        if ($hours > 0) {
            $output .= $hours . ($hours > 1 ? ' hrs ' : ' hr ');
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
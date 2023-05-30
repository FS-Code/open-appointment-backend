<?php
App\Helpers;

class Helper {
    public static function formatDuration(int $duration): string {
        $days = floor($duration / (24 * 60));
        $hours = floor(($duration % (24 * 60)) / 60);
        $minutes = $duration % 60;

        $formattedDuration = '';

        if ($days > 0) {
            $formattedDuration .= $days . ' days ';
        }

        if ($hours > 0) {
            $formattedDuration .= $hours . ' hours ';
        }

        if ($minutes > 0) {
            $formattedDuration .= $minutes . ' minutes';
        }


        return trim($formattedDuration);
    }
}


?>

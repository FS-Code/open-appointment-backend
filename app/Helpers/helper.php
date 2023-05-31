<?php
namespace App\Helpers;

class Helper {
    public static function formatDuration(int $duration): string {
        $days = floor($duration / (24 * 60));
        $hours = floor(($duration % (24 * 60)) / 60);
        $minutes = $duration % 60;

        $formattedDuration = '';

        if ($days > 0) {
            $formattedDuration .= $days . 'd ';
        }

        if ($hours > 0) {
            $formattedDuration .= $hours . 'h ';
        }

        if ($minutes > 0) {
            $formattedDuration .= $minutes . 'min';
        }

        return trim($formattedDuration);
    }
}


?>
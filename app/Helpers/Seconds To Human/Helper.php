<?php

function secondsToHumanReadable(int $duration): string
{
    $hours = floor($duration / 3600);
    $minutes = floor(($duration % 3600) / 60);
    $seconds = $duration % 60;

    $output = '';

    if ($hours > 0) {
        $output .= $hours . 'hr';
    }

    if ($minutes > 0) {
        if ($output !== '') {
            $output .= ' ';
        }
        $output .= $minutes . 'min';
    }

    if ($seconds > 0 || $output === '') {
        if ($output !== '') {
            $output .= ' ';
        }
        $output .= $seconds . 'sec';
    }

    return $output;
}

echo secondsToHumanReadable(3779);  // * Cavab 1 saat 2 deq 59 san cixacaq
?>

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

    if ($output === '') {
        $output = $seconds . 'sec';
    }

    return $output;
}

echo secondsToHumanReadable(3665); // * Numune cavab 1 saat 1 deq 5 saniye cixacaq
?>

<?php
function secondsToHumanReadable(int $duration): string
{
    $days = floor($duration / (24 * 3600));
    $hours = floor(($duration % (24 * 3600)) / 3600);
    $minutes = floor(($duration % 3600) / 60);
     

    // Burada max day min minute olaraq deyisdirdim
    $output = '';
     
    if ($days > 0) {
        $output .= $days . ' day ';
    }

    if ($hours > 0) {
        $output .= $hours . ' hrs ';
    }
    /*
     Burda deyirsiz 2hr yerine 2hrs olmalidi, tam olaraq burani basa dusmedim.
    */

    if ($minutes > 0 || $output === '') {
        $output .= $minutes . ' min';
    }

    return $output;
}

echo secondsToHumanReadable(166688);
?>


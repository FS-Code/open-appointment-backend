<?php

namespace App\Helpers;

class Helper {

    static function secondsToTime(int $duration) : string {

        //Daxil edilən ədədin gün, saat və dəqiqə formatına çevrilməsi;

        $toDays = (int)($duration / (3600 * 24));
        $toHours = (int)(($duration % (3600 * 24)) / 3600);
        $toMinutes = (int)(($duration % 3600) / 60);
        $time = "";

        //Günlərin sayının əlavə edilməsi (qiymət 0-dan böyükdürsə);

        if ($toDays > 0) {

            $time .= $toDays . "d";

        }

        //Saatların sayının əlavə edilməsi (qiymət 0-dan böyükdürsə);

        if ($toHours > 0) {

            if ($time != "") {

                $time .= " ";

            }

            $time .= $toHours . "hr";

        }

        //Dəqiqələrin sayının əlavə edilməsi (qiymət 0-dan böyükdürsə);

        if ($toMinutes > 0) {

            if ($time != "") {

                $time .= " ";

            }

            $time .= $toMinutes . "min";

        }

        //Daxiletmə olmadıqda mesaj verilməsi;

        if ($time == "") {

            echo "No time is left..";

        }

        return $time . "\n";

    }

}
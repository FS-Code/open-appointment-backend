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

            if ($toDays == 1) {

                $time .= $toDays . "d";

            } else {

                $time .= $toDays . "days";

            }

        }

        //Saatların sayının əlavə edilməsi (qiymət 0-dan böyükdürsə);

        if ($toHours > 0) {

            if ($time != "") {

                $time .= " ";

            }

            if ($toHours == 1) {

                $time .= $toHours . "hr";

            } else {

                $time .= $toHours . "hrs";

            }

        }

        //Dəqiqələrin sayının əlavə edilməsi (qiymət 0-dan böyükdürsə);

        if ($toMinutes > 0) {

            if ($time != "") {

                $time .= " ";

            }

            if ($toMinutes == 1) {

                $time .= $toMinutes . "min";

            } else {

                $time .= $toMinutes . "mins";

            }

        }

        //Daxiletmə olmadıqda mesaj verilməsi;

        if ($time == "") {

            echo "0min";

        }

        return $time;

    }

}
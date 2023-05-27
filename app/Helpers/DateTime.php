<?php

namespace App\Helpers;

class DateTime {

    public static array $result = [];
    public static int $time;
    public static string $options; // = 'd H i s'
    public static string $timeToHuman;

    public static function timeToReadable(int $time, string $options = 'd H i s') : string
    {
        self::$time = $time;
        $options ? self::$options = $options : null;
        return self::execute();
    }

    private static function execute() : string
    {
        self::$result = []; // XƏTA: demək, əgər burda resultu boşaltmasam əvvəlki vaxtları yenidən qaytarır
        
        foreach (self::getOptions() as $op){
            self::time($op);
        }
        return self::$timeToHuman;
    }

    private static function time(string $format) : void
    {
//        $time = intval(gmdate($format, self::$time)); // ikisidə eyni nəticəni qaytarır
        $time = intval(date($format, self::$time));
        if($format == 'd'){$time--;}

        self::$result[] = ($time != 0
            ? $time. ' ' . self::title($format, ($time > 1))
            : null);
        self::$timeToHuman = implode(' ', (array) self::$result);
    }

    private static function getOptions() : array
    {
        return (array) explode(' ', self::$options);
    }

    private static function title(string $format, int $key) : string
    {
        if($format == 'H'){
            return ['hr', 'hrs'][$key];
        }elseif ($format == 'i'){
            return ['min', 'mins'][$key];
        }elseif ($format == 's'){
            return ['sec', 'secs'][$key];
        }elseif ($format == 'd'){
            return ['day', 'days'][$key];
        }
    }

}

/*
 * DateTime::timeToReadable(60*60*24*3), // 3 days
 * DateTime::timeToReadable(60*60*24*1), // 1 day
 * DateTime::timeToReadable(63), // 1 min 3 secs
 * DateTime::timeToReadable(60*4), // 4 mins
 * DateTime::timeToReadable(60*60*5+60), // 5 hrs 1 min
 * DateTime::timeToReadable(60*62*24+2), // 1 day  48 mins 2 secs
 * */


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

    private static function title(string $format, $key) : string
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
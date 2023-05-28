<?php

namespace App\Controllers;

use App\Helpers\DateTime;

class HomeController
{
    public static function index(): string
    {
        echo '<pre>';
        print_r([
            DateTime::timeToReadable(60*60*24*3), // 3 days
            DateTime::timeToReadable(60*60*24*1), // 1 day
            DateTime::timeToReadable(63), // 1 min 3 secs
            DateTime::timeToReadable(60*4), // 4 mins
            DateTime::timeToReadable(60*60*5+60), // 5 hrs 1 min
            DateTime::timeToReadable(60*62*24+2), // 1 day  48 mins 2 secs
            DateTime::timeToReadable(1685228727), // 26 days 23 hrs 5 mins 27 secs
        ]);
        echo '<pre>';
        exit();
            
//        return 'I\'m an index function!';
    }

    public static function test(): string
    {
        return 'this is a test!';
    }

    public static function someData(): array
    {
        return [
            'hey' => 'here\'s some data for you'
        ];
    }
}

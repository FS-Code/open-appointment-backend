<?php

namespace App\Controllers;

use App\Helpers\DateTime;

class HomeController
{
    public static function index(): string
    {
        return[
            DateTime::timeToReadable(60*60*24*3); // 3 days
            DateTime::timeToReadable(60*60*24*1); // 1 day
            DateTime::timeToReadable(63); // 1 min 3 secs
            DateTime::timeToReadable(60*4); // 4 mins
            DateTime::timeToReadable(60*60*5+60); // 5 hrs 1 min
            
        ]
            
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

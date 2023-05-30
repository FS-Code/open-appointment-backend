<?php

namespace App\Controllers;
// use App\Helpers\DateFormatHelper;

class HomeController
{

 
    public static function index(): string
    {
        return 'I\'m an index function!';
        // return DateFormatHelper::secondsToHumanReadable(3650);

      
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
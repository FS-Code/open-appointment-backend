<?php

namespace App\Controllers;

use App\Helpers\DateTime;

class HomeController
{
    public static function index(): string
    {
        return DateTime::timeToReadable(60*60*24*3);
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
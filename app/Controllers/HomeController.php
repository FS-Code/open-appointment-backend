<?php

namespace App\Controllers;

use App\Models\Service;

class HomeController
{
    public static function index()
    {
        return Service::createService('name','location','details',120,
        [
            "monday" => ['start_time' => 2, 'end_time' => 3],
            "tuesday" => null,
            "wednesday" => null,
            "thursday" => null,
            "friday" => null,
            "saturday" => null,
            "sunday" => null
        ],
        1,3);
        return 'I\'m an index function!';
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
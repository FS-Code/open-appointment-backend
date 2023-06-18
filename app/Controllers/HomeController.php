<?php

namespace App\Controllers;

class HomeController
{
    public static function index(): string
    {
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
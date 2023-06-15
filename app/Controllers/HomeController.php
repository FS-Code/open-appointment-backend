<?php

namespace App\Controllers;

use App\Models\Buffer;
use App\Models\BusinessHours;
use App\Models\Service;

class HomeController
{
    public static function index(): string
    {
        return 'I\'m an index function!';
    }
}
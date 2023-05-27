<?php

namespace App\Core;

class App
{
    public static function run(): void
    {
        //initializes environment variables
        Env::init();

        //initializes database connection
//        DB::init();

        //
        Router::resolve();
    }
}
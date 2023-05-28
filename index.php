<?php

define( 'ROOT', __DIR__ ) or die();

require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/api.php';

use App\core\App;

App::run();

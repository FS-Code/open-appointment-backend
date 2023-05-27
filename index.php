<?php

define( 'ROOT', __DIR__ ) or die();

require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/api.php';

use App\Core\App;
use App\Core\Request;

App::run();

$_GET['key']='value';
$_GET['nice']='value';
$_GET['niye']='value';

if(Request::has('get',['key','nice','niye'])){
    echo 'yes';
}
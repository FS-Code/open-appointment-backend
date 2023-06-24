<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Helpers\AuthHelper;
use App\Models\Settings;
use Exception;

class SettingController{
    public static function saveSettings(){
        $settings = Request::body();
        $user_id = Request::$user->getId();

        if(empty($settings)){
            Response::setStatusBadRequest();
            return;
        }

        foreach ($settings as $key => $value) {
            Settings::set($user_id,$key,$value);
        }

        Response::setStatusOk();
        return;
    }
}


?>
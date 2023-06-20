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
        $jwt = Request::cookie("auth_token");
        $user_id = null;

        try {
            $payload = AuthHelper::readJWT($jwt);
            if(is_array($payload) && array_key_exists("user_id",$payload)){
                $user_id = $payload["user_id"];
            }else{
                throw new Exception("Invalid payload or jwt token");
            }
            
        } catch (Exception $e) {
            Response::setStatusBadRequest();
            return ['error' => $e->getMessage()];
        }

        if(empty($settings)){
            throw new Exception("There are no settings");
        }

        foreach ($settings as $key => $value) {
            Settings::set($user_id,$key,$value);
        }

        Response::setStatusOk();

        return;
    }
}


?>
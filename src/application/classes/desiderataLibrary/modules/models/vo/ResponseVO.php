<?php
class desiderataLibrary_modules_models_vo_ResponseVO
{
    public static function OK()
    {
        return array(
            'http-status' => 200,
            'message' => 'OK'
        );
    }
}
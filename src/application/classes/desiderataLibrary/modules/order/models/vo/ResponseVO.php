<?php
class desiderataLibrary_modules_order_models_vo_ResponseVO
{
    public static function OK($url)
    {
        return array(
            'http-status' => 200,
            'url' => $url
        );
    }
}
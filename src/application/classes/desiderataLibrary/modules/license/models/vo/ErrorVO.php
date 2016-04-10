<?php
class desiderataLibrary_modules_license_models_vo_ErrorVO
{
    public $httpStatus;
    public $message;

    function __construct($httpStatus, $message)
    {
        $this->httpStatus = $httpStatus;
        $this->message = $message;
    }

    public static function wrongCode()
    {
        return new self('400', 'Wrong code');
    }

    public static function alreadyUsed()
    {
        return new self('400', 'Code already used');
    }
}

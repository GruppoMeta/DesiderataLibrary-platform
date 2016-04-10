<?php
class desiderataLibrary_modules_models_vo_ErrorVO
{
    public static function MissingRequiredParameters()
    {
        return array(
            'http-status' => 400,
            'message' => 'Invalid request: missing required parameters'
        );
    }

    public static function Unauthorized()
    {
        return array(
            'http-status' => 401,
            'message' => 'Unauthorized'
        );
    }

    public static function InvalidUsernamePassword()
    {
        return array(
            'http-status' => 401,
            'message' => 'Invalid username/password supplied'
        );
    }

    public static function Forbidden()
    {
        return array(
            'http-status' => 403,
            'message' => 'Forbidden'
        );
    }

    public static function NotFound()
    {
        return array(
            'http-status' => 404,
            'message' => 'Not found'
        );
    }

    public static function UserAlreadyExists($message, $code)
    {
        return array(
            'http-status' => 409,
            'message' => $message,
            'code' => $code,
        );
    }

    public static function UsernameAlreadyExists()
    {
        return self::UserAlreadyExists('User already exists', -100);
    }

    public static function UserEmailAlreadyExists()
    {
        return self::UserAlreadyExists('Email already exists', -101);
    }

    public static function InternalServerError($message='Internal server error')
    {
        return array(
            'http-status' => 500,
            'message' => $message
        );
    }
}

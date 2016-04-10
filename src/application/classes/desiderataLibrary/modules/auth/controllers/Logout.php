<?php
class desiderataLibrary_modules_auth_controllers_Logout extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        $result = array();
        $authClass = org_glizy_ObjectFactory::createObject(__Config::get('glizy.authentication'));
        $user = $authClass->logout();
        $result['message'] = 'Successfully logged out';
        return $result;
    }
}

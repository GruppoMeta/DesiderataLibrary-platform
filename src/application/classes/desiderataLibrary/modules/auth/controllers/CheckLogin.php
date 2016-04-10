<?php
class desiderataLibrary_modules_auth_controllers_CheckLogin extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            return desiderataLibrary_modules_models_vo_ResponseVO::OK();
        }

        return $result;
    }
}

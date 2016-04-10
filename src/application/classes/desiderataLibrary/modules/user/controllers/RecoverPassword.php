<?php
class desiderataLibrary_modules_user_controllers_RecoverPassword extends org_glizy_rest_core_CommandRest
{
    function execute($email)
    {
        $result = array();

        $ar = org_glizy_ObjectFactory::createModel('org.glizy.models.User');
        if (!$ar->find(array('user_email' => $email))) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
        } else if ($email) {
            $ar->user_passwordTemp = glz_makePass(8);
            $ar->save();

            $emailInfo = org_glizy_helpers_Mail::getEmailInfoStructure();
            $emailInfo[ 'USER' ] = $ar->user_loginId;
            $emailInfo[ 'PASSWORD' ] = $ar->user_passwordTemp;
            $emailInfo[ 'EMAIL' ] = $email;
            org_glizy_helpers_Mail::sendEmailFromTemplate( 'recuperaPassword', $emailInfo );

            return desiderataLibrary_modules_models_vo_ResponseVO::OK();
        } else {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        }

        return $result;
    }
}
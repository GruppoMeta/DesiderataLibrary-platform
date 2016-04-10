<?php
class desiderataLibrary_modules_user_controllers_HelpRequest extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        $body = json_decode(__Request::getBody());

        // salvi i dati della richiesta
        $now = new org_glizy_types_DateTime();
        $userRequest = __ObjectFactory::createModel('desiderataLibrary.modules.user.models.UserRequest');
        $userRequest->user_request_firstName = $body->firstName;
        $userRequest->user_request_lastName = $body->lastName;
        $userRequest->user_request_email = $body->email;
        $userRequest->user_request_bookTitle = $body->bookTitle;
        $userRequest->user_request_description = $body->description;
        $userRequest->user_request_date = (string)$now;
        $userRequest->save();

        $emailInfo = org_glizy_helpers_Mail::getEmailInfoStructure();
        $emailInfo[ 'USER_FIRST_NAME' ] = $body->firstName;
        $emailInfo[ 'USER_LAST_NAME' ] = $body->lastName;
        $emailInfo[ 'USER_EMAIL' ] = $body->email;
        $emailInfo[ 'BOOK' ] = $body->bookTitle;
        $emailInfo[ 'DESCRIPTION' ] = $body->description;
        $emailInfo[ 'EMAIL' ] = $body->email;
        $emailInfo[ 'FIRST_NAME' ] = $body->firstName;
        $emailInfo[ 'LAST_NAME' ] = $body->lastName;
        org_glizy_helpers_Mail::sendEmailFromTemplate( 'assistenzaExternal', $emailInfo );

        $emailInfo[ 'EMAIL' ] = __Config::get('desiderataLibrary.support.email');
        $emailInfo[ 'FIRST_NAME' ] = '';
        $emailInfo[ 'LAST_NAME' ] = '';
        $emailInfo[ 'SENDER' ] = array('email' => $body->email, 'name' => $body->firstName.' '.$body->lastName);
        org_glizy_helpers_Mail::sendEmailFromTemplate( 'assistenzaInternal', $emailInfo );

        return desiderataLibrary_modules_models_vo_ResponseVO::OK();
    }
}
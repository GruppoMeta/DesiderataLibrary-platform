<?php
class desiderataLibrary_modules_user_controllers_Register extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        $body = json_decode(__Request::getBody());

        // controllo validità dei campi
        if (!$body->firstName ||
            !$body->lastName ||
            !$body->email ||
            !$body->username ||
            !$body->password) {
            return desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        }

        // controlla se l'email o l'username sono ggià presenti
        $ar = org_glizy_ObjectFactory::createModel('org.glizy.models.User');
        if ($ar->find(array('user_loginId' => $body->username))) {
            return desiderataLibrary_modules_models_vo_ErrorVO::UsernameAlreadyExists();
        } else if ($ar->find(array('user_email' => $body->email))) {
            return desiderataLibrary_modules_models_vo_ErrorVO::UserEmailAlreadyExists();
        }

        $ar->user_FK_usergroup_id = __Config::get('USER_DEFAULT_USERGROUP');
        $ar->user_isActive = true;
        $ar->user_firstName	= $body->firstName;
        $ar->user_lastName = $body->lastName;
        $ar->user_email = $body->email;
        $ar->user_loginId = $body->username;
        $ar->user_password = glz_password($body->password);
        $ar->user_age = $body->age;
        $ar->user_city = $body->city;
        $ar->user_interests = $body->interests;
        $ar->user_qualification = $body->qualification;
        $ar->user_profession = $body->profession;
        $ar->user_dateCreation = new org_glizy_types_DateTime();
        $ar->save();

        $result = array(
            'id' => $ar->getId(),
            'firstName' => $ar->user_firstName,
            'lastName' => $ar->user_lastName,
            'loginId' => $ar->user_loginId,
            'email' => $ar->user_email,
            'age' => $ar->user_age,
            'city' => $ar->user_city,
            'interests' => $ar->user_interests,
            'qualification' => $ar->user_qualification,
            'profession' => $ar->user_profession,
            'groupId' => $ar->user_FK_usergroup_id
        );

        $emailInfo = org_glizy_helpers_Mail::getEmailInfoStructure();
        $emailInfo[ 'EMAIL' ] = $ar->user_email;
        $emailInfo[ 'FIRST_NAME' ] = $ar->user_firstName;
        $emailInfo[ 'LAST_NAME' ] = $ar->user_lastName;
        $emailInfo[ 'LOGIN_ID' ] = $ar->user_loginId;
        $emailInfo[ 'PASSWORD' ] = $body->password;
        org_glizy_helpers_Mail::sendEmailFromTemplate( 'registrazione', $emailInfo );

        gruppometa_easybook_EasybookFE::trackEvent('registration', 'success');
        return $result;
    }
}
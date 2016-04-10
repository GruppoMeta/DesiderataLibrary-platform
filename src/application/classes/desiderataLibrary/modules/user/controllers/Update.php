<?php
class desiderataLibrary_modules_user_controllers_Update extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        $result = array();

        if (!$this->user->isLogged()) {
            return desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        }

            $body = json_decode(__Request::getBody());

         // controllo validità dei campi
        if (!$body->firstName ||
            !$body->lastName ||
            !$body->email ||
            !$body->username ||
            !$body->password) {
            return desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        }

            $ar = org_glizy_ObjectFactory::createModel('org.glizy.models.User');
            $ar->load($this->user->id);
            $ar->user_firstName	= $body->firstName;
            $ar->user_lastName = $body->lastName;
            $ar->user_email = $body->email;
            if ($body->password) $ar->user_password = glz_password($body->password);
            $ar->user_age = $body->age;
            $ar->user_city = $body->city;
            $ar->user_interests = $body->interests;
            $ar->user_qualification = $body->qualification;
            $ar->user_profession = $body->profession;
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

        return $result;
    }
}
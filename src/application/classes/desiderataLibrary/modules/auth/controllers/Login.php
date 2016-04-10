<?php
class desiderataLibrary_modules_auth_controllers_Login extends org_glizy_rest_core_CommandRest
{
    private $licenseService;

    function __construct( $application=NULL )
    {
        parent::__construct($application);
        $this->licenseService = __ObjectFactory::createObject('desiderataLibrary.modules.license.service.LicenseService');
    }


    function execute($username, $password)
    {
        $result = array();

        if (!$username || !$password) {
            gruppometa_easybook_EasybookFE::trackEvent('login', 'error');
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $authClass = org_glizy_ObjectFactory::createObject(__Config::get('glizy.authentication'));
            try {
                $user = $authClass->login($username, $password, false);
                $result = $user;

                $ar = org_glizy_ObjectFactory::createModel('org.glizy.models.User');
                $ar->load($user['id']);
                $result['age'] = $ar->user_age;
                $result['city'] = $ar->user_city;
                $result['interests'] = $ar->user_interests;
                $result['qualification'] = $ar->user_qualification;
                $result['profession'] = $ar->user_profession;

                // resetta le licenze così da forzare il caricamento
                gruppometa_easybook_EasybookFE::resetLicenses();

                gruppometa_easybook_EasybookFE::trackEvent('login', 'success');
            } catch(org_glizy_authentication_AuthenticationException $e) {
                gruppometa_easybook_EasybookFE::trackEvent('login', 'error');
                $result = desiderataLibrary_modules_models_vo_ErrorVO::InvalidUsernamePassword();
            }
        }

        return $result;
    }
}

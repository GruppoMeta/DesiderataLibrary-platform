<?php
class gruppometa_easybook_EasybookFE
{
    public static function getLanguage()
    {
        return 1;
    }

    public static function isBackdoorUser($user)
    {
        return $user && $user->groupId==1;
    }

    public static function isFolder($node, $subContent=false)
    {
        return strpos($node->pageType, 'Empty')===0;
    }


    public static function resetLicenses()
    {
        __Session::set('user.licenses', null);
    }

    public static function setLicenses($licenses)
    {
        __Session::set('user.licenses', $licenses);
    }

    public static function getLicenses()
    {
        $licenses = __Session::get('user.licenses', null);
        if ($licenses===null) {
            $user = org_glizy_ObjectValues::get('org.glizy', 'user');
            $licenseService = __ObjectFactory::createObject('desiderataLibrary.modules.license.service.LicenseService');
            $licenses = $licenseService->getLicensesForLoggedUser();
            gruppometa_easybook_EasybookFE::setLicenses($licenses);
        }

        return $licenses;
    }

    public static function checkLicenseForMedia($media)
    {
        $allow = true;
        $user = org_glizy_ObjectValues::get('org.glizy', 'user');
        $allow = $user->isLogged();

        if ($allow) {
            $licenses = self::getLicenses();
            $allow = in_array($media->ar->media_FK_site_id, $licenses);
        }

        if (!$allow) {
          header( $_SERVER['SERVER_PROTOCOL'].' 403 Forbidden' );
          echo "<h1>403 Forbidden</h1>";
          exit();
        }
    }


    public static function trackEvent($category, $action, $publicationId=null)
    {
        $now = new org_glizy_types_DateTime();
        $ar = org_glizy_ObjectFactory::createModel('gruppometa.easybook.models.EventStat');
        $ar->eventStats_nomeEvento = $category;
        $ar->eventStats_datetime = $now->__toString();
        $ar->eventStats_parametro = $action;
        $ar->eventStats_idPubblicazione = $publicationId;
        $ar->save();
    }

    public static function checkExportPluginRequest()
    {
        $headers = getallheaders();
        $backdoorKey = __Config::get('desiderataLibrary.plugins.offline.backdoor.key');
        $backdoorValue = __Config::get('desiderataLibrary.plugins.offline.backdoor.value');
        if (isset($headers[$backdoorKey]) && $headers[$backdoorKey]===$backdoorValue) {
            // logga in automatico
            $ar = org_glizy_ObjectFactory::createModel('org.glizy.models.User');
            $ar->find(array('user_FK_usergroup_id' => 1));
            $user = array(  'id' => $ar->user_id,
                            'firstName' => $ar->user_firstName,
                            'lastName' => $ar->user_lastName,
                            'loginId' => $ar->user_loginId,
                            'email' => $ar->user_email,
                            'groupId' => $ar->user_FK_usergroup_id,
                            'backEndAccess' => true,
                            'language' => 'it'
                            );

            org_glizy_Session::set('glizy.userLogged', true);
            org_glizy_Session::set('glizy.user', $user);

            $application = __ObjectValues::get('org.glizy', 'application');
            $evt = array('type' => GLZ_EVT_USERLOGIN, 'data' => $user);
            $application->dispatchEvent($evt);
        }
    }
}


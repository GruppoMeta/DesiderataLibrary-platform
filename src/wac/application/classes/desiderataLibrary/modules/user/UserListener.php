<?php
class desiderataLibrary_modules_user_UserListener extends GlizyObject
{
    function __construct()
    {
         $this->addEventListener( GLZ_EVT_AR_INSERT.'@org.glizy.models.User', $this, false, 'userUpdate');
         $this->addEventListener( GLZ_EVT_AR_UPDATE.'@org.glizy.models.User', $this, false, 'userUpdate');
    }

    public function userUpdate($event)
    {
        // chiama il servizio di importazione del recommender
        $url = __Config::get('desiderataLibrary.recommender.host').'/recommender/import';
        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'GET');
        $request->execute();
    }
}
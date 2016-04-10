<?php
class desiderataLibrary_modules_license_controllers_BurnCode extends org_glizy_rest_core_CommandRest
{
    function execute($code)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {

            // verifica il codice
            $licenseService = __ObjectFactory::createObject('desiderataLibrary.modules.license.service.LicenseService');
            $result = $licenseService->burnCode($code, $this->user->id);
            if ($result===true) {
                return $this->application->executeCommand('gruppometa.easybook.controllers.GetLibrary');
            }
        }

        return $result;
    }
}
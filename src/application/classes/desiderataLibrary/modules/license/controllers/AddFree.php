<?php
class desiderataLibrary_modules_license_controllers_AddFree extends org_glizy_rest_core_CommandRest
{
    function execute($id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$id) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $licenseService = __ObjectFactory::createObject('desiderataLibrary.modules.license.service.LicenseService');
            // TODO verificare che il titolo sia gratuito
            $result = $licenseService->addLicense($id, $this->user->id);

            if ($result===true) {
                return $this->application->executeCommand('gruppometa.easybook.controllers.GetLibrary');
            }
        }

        return $result;
    }
}
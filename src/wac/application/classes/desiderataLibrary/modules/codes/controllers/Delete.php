<?php
class desiderataLibrary_modules_codes_controllers_Delete extends org_glizy_mvc_core_Command
{
    public function execute($id)
    {
        if ($id) {
            $licenseProxy = org_glizy_objectFactory::createObject('desiderataLibrary.modules.codes.models.proxy.Code');
            $licenseProxy->deleteGroup($id);
        }

        org_glizy_helpers_Navigation::goHere();
    }
}
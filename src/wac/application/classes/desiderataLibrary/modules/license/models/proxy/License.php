<?php
class desiderataLibrary_modules_license_models_proxy_License
{
    public function getLicensesForUser($userId)
    {
        return $this->getLicenses($userId, null);
    }

    public function getLicensesForLibrary($libraryId)
    {
        return $this->getLicenses(null, $libraryId);
    }

    public function addLicenseToUser($userId, $licenses)
    {
        $this->addLicense($userId, null, $licenses);
    }

    public function addLicenseToLibrary($libraryId, $licenses)
    {
        $this->addLicense(null, $libraryId, $licenses);
    }

    private function getLicenses($userId, $libraryId)
    {
        $result = array();

        if ($userId || $libraryId) {
            $it = org_glizy_ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.Model')
                ->load('licenses', array('userId' => $userId, 'libraryId' => $libraryId));

            foreach($it as $ar) {
                $result[] = array(
                    'id' => $ar->menu_id,
                    'text' => $ar->menudetail_title,
                    'licenseId' => $ar->license_id,
                    'publisherId' => (int)$ar->publisherId,
                );
            }
        }

        return $result;
    }


    private function addLicense($userId, $libraryId, $licenses)
    {
        $licensesId = array();
        foreach($licenses as $l) {
            $licensesId[] = $l->id;
        }

        // verifica se ci sono delle license da cancellare
        $it = org_glizy_ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.Model')
            ->load('licenses', array('userId' => $userId, 'libraryId' => $libraryId));
        foreach($it as $ar) {
            if (!in_array($ar->license_id, $licensesId))
            $ar->delete();
        }

        // aggiunge le nuove license
        $ar = org_glizy_ObjectFactory::createModel('desiderataLibrary.modules.license.models.Model');
        foreach($licenses as $l) {
            $ar->emptyRecord();
            $ar->license_startDate = new org_glizy_types_Date();
            $ar->license_FK_menu_id = $l->id;
            $ar->license_FK_user_id = $userId ? : 0;
            $ar->license_FK_library_id = $libraryId ? : 0;
            $ar->save();
        }
    }
}

<?php
class desiderataLibrary_modules_license_service_LicenseService extends GlizyObject
{
    /**
     * Restituisce le licenze dell'utente loggato
     * Considera anche eventuali licenze per IP
     * @return array licenze
     */
    public function getLicensesForLoggedUser()
    {
        $user = org_glizy_ObjectValues::get('org.glizy', 'user');
        if (!$user->isLogged()) {
            return array();
        }

        $licenses = $this->getLicenseForUser($user);

        $ip = $_SERVER['REMOTE_ADDR'];
        $licensesIp = $this->getLicenseForIP($ip);
        if (count($licensesIp)) {
            $licenses = array_merge($licenses, $licensesIp);
        }

        return $licenses;
    }

    /**
     * Restituisce le licenze di un utente,
     * considerando anceh i casi dei gruppi particolari
     * @param  $user Oggetto User di Glizy
     * @return array licenze
     */
    public function getLicenseForUser($user)
    {

        if (gruppometa_easybook_EasybookFE::isBackdoorUser($user)) {
            $field = 'menu_id';
            $it = __ObjectFactory::createModelIterator('gruppometa.easybook.models.Publication')
                    ->load('getPublications');
        } else if ($user->groupId==2 || $user->groupId==7) {
            // carica l'id dell'editore
            $arUser = __ObjectFactory::createModel('org.glizy.models.User');
            $arUser->load($user->id);
            $field = 'menu_id';
            $it = __ObjectFactory::createModelIterator('gruppometa.easybook.models.Publication')
                    ->load('getPublicationsForEditors', array('publisherId' => $arUser->user_FK_editor_id));
        } else {
            $field = 'license_FK_menu_id';
            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.License')
                    ->load('licenses', array('userId' => $user->id));
        }

        $result = array();
        foreach($it as $ar) {
            $result[] = $ar->{$field};
        }

        return $result;
    }

    /**
     * Restituisce le licenze di una biblioteca
     * @param  $libraryId ID della biblioteca
     * @return array licenze
     */
    public function getLicenseForLibrary($libraryId)
    {
        $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.License')
                ->load('licenses', array('libraryId' => $libraryId));

        $result = array();
        foreach($it as $ar) {
            $result[] = $ar->license_FK_menu_id;
        }

        return $result;
    }

    /**
     * Restituisce le licenze per IP
     * @param  string $ip
     * @return array licenze
     */
    public function getLicenseForIP($ip)
    {
        $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.Library')
                    ->load('allWithIp');
        foreach($it as $ar) {
            if ($this->checkIp($ar->library_ip, $ip)) {
                return $this->getLicenseForLibrary($ar->library_id);
            }
        }
        return array();
    }

    /**
     * Bruciatura di un codice
     * @param  string $code   Codice da bruciare
     * @param  int $userId  Id dell'uetnte
     * @return boolean|desiderataLibrary_modules_license_models_vo_ErrorVO
     */
    public function burnCode($code, $userId)
    {
        GlizyClassLoader::addLib('Hashids', __Paths::get('APPLICATION_LIBS').'Hashids');
        $hashids = new Hashids\Hashids(__Config::get('desiderata.hashids.salt'), 0, 'abcdefghijklmnopqrstuvwxyz1234567890');
        $result = $hashids->decode($code);
        if (count($result)==3) {
            $arCodeGroup = __ObjectFactory::createModel('desiderataLibrary.modules.license.models.CodeGroup');
            $arCode = __ObjectFactory::createModel('desiderataLibrary.modules.license.models.Code');
            $r1 = $arCodeGroup->load($result[0]);
            $r2 = $arCode->find(array('code_pos' => $result[1], 'code_FK_codegroup_id' => $result[0]));
            if ($r1 && $r2) {
                if ($arCode->code_status==0) {
                    $arCode->code_status = 1;
                    $arCode->code_FK_user_id = $userId;
                    $arCode->code_burnDate = new org_glizy_types_Date();
                    $arCode->save();

                    // aggiunge le nuove license
                    $newLicenses = glz_maybeJsonDecode($arCodeGroup->codegroup_licenses, false);
                    if (!is_string($newLicenses)) {
                        foreach($newLicenses as $l) {
                            $this->addLicense($l->id, $userId);
                        }
                    }

                    gruppometa_easybook_EasybookFE::trackEvent('burnCode', 'success');
                    return true;
                } else {
                    gruppometa_easybook_EasybookFE::trackEvent('burnCode', 'already-used');
                    return desiderataLibrary_modules_license_models_vo_ErrorVO::alreadyUsed();
                }
            } else {
                gruppometa_easybook_EasybookFE::trackEvent('burnCode', 'not-valid');
                return desiderataLibrary_modules_license_models_vo_ErrorVO::wrongCode();
            }
        }
    }

    public function addLicense($pubId, $userId, $returnId = false)
    {
        // invalida le licenze dell'utente
        gruppometa_easybook_EasybookFE::resetLicenses();

        $ar = org_glizy_ObjectFactory::createModel('desiderataLibrary.modules.license.models.License');
        $ar->license_startDate = new org_glizy_types_Date();
        $ar->license_FK_menu_id = $pubId;
        $ar->license_FK_user_id = $userId;
        $ar->license_FK_library_id = 0;
        $ar->save();
        return $returnId ? $ar->getId() : true;
    }

    private function checkIp( $ipList, $userIp ) {
        if ( $ipList == $userIp) return true;

        $ipList = explode( ",", $ipList );
        $userIp = explode( ".", $userIp );
        $valid = true;
        foreach ( $ipList as $ip ) {
            $ip = explode( ".", $ip );
            for ( $i=0; $i < 4; $i++ ) {
                if ( $ip[ $i ] == $userIp[ $i ] ) {
                    continue;
                } else if ( $ip[ $i ] == "*" ) {
                    continue;
                } else if ( strpos( $ip[ $i ], "-") !== false ) {
                    list( $min, $max ) = explode( "-", $ip[ $i ] );
                    if ( intval( $min ) <= intval( $userIp[ $i ] ) && intval( $max ) >= intval( $userIp[ $i ] ) ) {
                        continue;
                    }
                }
                $valid = false;
                break;
            }

            if ( $valid ) break;
        }
        return $valid;
    }
}
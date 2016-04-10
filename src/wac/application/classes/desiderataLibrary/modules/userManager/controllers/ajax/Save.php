<?php
class desiderataLibrary_modules_userManager_controllers_ajax_Save extends org_glizy_mvc_core_CommandAjax
{
    public function execute($data)
    {
		$this->directOutput = true;

    	$data = json_decode($data);
    	$id = (int)$data->__id;
    	$modelName = $data->__model;

		// controlla se l'email è già nel DB
		$ar = __ObjectFactory::createModel($modelName);
		if ( $ar->find( array( 'user_email' => $data->user_email ) ) ) {
			if ( $id != $ar->user_id ) {
				return array('errors' => array(__T( 'Email presente nel database. Inserire una nuova email' )));
			}
		}
        $ar ->emptyRecord();
        if ( $ar->find( array( 'user_loginId' => $data->user_loginId ) ) ) {
            if ( $id != $ar->user_id ) {
                return array('errors' => array(__T( 'Nome utente presente nel database.' )));
            }
        }

		$password = $data->user_password;
		$password = $password ? glz_password( $password ) : $ar->user_password;
		$data->user_password = $password;
		if ( $id == 0 ) {
			$data->user_dateCreation = new org_glizy_types_DateTime();
		}

        if ($data->user_interests) {
            $data->user_interests = implode(';', $data->user_interests);
        }

        $proxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ActiveRecordProxy');
        $result = $proxy->save($data);

        if ($result['__id']) {
            // aggiunge le licenze
            $licensesOther = property_exists($data, 'licensesOther') ? json_decode($data->licensesOther) : array();
            $licenses = array_merge(
                                    (property_exists($data, 'licenses') ? $data->licenses : array()),
                                    (is_array($licensesOther) ? $licensesOther : array())
                                    );
		    $licenseProxy = org_glizy_objectFactory::createObject('desiderataLibrary.modules.license.models.proxy.License');
		    $licenseProxy->addLicenseToUser($result['__id'], $licenses);
		}

        if ($result['__id']) {
            return array('set' => $result);
        }
        else {
            return array('errors' => $result);
        }
    }
}
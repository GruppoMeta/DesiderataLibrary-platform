<?php
class desiderataLibrary_modules_libraries_controllers_ajax_Save extends org_glizy_mvc_core_CommandAjax
{
    public function execute($data)
    {
		$this->directOutput = true;

    	$data = json_decode($data);

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
            $licenseProxy->addLicenseToLibrary($result['__id'], $licenses);
        }


        if ($result['__id']) {
            return array('set' => $result);
        }
        else {
            return array('errors' => $result);
        }
    }
}
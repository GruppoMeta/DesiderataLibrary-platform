<?php
class desiderataLibrary_modules_codes_controllers_ajax_SaveClose extends desiderataLibrary_modules_libraries_controllers_ajax_Save
{
    function execute($data)
    {
        $this->directOutput = true;

        $data = json_decode($data);
        $id = (int)$data->__id;
        $modelName = $data->__model;
        $data->codegroup_num = (int)$data->codegroup_num;
        $data->codegroup_FK_user_id = $this->user->id;

        if ($data->codegroup_num<1) {
            return array('errors' => array(__T('Numero di codici non valido')));
        }

        $data->codegroup_licenses = json_encode($data->codegroup_licenses);

        if (!$data->codegroup_startDate) {
            $data->codegroup_startDate = new org_glizy_types_Date();
        }

        $proxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ActiveRecordProxy');
        $result = $proxy->save($data);

        if ($result['__id']) {
            // genera i codici
            $licenseProxy = org_glizy_objectFactory::createObject('desiderataLibrary.modules.codes.models.proxy.Code');
            $licenseProxy->generateCodes($result['__id'], $data->codegroup_num);
            return array('url' => $this->changeAction(''));
        }
        else {
            return array('errors' => $result);
        }
    }
}
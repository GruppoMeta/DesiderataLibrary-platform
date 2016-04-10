<?php
class desiderataLibrary_modules_codes_models_proxy_Code
{
    private $hashids;

    function __construct()
    {
        GlizyClassLoader::addLib('Hashids', __Paths::get('APPLICATION_LIBS').'Hashids');
        $this->hashids = new Hashids\Hashids(__Config::get('desiderata.hashids.salt'), 0, 'abcdefghijklmnopqrstuvwxyz1234567890');
    }

    public function generateCodes($groupId, $num)
    {
        $creationDate = new org_glizy_types_Date();
        $ar = org_glizy_ObjectFactory::createModel('desiderataLibrary.modules.codes.models.Code');
        for ($i=0; $i<$num; $i++) {
            $ar->emptyRecord();
            $ar->code_status = 0;
            $ar->code_FK_codegroup_id = $groupId;
            $ar->code_FK_user_id = 0;
            $ar->code_creationDate = $creationDate;
            $ar->code_burnDate = '0000-00-00';
            $ar->code_pos = $i;
            $ar->code_value = $this->hashids->encode(array($groupId, $i, rand(0, 1000)));
            try {
                $ar->save();
            } catch (Exception $e) {
                die(var_export($e->getErrors()));
            }
        }
    }

    public function deleteGroup($id)
    {
        $ar = org_glizy_ObjectFactory::createModel('desiderataLibrary.modules.codes.models.CodeGroup');
        if ($ar->load($id)) {
            $ar->codegroup_deleted = 1;
            $ar->save();
        }
    }
}
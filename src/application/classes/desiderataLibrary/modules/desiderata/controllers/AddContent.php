<?php
class desiderataLibrary_modules_desiderata_controllers_AddContent extends org_glizy_rest_core_CommandRest
{
    function execute($desiderataId, $volumeId, $contentId)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.Desiderata');

            $r = $ar->find(array(
                'desiderata_id' => $desiderataId,
                'desiderata_FK_user_id' => $this->user->id,
                'desideratadetail_volumeId' => $volumeId,
                'desideratadetail_contentId' => $contentId
            ));

            // se il contenuto da aggiungere non è già presente
            if (!$r) {
                $arDetail = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.DesiderataDetail');
                $arDetail->desideratadetail_FK_desiderata_id = $desiderataId;
                $arDetail->desideratadetail_volumeId = $volumeId;
                $arDetail->desideratadetail_contentId = $contentId;
                $arDetail->save();
                $result = desiderataLibrary_modules_models_vo_ResponseVO::OK();
            } else {
                // il testo da aggiungere è già presente
                return false;
	    	}
        }

        return $result;
    }
}
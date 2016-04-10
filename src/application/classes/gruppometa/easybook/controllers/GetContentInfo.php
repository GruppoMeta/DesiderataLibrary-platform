<?php
class gruppometa_easybook_controllers_GetContentInfo extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id, $content_id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            desiderataLibrary_models_vo_ResultVO::init();
            $resultVO = __ObjectFactory::createObject('desiderataLibrary.models.vo.ResultVO');
            $resultVO->createFromVolumeIdContentId($this->user->id, $volume_id, $content_id);
            $result = $resultVO;
        }

        return $result;
    }
}
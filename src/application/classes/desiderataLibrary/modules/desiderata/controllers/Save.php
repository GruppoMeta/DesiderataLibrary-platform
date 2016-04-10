<?php
class desiderataLibrary_modules_desiderata_controllers_Save extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $body = json_decode(__Request::getBody());
            $now = new org_glizy_types_DateTime();

            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.DesiderataMain');
            $ar->desiderata_FK_user_id = $this->user->id;
            $ar->desiderata_title = $body->title;
            $ar->desiderata_tags = json_encode($body->tags);
            $ar->desiderata_created_at = $now->__toString();
            $desiderataId = $ar->save();

            $elements = array();
            
            desiderataLibrary_models_vo_ResultVO::init();

            foreach ($body->elements as $element) {
                $arDetail = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.DesiderataDetail');
                $arDetail->desideratadetail_FK_desiderata_id = $desiderataId;
                $arDetail->desideratadetail_volumeId = $element->publicationId;
                $arDetail->desideratadetail_contentId = $element->id;
                $arDetail->save();

                $resultVO = __ObjectFactory::createObject('desiderataLibrary.models.vo.ResultVO');
                $resultVO->createFromVolumeIdContentId($this->user->id, $element->publicationId, $element->id);
                $elements[] = $resultVO;
            }


            $result = array(
                'id' => $ar->getId(),
                'title' => $ar->desiderata_title,
                'tags' => json_decode($ar->desiderata_tags),
                'elements' => $elements,
                'creationDate' => $ar->desiderata_created_at
            );
        }

        return $result;
    }
}
<?php
class desiderataLibrary_modules_desiderata_controllers_Update extends org_glizy_rest_core_CommandRest
{
    function execute($id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $body = json_decode(__Request::getBody());
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.DesiderataMain');
            if ($ar->load($id)) {
                $ar->desiderata_title = $body->title;
                $ar->desiderata_tags = json_encode($body->tags);
                $ar->save();

                $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.desiderata.models.DesiderataDetail')
                    ->where('desideratadetail_FK_desiderata_id', $id);

                foreach ($it as $arDetail) {
                    $arDetail->delete();
                }

                $elements = array();
                
                desiderataLibrary_models_vo_ResultVO::init();

                foreach ($body->elements as $element) {
                    $arDetail = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.DesiderataDetail');
                    $arDetail->desideratadetail_FK_desiderata_id = $id;
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
            } else {
                $result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
            }
        }

        return $result;
    }
}


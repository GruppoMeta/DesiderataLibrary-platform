<?php
class desiderataLibrary_modules_tags_controllers_Save extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id, $content_id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$volume_id || !$content_id) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.tags.models.Tags');
            $ar->find(array('tag_FK_user_id' => $this->user->id, 'tag_volume_id' => $volume_id, 'tag_content_id' => $content_id));
            $ar->delete();

            $body = json_decode(__Request::getBody());

            if ($body->userKeywords) {
                foreach ($body->userKeywords as $userKeywords) {
                    $ar = __ObjectFactory::createModel('desiderataLibrary.modules.tags.models.Tags');
                    $ar->tag_FK_user_id = $this->user->id;
                    $ar->tag_volume_id = $volume_id;
                    $ar->tag_content_id = $content_id;
                    $ar->tagdetail_keyword = $userKeywords->text;
                    $ar->save();
                }
            }

            $now = new org_glizy_types_DateTime();
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.annotations.models.Annotation');
            $r = $ar->find(array(
                'annotation_type' => 'tag',
                'annotation_user_id' => $this->user->id,
                'annotation_volume_id' => $volume_id,
                'annotation_content_id' => $content_id
            ));

            if (!$r) {
                $ar->annotation_type = 'tag';
                $ar->annotation_user_id = $this->user->id;
                $ar->annotation_volume_id = $volume_id;
                $ar->annotation_content_id = $content_id;
                $ar->annotation_created_at = $now->__toString();
            }

            $ar->annotation_data = preg_replace('/\s+/', '', __Request::getBody());
            $ar->annotation_updated_at = $now->__toString();
            $ar->save();
            $result = __ObjectFactory::createObject('desiderataLibrary.modules.annotations.models.vo.AnnotationVO', $ar);
        }

        return $result;
    }
}
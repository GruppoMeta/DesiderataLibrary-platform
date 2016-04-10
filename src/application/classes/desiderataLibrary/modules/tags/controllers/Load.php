<?php
class desiderataLibrary_modules_tags_controllers_Load extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id, $content_id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$volume_id || !$content_id) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $ar = __ObjectFactory::createModelIterator('desiderataLibrary.modules.annotations.models.Annotation')
                ->where('annotation_type', 'tag')
                ->where('annotation_user_id', $this->user->id)
                ->where('annotation_volume_id', $volume_id)
                ->where('annotation_content_id', $content_id)
                ->first();

            $result = json_decode($ar->annotation_data);
        }

        return $result;
    }
}
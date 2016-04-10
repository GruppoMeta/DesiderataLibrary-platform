<?php
class desiderataLibrary_modules_annotations_controllers_GetAnnotationsByVolumeAndType extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id, $type)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$volume_id) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.annotations.models.Annotation')
                ->where('annotation_user_id', $this->user->id)
                ->where('annotation_volume_id', $volume_id)
                ->where('annotation_type', $type);

            foreach ($it as $ar) {
                $result[] = __ObjectFactory::createObject('desiderataLibrary.modules.annotations.models.vo.AnnotationVO', $ar);
            }
        }

        return $result;
    }
}

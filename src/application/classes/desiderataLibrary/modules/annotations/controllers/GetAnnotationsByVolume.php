<?php
class desiderataLibrary_modules_annotations_controllers_GetAnnotationsByVolume extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$volume_id) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.annotations.models.Annotation')
                ->where('annotation_user_id', $this->user->id)
                ->where('annotation_volume_id', $volume_id);

            foreach ($it as $ar) {
                if ($ar->annotation_type == 'comment') {
                    $annotationVO = __ObjectFactory::createObject('desiderataLibrary.modules.annotations.models.vo.CommentVO', $ar);
                } else {
                    $annotationVO = __ObjectFactory::createObject('desiderataLibrary.modules.annotations.models.vo.AnnotationVO', $ar);
                }
                $result[] = $annotationVO;
            }
        }

        return $result;
    }
}

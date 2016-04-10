<?php
class desiderataLibrary_modules_annotations_controllers_AddAnnotation extends org_glizy_rest_core_CommandRest
{
    function execute($type)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$type) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $body = json_decode(__Request::getBody());
            $now = new org_glizy_types_DateTime();
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.annotations.models.Annotation');
            foreach ($body as $k => $v) {
                $ar->{'annotation_'.$k} = $v;
            }
            $ar->annotation_type = $type;
            $ar->annotation_created_at = $now->__toString();
            $ar->annotation_updated_at = $now->__toString();
            $ar->annotation_user_id = $this->user->id;
            $ar->save();
            $result = __ObjectFactory::createObject('desiderataLibrary.modules.annotations.models.vo.AnnotationVO', $ar);
        }

        return $result;
    }
}

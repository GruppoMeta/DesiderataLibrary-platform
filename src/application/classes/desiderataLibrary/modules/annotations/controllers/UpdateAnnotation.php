<?php
class desiderataLibrary_modules_annotations_controllers_UpdateAnnotation extends org_glizy_rest_core_CommandRest
{
    function execute($id, $type)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$id || !$type) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $body = json_decode(__Request::getBody());
            $now = new org_glizy_types_DateTime();
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.annotations.models.Annotation');
            if ($ar->load($id)) {
                if ($ar->annotation_user_id != $this->user->id) {
                    return desiderataLibrary_modules_models_vo_ErrorVO::Forbidden();
                }
                foreach ($body as $k => $v) {
                    $ar->{'annotation_'.$k} = $v;
                }
                $ar->annotation_updated_at = $now->__toString();
                $ar->save();
                $result = __ObjectFactory::createObject('desiderataLibrary.modules.annotations.models.vo.AnnotationVO', $ar);
            } else {
                $result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
            }
        }

        return $result;
    }
}

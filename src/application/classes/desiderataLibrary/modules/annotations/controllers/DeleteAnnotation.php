<?php
class desiderataLibrary_modules_annotations_controllers_DeleteAnnotation extends org_glizy_rest_core_CommandRest
{
    function execute($id, $type)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!id || !$type) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.annotations.models.Annotation');
            $ar->load($id);
            if ($ar->annotation_user_id != $this->user->id) {
                $result = desiderataLibrary_modules_models_vo_ErrorVO::Forbidden();
            } else {
                $ar->delete($id);
                $result['message'] = 'OK';
            }
        }

        return $result;
    }
}

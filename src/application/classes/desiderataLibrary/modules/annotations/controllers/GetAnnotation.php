<?php
class desiderataLibrary_modules_annotations_controllers_GetAnnotation extends org_glizy_rest_core_CommandRest
{
	function execute($id, $type)
	{
	    $result = array();

	    if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
		} else if (!$id || !$type) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
	    } else {
    	    $ar = __ObjectFactory::createModel('desiderataLibrary.modules.annotations.models.Annotation');

	    	if ($ar->find(array('annotation_id' => $id,'annotation_user_id' => $this->user->id))) {
    	    	$result = __ObjectFactory::createObject('desiderataLibrary.modules.annotations.models.vo.AnnotationVO', $ar);
	    	} else {
	    		$result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
	    	}
	    }

        return $result;
	}
}

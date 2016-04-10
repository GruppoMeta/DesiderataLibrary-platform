<?php
class desiderataLibrary_modules_desiderata_controllers_Delete extends org_glizy_rest_core_CommandRest
{
    function execute($id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.Desiderata');
            if (!$ar->load($id)) {
	    		$result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
            } else if ($ar->desiderata_FK_user_id != $this->user->id) {
                $result = desiderataLibrary_modules_models_vo_ErrorVO::Forbidden();
            } else {
                $ar->delete($id);
                $result['message'] = 'OK';
            }
        }

        return $result;
    }
}
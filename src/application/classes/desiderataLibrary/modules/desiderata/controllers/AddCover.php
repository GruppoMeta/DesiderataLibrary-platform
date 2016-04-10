<?php
class desiderataLibrary_modules_desiderata_controllers_AddCover extends org_glizy_rest_core_CommandRest
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
            } else if (!$_FILES['picture']) {
                $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
            } else {
                $coverPath = __Paths::get('APPLICATION_MEDIA_ARCHIVE').'users/'.$this->user->id.'/'.$id;
                @mkdir($coverPath, 0777, true);

                $dest = $coverPath.'/'.$_FILES['picture']['name'];

                if (move_uploaded_file($_FILES['picture']['tmp_name'], $dest)) {
                    // rimuove la vecchia cover se c'è
                    if ($ar->desiderata_coverName) {
                        @unlink($coverPath.'/'.$ar->desiderata_coverName);
                    }

                    $ar->desiderata_coverName = $_FILES['picture']['name'];
                    $ar->save();

                    $result = desiderataLibrary_modules_models_vo_ResponseVO::OK();
                } else {
                    $result = desiderataLibrary_modules_models_vo_ErrorVO::InternalServerError();
                }
            }
        }

        return $result;
    }
}
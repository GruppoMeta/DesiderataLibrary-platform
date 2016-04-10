<?php
class desiderataLibrary_modules_tags_controllers_CreateUserKeyword extends org_glizy_rest_core_CommandRest
{
    function execute($term)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$term) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.tags.models.Keyword');
            $ar->find(array(
                'keyword_text' => $term,
                'keyword_FK_user_id' => $this->user->id
            ));
            $ar->keyword_text = $term;
            $ar->keyword_FK_user_id = $this->user->id;
            $ar->save();

            $result = array(
                'id' => $ar->getId(),
                'text' => $term
            );
        }

        return $result;
    }
}
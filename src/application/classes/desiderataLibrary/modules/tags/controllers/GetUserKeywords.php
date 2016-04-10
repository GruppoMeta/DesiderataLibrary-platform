<?php
class desiderataLibrary_modules_tags_controllers_GetUserKeywords extends org_glizy_rest_core_CommandRest
{
    function execute($term)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.tags.models.Keyword')
                ->where('keyword_text', '%'.$term.'%', 'LIKE')
                ->where('keyword_FK_user_id', $this->user->id);

            foreach ($it as $ar) {
                $result[] = array(
                    'id' => $ar->getId(),
                    'text' => $ar->keyword_text
                );
            }
        }

        return $result;
    }
}
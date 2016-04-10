<?php
class desiderataLibrary_modules_tags_controllers_GetOntologyTags extends org_glizy_rest_core_CommandRest
{
    function execute($term)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $proxy = __ObjectFactory::createModel('desiderataLibrary.modules.ontologybuilder.models.proxy.EntityContentProxy');
            $result = $proxy->findTerm(null, null, null, $term, null);
        }

        return $result;
    }
}
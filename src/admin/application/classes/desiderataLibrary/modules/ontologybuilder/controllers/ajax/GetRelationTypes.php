<?php
class desiderataLibrary_modules_ontologybuilder_controllers_ajax_GetRelationTypes extends org_glizy_mvc_core_CommandAjax
{
    function execute()
    {
        $application = org_glizy_ObjectValues::get('org.glizy', 'application');
        $language = $application->getLanguage();

        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.RelationTypesDocument');
        //$it->load('relationTypesFromLanguage', array('language' => $language));
        $it->orderBy('translation');

        $relationTypes = array();

        foreach($it as $ar) {
            $r = array(
                'id' => $ar->key,
                'name' => $ar->translation[$language] ? $ar->translation[$language] : $ar->key
            );

            $relationTypes[$r['id']] = $r;
        }

        return $relationTypes;
    }
}
?>
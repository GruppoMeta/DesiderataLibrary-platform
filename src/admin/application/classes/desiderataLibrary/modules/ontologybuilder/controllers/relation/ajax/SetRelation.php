<?php
class desiderataLibrary_modules_ontologybuilder_controllers_relation_ajax_SetRelation extends org_glizy_mvc_core_CommandAjax
{
    function execute()
    {
        $relation = org_glizy_objectFactory::createModel('desiderataLibrary.modules.ontologybuilder.models.RelationTypesDocument');
        $relation->load(__Request::get('pk'));
        $field = __Request::get('name');

        if (preg_match("/translation.(.+)/", $field, $m)) {
            $language = $m[1];
            $translation = $relation->translation;
            $translation[$language] = __Request::get('value');
            $relation->translation = $translation;
        }
        else {
            $relation->$field = __Request::get('value');
        }

        $relation->save();

        $entityTypeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');
        $entityTypeService->invalidate();

        $localeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.LocaleService');
        $localeService->invalidate();
    }
}
?>
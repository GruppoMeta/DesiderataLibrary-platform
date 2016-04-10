<?php
class desiderataLibrary_modules_ontologybuilder_controllers_relation_ajax_DelRelation extends org_glizy_mvc_core_CommandAjax
{
    function execute($id)
    {
// TODO verificare permessi
        $relation = org_glizy_objectFactory::createModel('desiderataLibrary.modules.ontologybuilder.models.RelationTypesDocument');
        $relation->delete($id);

        $entityTypeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');
        $entityTypeService->invalidate();

        $localeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.LocaleService');
        $localeService->invalidate();

        return true;
    }
}

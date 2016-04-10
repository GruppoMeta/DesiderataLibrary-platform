<?php
class desiderataLibrary_modules_ontologybuilder_controllers_Delete extends org_glizy_mvc_core_Command
{
    public function execute($id)
    {
        // cancella tutti i contenuti collegati al tipo di entità da cancellare
        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityDocument');
        $it->load('allFromTypeAllStatusAllLanguages', array('entityTypeId' => $id));

        foreach($it as $ar) {
            $ar->delete();
        }

        // cancella le proprietà collegate all'entità
        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityProperties');
        $it->load('entityPropertiesFromId', array('entityId' => $id));

        foreach($it as $ar) {
            $ar->delete();
        }

        // cancella l'entità
        $entity = org_glizy_objectFactory::createModel('desiderataLibrary.modules.ontologybuilder.models.Entity');
        $entity->delete($id);

        $entityTypeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');
        $entityTypeService->invalidate();

        org_glizy_helpers_Navigation::goHere();
    }
}

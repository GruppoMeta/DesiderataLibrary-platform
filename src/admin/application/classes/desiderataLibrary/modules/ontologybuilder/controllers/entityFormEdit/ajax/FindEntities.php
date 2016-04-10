<?php
class desiderataLibrary_modules_ontologybuilder_controllers_entityFormEdit_ajax_FindEntities extends org_glizy_mvc_core_CommandAjax
{
    function execute($entityTypeId, $term)
    {
        $entityProxy = org_glizy_objectFactory::createObject('desiderataLibrary.modules.ontologybuilder.models.proxy.EntityProxy');
        $entities = $entityProxy->findEntities($entityTypeId, $term);
        return $entities;
    }
}
?>
<?php
class desiderataLibrary_modules_ontologybuilder_models_proxy_EntityContentProxy extends GlizyObject
{
    public function findTerm($fieldName, $model, $query, $term, $proxyParams)
    {
        $result = array();

        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityModel');
        // if (__Config::get('MULTISITE_ENABLED')) {
        //     $it->where('entity_FK_site_id', org_glizy_ObjectValues::get('org.glizy', 'siteId' ));
        // }

        if ($term != '') {
            $it->where('entity_name', '%'.$term.'%', 'ILIKE');
        }

        $it->orderBy('entity_name');

        $result = array();

        foreach($it as $ar) {
            $result[] = array(
                'id' => $ar->entity_id,
                'text' => $ar->entity_name,
                'type' => 'Entità'
            );
        }

        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityDocument', 'All');
        $it->disableSiteConditionIs();
        if ($term != '') {
            $it->where('title', '%'.$term.'%', 'ILIKE');
        }

        $it->orderBy('title');

        foreach($it as $ar) {
            $result[] = array(
                'id' => $ar->document_id,
                'text' => $ar->title,
                'type' => 'Contenuto'
            );
        }
        return $result;
    }
}
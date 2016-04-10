<?php
class desiderataLibrary_modules_ontologybuilder_models_proxy_ContentProxy extends GlizyObject
{
    public function findTerm($fieldName, $model, $query, $term, $proxyParams)
    {
        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityDocument', 'All');

        if ($term != '') {
            $it->where('title', '%'.$term.'%', 'ILIKE');
        }

        $it->orderBy('title');

        $result = array();

        foreach($it as $ar) {
            $result[] = array(
                'id' => $ar->document_id,
                'text' => $ar->title
            );
        }

        return $result;
    }
}
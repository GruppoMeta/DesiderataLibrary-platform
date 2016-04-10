<?php
class desiderataLibrary_modules_ontologybuilder_controllers_relation_ajax_NewRelation extends org_glizy_mvc_core_CommandAjax
{
    function execute()
    {
        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.Languages', 'all');

        $translation = array();

        foreach($it as $ar) {
            $code = $ar->language_code;
            if (__Request::get('translation_'.$code)) {
                $translation[$code] = __Request::get('translation_'.$code);
                if (empty($key)) {
                    $key = $translation[$code];
                }
            }
        }

        $relation = org_glizy_objectFactory::createModel('desiderataLibrary.modules.ontologybuilder.models.RelationTypesDocument');
        $relation->key = $key;
        $relation->translation = $translation;
        $relation->cardinality = __Request::get('cardinality');
        $id = $relation->save();

        $entityTypeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');
        $entityTypeService->invalidate();

        $localeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.LocaleService');
        $localeService->invalidate();

        return $id;
    }
}
?>
<?php
class desiderataLibrary_modules_ontologybuilder_controllers_ajax_AddEntityLabel extends org_glizy_mvc_core_CommandAjax
{
    function execute($text, $key)
    {
        $application = org_glizy_ObjectValues::get('org.glizy', 'application');
        $language = $application->getEditingLanguage();

        $localeService = $this->application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.LocaleService');
        $keyLanguageCode = $localeService->keyAlreadyExists($lang, $key);

        if ($keyLanguageCode) {
            $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityLabelsDocument')
                ->where('key', $key);

            $entityLabels = $it->first();
            $temp = $entityLabels->translation;
            $temp[$language] = $text;
            $entityLabels->translation = $temp;
            $entityLabels->save();
            $localeService->invalidate();
        } else {
            $entityLabels = org_glizy_objectFactory::createModel('desiderataLibrary.modules.ontologybuilder.models.EntityLabelsDocument');
            $entityLabels->key = $text;
            $entityLabels->translation = array($language => $text);
            $entityLabels->save();
        }

        return $entityLabels->key;
    }
}
?>
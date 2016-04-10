<?php
class gruppometa_easybook_controllers_GetCatalog extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        $results = array();

        $it = org_glizy_ObjectFactory::createModelIterator('gruppometa.easybook.models.Publication')
            ->load("getPublications");

        $publicationService = __ObjectFactory::createObject('desiderataLibrary.service.PublicationService');

        foreach ($it as $ar) {
            if (('2' == $ar->document->state)) {
                $publicationVO =  org_glizy_ObjectFactory::createObject('gruppometa.easybook.models.vo.PublicationVO', $ar);
                $publicationVO->inLibrary = $publicationService->isInLibrary($publicationVO->id);
                $results[] = $publicationVO;
            }
        }
        return $results;
    }
}

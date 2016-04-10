<?php
class desiderataLibrary_modules_search_controllers_InferenceSearch extends org_glizy_rest_core_CommandRest
{
    // TODO funzione per aggiungere boost
    function execute($volume_id, $content_id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            // recupera i tag di tipo contenuto
            $contentProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
            $content = $contentProxy->readContentFromMenu($content_id, __ObjectValues::get('org.glizy', 'editingLanguageId'));
            $inferenceService = __objectFactory::createObject('desiderataLibrary.plugins.export.services.InferenceService');
            $inferenceResult = $inferenceService->getRelatedContents($content->tags);

            $result = $this->doSolrSearch($inferenceResult);
        }

        return $result;
    }

    protected function doSolrSearch($inference)
    {
        $url =  __Config::get('desiderataLibrary.solr.url').'select';

        $q = array();

        foreach ((array)$inference['contents'] as $content) {
            $q[] = 'tags_facet:"'.$content.'"';
        }

        foreach ((array)$inference['relatedContents'] as $relatedContent) {
            $q[] = 'tags_facet:"'.$relatedContent.'"';
        }

        $params = array(
            'wt' => 'json',
            'indent' => 'true',
            'q' => implode(' OR ', $q)
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'GET', $params);
        $request->execute();
        $r = json_decode($request->getResponseBody());
        $response = $r->response;

        $searchResults = array();
        desiderataLibrary_models_vo_ResultVO::init();

        foreach ($response->docs as $doc) {
            $resultVO = __ObjectFactory::createObject('desiderataLibrary.models.vo.ResultVO');
            $resultVO->createFromSolr($this->user->id, $doc);
            $searchResults[] = $resultVO;
        }

        return $searchResults;
    }
}
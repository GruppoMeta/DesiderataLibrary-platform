<?php
class desiderataLibrary_modules_search_controllers_PublicationSearch extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id, $text)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $result = $this->doSolrSearch($volume_id, $text);
        }

        return $result;
    }

    protected function doSolrSearch($volumeId, $text)
    {
        $url =  __Config::get('desiderataLibrary.solr.url').'select';

        $params = array(
            'wt' => 'json',
            'indent' => 'true',
            'facet' => 'true',
            'facet.field' => 'tags_facet',
            'facet.mincount' => 1,
            'q' => 'publicationId_i:'.$volumeId.' AND *'.$text.'*'
        );

        $fq = array();

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
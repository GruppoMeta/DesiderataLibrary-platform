<?php
class desiderataLibrary_modules_similar_controllers_SimilarDocuments extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id, $content_id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $result = $this->doSolrSearch($volume_id, $content_id);
        }

        return $result;
    }

    protected function doSolrSearch($volume_id, $content_id)
    {
        $url =  __Config::get('desiderataLibrary.solr.url').'mlt';

        // cerca i testi simili di libri diversi da quello specificato in $volume_id
        $params = array(
            'wt' => 'json',
            'indent' => 'true',
            'q' => 'id:'.$content_id,
            'fq' => '-publicationId_i:'.$volume_id,
            'mlt.fl' => 'text_t'
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'GET', $params);
        $request->execute();
        $r = json_decode($request->getResponseBody());
        $response = $r->response;

        $searchResults = array();

        if ($response) {
            desiderataLibrary_models_vo_ResultVO::init();

            foreach ($response->docs as $doc) {
                $resultVO = __ObjectFactory::createObject('desiderataLibrary.models.vo.ResultVO');
                $resultVO->createFromSolr($this->user->id, $doc);
                $searchResults[] = $resultVO;
            }
        }

        return $searchResults;
    }
}
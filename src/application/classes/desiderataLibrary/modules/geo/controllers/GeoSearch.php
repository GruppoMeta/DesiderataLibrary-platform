<?php
class desiderataLibrary_modules_geo_controllers_GeoSearch extends org_glizy_rest_core_CommandRest
{
    function execute($geo, $distance)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            // restituisce i poi entro un il range indicagto in $distance dal centro $geo
            $result = $this->doSolrSearch($geo, $distance);
        }

        return $result;
    }

    protected function doSolrSearch($geo, $distance)
    {
        $url =  __Config::get('desiderataLibrary.solr.url').'select';

        $params = array(
            'wt' => 'json',
            'indent' => 'true',
            'q' => '*:*',
            'pt' => $geo,
            'd' => $distance,
            'fq' => '{!geofilt}',
            'sfield' => 'latlon'
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
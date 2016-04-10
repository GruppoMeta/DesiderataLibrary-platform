<?php
class desiderataLibrary_modules_search_controllers_Search extends org_glizy_rest_core_CommandRest
{
    function execute($text, $keywords, $topics, $page)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $result = $this->doSolrSearch($text, $keywords, $topics, $page);
        }

        return $result;
    }

    protected function doSolrSearch($text, $keywords, $topics, $page)
    {
        if (!$page) {
            $page = 0;
        }

        $url =  __Config::get('desiderataLibrary.solr.url').'select';
        $pageLength = __Config::get('desiderataLibrary.search.pageLength');

        $params = array(
            'wt' => 'json',
            'indent' => 'true',
            'facet' => 'true',
            'facet.field' => 'tags_facet',
            'facet.mincount' => 1,
            'q' => ($text ? $text : '*'),
            'start' => $page*$pageLength,
            'rows' => $pageLength
        );

        $fq = array();

        if ($keywords) {
            $fq[] = 'keywords_facet:"'.implode('"&fq=keywords_facet:"', array_map(urlencode, $keywords)).'"';
        }

        if ($topics) {
            $fq[] = 'tags_facet:"'.implode('"&fq=tags_facet:"', array_map(urlencode, $topics)).'"';
        }

        if (!empty($fq)) {
            $url .= '?fq='.implode('&fq=', $fq);
        }

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

        $categories = array();
        $facet_fields = $r->facet_counts->facet_fields;
        $facetValues = $facet_fields->tags_facet;

        $topicsMap = array_fill_keys(array_map('strtolower',$topics), true);

        for ($i = 0; $i < count($facetValues); $i+=2) {
            $label = $facetValues[$i];
            $number = $facetValues[$i+1];
            $categories[] = array(
                'label' => $label,
                'number' => $number,
                'selected' => $topicsMap[strtolower($label)] ? true : false
            );
        }

        $result = array(
            'results' => $searchResults,
            'categories' => $categories,
            'page' => $page,
            'pages' => floor($response->numFound / $pageLength),
            'total' => $response->numFound
        );

        return $result;
    }
}
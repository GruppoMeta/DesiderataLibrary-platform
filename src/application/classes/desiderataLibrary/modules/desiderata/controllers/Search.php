<?php
class desiderataLibrary_modules_desiderata_controllers_Search extends org_glizy_rest_core_CommandRest
{
    function execute($search)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $result['desiderata'] = array();

            if ($search) {
                $contentIdArray = $this->doSolrSearch($search);

                $replaceArray = array(
                    '##search##' => $search,
                    '##userId##' => $this->user->id,
                    '##contentIds##' => !empty($contentIdArray) ? implode(',', $contentIdArray) : 'NULL'
                );

                $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.desiderata.models.Desiderata')
                    ->load('getDesiderataFromContentId', array('replace' => $replaceArray));
            } else {
                $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.desiderata.models.DesiderataMain')
                    ->where('desiderata_FK_user_id', $this->user->id);
            }

            foreach ($it as $ar) {
                if ($ar->desiderata_coverName) {
                    $coverPath = GLZ_HOST.'/'.__Paths::get('APPLICATION_MEDIA_ARCHIVE').'users/'.$this->user->id.'/'.$ar->getId().'/'.$ar->desiderata_coverName;
                } else {
                    $coverPath = GLZ_HOST.'/'.__Paths::get('STATIC_DIR').'images/desiderata_cover.png';
                }

                $count = __ObjectFactory::createModelIterator('desiderataLibrary.modules.desiderata.models.DesiderataDetail')
                    ->where('desideratadetail_FK_desiderata_id', $ar->getId())->count();

                $result['desiderata'][] = array(
                    'id' => $ar->getId(),
                    'title' => $ar->desiderata_title,
                    'creationDate' => $ar->desiderata_created_at,
                    'cover' => $coverPath,
                    'count' => $count
                );
            }
        }

        return $result;
    }

    protected function doSolrSearch($text)
    {
        $url =  __Config::get('desiderataLibrary.solr.url').'select';

        $params = array(
            'wt' => 'json',
            'indent' => 'true',
            'q' => $text
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'GET', $params);
        $request->execute();
        $r = json_decode($request->getResponseBody());
        $response = $r->response;

        $searchResults = array();

        foreach ((array)$response->docs as $doc) {
            $searchResults[] = $doc->id;
        }

        return $searchResults;
    }
}
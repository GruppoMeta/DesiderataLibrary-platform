<?php
class desiderataLibrary_plugins_export_services_ExportService extends gruppometa_jobmanager_service_SimpleService
{
    private $parts = 0;
    private $totalParts = 0;
    private $subtasks = 0;
    private $totalSubtasks = 0;
    private $menuIds = 0;
    private $host;
    private $contentProxy;
    private $inferenceService;
    private $seoService;
    private $offlineService;

    public function run()
    {
        parent::run();

        $this->contentProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
        $this->inferenceService = __objectFactory::createObject('desiderataLibrary.plugins.export.services.InferenceService');
        $this->seoService = __objectFactory::createObject('desiderataLibrary.plugins.export.services.SeoService');
        $this->offlineService = __objectFactory::createObject('desiderataLibrary.plugins.export.services.OfflineService');

        $this->menuIds = $this->params['menuIds'];
        $this->host = $this->params['host'];
        $this->totalParts = count($this->menuIds);

        foreach($this->menuIds as $publicationId) {
            $siteMap = $this->getSiteMap($publicationId);
            $this->totalSubtasks = ($siteMap->numPages() * 2) + 4;
            $this->subtaskDone('Lettura contenuti: '.$publicationId);
            // rimuove prima la vecchia indicizzazione
            $this->removeFromSolr($publicationId);
            $this->createArticlesAndIndex($publicationId, $siteMap);
            $this->parts++;
            $this->subtasks = 0;
            $this->recommenderImport($publicationId);
        }

        $this->complete();
    }

    protected function recommenderImport($publicationId)
    {
        // chiama il servizio per l'aggiornamento dei contenuti
        $url = __Config::get('desiderataLibrary.recommender.host');
        if ($url) {
            $url .= '/recommender/update_contents';
            $params = array('publication_id' => $publicationId);
            $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'GET', $params);
            $request->execute();
        }
    }

    private function getSiteMap($publicationId)
    {
        __Config::set('MULTISITE_ENABLED', true);
        org_glizy_ObjectValues::set('org.glizy', 'siteId', $publicationId);
        $siteMap = org_glizy_ObjectFactory::createObject('org.glizycms.core.application.SiteMapDB');
        $siteMap->getSiteArray();
        return $siteMap;
    }

    private function subtaskDone($message='')
    {
        if ($message) {
            $this->setMessage($message);
        }
        $this->subtasks++;
        $this->updateProgress((100 / $this->totalParts * $this->parts) + $this->subtasks / $this->totalSubtasks * (100 / $this->totalParts));
        $this->save();
    }

    private function createArticlesAndIndex($publicationId, $siteMap, $language=1)
    {
        $articles = array();

        $publicationContent = $this->contentProxy->readContentFromMenu($publicationId, $language);
        $this->seoService->initSeoForPubId($this->host, $publicationId, $publicationContent);
        $this->offlineService->init($this->host, $publicationId);

        $it = org_glizy_ObjectFactory::createObject('org.glizy.application.SiteMapIterator', $siteMap);
        while($it->hasMore()) {
            $menu = $it->getNode();
            $it->moveNext();
            $pageType = strtolower($menu->pageType);
            $content = $this->contentProxy->readContentFromMenu($menu->id, $language);

            $this->subtaskDone('Esportazione contenuto SOLR '.$menu->id);
            if ($pageType=='text' || $pageType=='textpdf') {
                if (@$content->hideInSearch!=1) {
                    $article = $this->createSolrDocumentForSection($publicationContent, $menu, $content);
                    if ($article) {
                        $articles[] = $article;
                    }
                }

                if ($content->webIndex==1) {
                    $this->seoService->addPageInSeo($menu, $content);
                }
            }

            $this->subtaskDone('Esportazione contenuto OFFLINE '.$menu->id);
            $this->offlineService->getContent($menu->id);
        }

        $this->subtaskDone('Creazione zip per offline');
        $this->offlineService->finish();

        $this->subtaskDone('Invio dati a SOLR');
        $this->seoService->store();
        $this->sendToSolr($articles);
    }

    protected function rip_tags($string)
    {
        $string = preg_replace ('/<[^>]*>/', ' ', $string);
        $string = str_replace("\r", '', $string);
        $string = str_replace("\n", ' ', $string);
        $string = str_replace("\t", ' ', $string);
        $string = trim(preg_replace('/ {2,}/', ' ', $string));
        $string = html_entity_decode($string);
        return $string;
    }

    private function createSolrDocumentForSection($publicationContent, $menu, $content)
    {
        $article = new StdClass();
        $article->id = $content->getId();
        $article->publicationId_i = $publicationContent->getId();
        $article->publicationTitle_t = $publicationContent->__title;
        $article->publicationAuthor_tt = $publicationContent->author;
        $article->sectionTitle_t = $content->__title;
        $article->isFree_i = @$publicationContent->isFree==1 ? 1 : 0;

        if ($publicationContent->publisher ) {
            $article->publicationPublisher_t = $publicationContent->publisher->text;
        }

        // metadati per profilazione
        if ($publicationContent->profile_age) {
            $article->profile_age_facet = $publicationContent->profile_age;
        }

        if (!empty($publicationContent->profile_interests) && $publicationContent->profile_interests[0]) {
            $article->profile_interests_facet = $publicationContent->profile_interests;
        }

        if (!empty($publicationContent->profile_qualification) && $publicationContent->profile_qualification[0]) {
            $article->profile_qualification_facet = $publicationContent->profile_qualification;
        }

        if (!empty($publicationContent->profile_profession) && $publicationContent->profile_profession[0]) {
            $article->profile_profession_facet = $publicationContent->profile_profession;
        }

        $textClean = trim($this->rip_tags($content->text));

        if ($textClean) {
            $article->text_t = $textClean;
        } else {
            return false;
        }

        $article->keywords_facet = array();

         // quando una pubblicazione ha un tag tutti i contenuti della pubblicazione avranno quel tag su solr
        if ($publicationContent->keywords) {
            $article->keywords_facet = array_merge($article->keywords_facet, $publicationContent->keywords);
        }

        if ($content->keywords) {
            $article->keywords_facet = array_unique(array_merge($article->keywords_facet, $content->keywords));
        }

        __Config::set('MULTISITE_ENABLED', false);
        $inferenceResult = $this->inferenceService->getRelatedContents($content->tags);
        __Config::set('MULTISITE_ENABLED', true);

        if ($inferenceResult['contents']) {
            $article->tags_facet = $inferenceResult['contents'];
        }

        if ($inferenceResult['relatedContents']) {
            $article->relatedContents_facet = $inferenceResult['relatedContents'];
        }

        $article->type_s = 'section';

        $geo = substr($content->geo, 0, strrpos($content->geo, ','));

        if ($geo) {
            $article->latlon = $geo;
        }

        return $article;
    }

    private function sendToSolr($articles)
    {
        foreach($articles as $article) {
            $this->sendRequestToSolr($article);
        }

        $this->subtaskDone();
    }

    private function sendRequestToSolr($doc)
    {
        $command = 'update/json';
        $json = array(
            'add' => array(
                'doc' => $doc,
                'boost' => 1.0,
                'overwrite' => true,
                'commitWithin' => 5000
                )
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest',
            __Config::get('desiderataLibrary.solr.url').$command.'?wt=json&commit=true',
            'POST',
             json_encode($json),
            'application/json'
        );
        $request->execute();
    }

    private function removeFromSolr($publicationId)
    {
        $command = 'update/json';
        $json = array(
            'delete' => array(
                'query' => 'publicationId_i:'.$publicationId
            )
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest',
            __Config::get('desiderataLibrary.solr.url').$command.'?wt=json&commit=true',
            'POST',
             json_encode($json),
            'application/json'
        );
        $request->execute();
    }
}
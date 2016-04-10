<?php
class gruppometa_easybook_controllers_GetContent extends org_glizy_rest_core_CommandRest
{
    private $siteMap;
    private $pubId;

    /**
     * @param  int $id        id della pubblicazion
     * @param  int $contentId id del contenuto
     * @return object            oggetto dei contenuti
     */
	function execute($id, $contentId, $returnChild=false)
	{
	    if (!$this->user->isLogged()) {
            return desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
	    }

	    if ((int)$id && (int)$contentId) {
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', $id);
            $this->pubId = $id;
            try {
                $this->siteMap = &org_glizy_ObjectFactory::createObject('org.glizycms.core.application.SiteMapDB');
                $this->siteMap->getSiteArray();
                $node = $this->siteMap->getNodeById($contentId);

                $result = $this->getContent($node, $returnChild);
                gruppometa_easybook_EasybookFE::trackEvent('read', 'read', $id);

                $this->feedback($id, $contentId);

                return $result;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
	}

    protected function feedback($volumeId, $contentId)
    {
        if (!__Config::get('desiderataLibrary.recommender.host')) {
            return;
        }

        $contentTime = __Session::get('getContentTime');

        if ($contentTime) {
            $a = microtime(true);

            $delta = round(microtime(true) - $contentTime['timestamp']);

            $url = __Config::get('desiderataLibrary.recommender.host').'/recommender/feedback';
            $params = array(
                'publication_id' => $volumeId,
                'content_id' => $contentId,
                'user_id' => $this->user->id,
                'content_time' => $delta
            );
            $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'POST', json_encode($params), 'application/json');
            $request->execute();
        }

        __Session::set('getContentTime', array('volumeId' => $volumeId, 'contentId' => $contentId, 'timestamp' => microtime(true)));
    }

    private function getContent($node, $returnChild)
    {
        $contentProxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
        $content = $contentProxy->readContentFromMenu($node->id, gruppometa_easybook_EasybookFE::getLanguage());

        switch ($node->pageType) {
            case 'Publication':
            case 'PublicationPdf':
                $child = $node->childNodes();
                return array('redirectId' => $child[0]->id);
                break;

            case 'Text':
                $result = org_glizy_ObjectFactory::createObject('gruppometa.easybook.models.vo.ContentTextVO', $content, $node, $this->pubId);
                break;

            case 'TextPdf':
                $result = org_glizy_ObjectFactory::createObject('gruppometa.easybook.models.vo.ContentTextPdfVO', $content, $node, $this->pubId);
                break;

            case 'Empty':
            case 'EmptyPdf':
                $result = org_glizy_ObjectFactory::createObject('gruppometa.easybook.models.vo.ContentEmptyVO', $content, $node, $this->pubId);
                break;
        }

        $this->getPrevNext($node, $result);
        return $result;
    }

    private function getPrevNext($node, $result)
    {
        $currentNodeId = $node->id;
        $it = org_glizy_ObjectFactory::createObject('org.glizy.application.SiteMapIterator', $this->siteMap);
        $it->setNode($node);
        while (true) {
        $prevNode = $it->movePrevious();
            if (!$prevNode) {
                break;
            } else if (($prevNode->pageType=='Text' || $prevNode->pageType=='TextPdf') && $prevNode->isVisible) {
                break;
            }
        }
        $it->setNode($node);
        while (true) {
        $nextNode = $it->moveNext();
            if (!$nextNode) {
                break;
            } else if (($nextNode->pageType=='Text' || $nextNode->pageType=='TextPdf') && $nextNode->isVisible) {
                break;
            }
        }
        $result->prevId = $prevNode && $prevNode->depth > 1 ? $prevNode->id : 0;
        $result->nextId = $nextNode && $nextNode->depth > 1 ? $nextNode->id : 0;

        while ($node!==null) {
            $result->path[] = $node->id;
            $tempNode = $node->parentNode();
            $node = $tempNode;
        }

        $result->path = array_reverse($result->path);
        $result->path = array_slice($result->path, 2);
    }
}

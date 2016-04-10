<?php

class desiderataLibrary_modules_exportEPUB_controllers_ajax_GetSteps extends org_glizy_mvc_core_CommandAjax
{
    public function execute()
    {
        if ($this->user->isLogged()) {
            $startPage = __Request::get('startPage', 1);
            $dc_title = __Request::get('dc_title');
            $dc_author = __Request::get('dc_author');
            $pubId = uniqid();
            $pubDate = date('d/m/Y H:i:00');

            $publishStepVO = 'desiderataLibrary.modules.exportEPUB.models.vo.PublishStepVO';

            // elenco delle pagine da pubblicare
            $pages = array();
            $found = false;
            $menuDepth = 0;
            $siteMapIterator = desiderataLibrary_modules_exportEPUB_Common::getSitemapIterator();
            while (!$siteMapIterator->EOF) {
                $n = $siteMapIterator->getNodeArray();
                $siteMapIterator->moveNext();
                if ($n['id'] == $startPage) {
                    $found = true;
                    $menuDepth = $n['depth'];
                    continue;
                }

                if ($found) {
                    if ($menuDepth == $n['depth']) break;
                    if ($n['type'] == 'SYSTEM') {
                        continue;
                    }

                    $pages[] = array('id' => $n['id'],
                        'depth' => $n['depth'] - $menuDepth,
                    );
                }
            }

            $pagesStep = array_chunk($pages, 10);

            $customCSS = "css/publication.css";
            // costruisce l'array con gli step da chiamare per la pubblicazione su amazon
            $result = array();
            $result[] = org_glizy_objectFactory::createObject($publishStepVO, 'EpubInitialize', array('startPage' => $startPage, 'pubId' => $pubId, 'pubDate' => $pubDate, 'dc_title' => $dc_title, 'dc_author' => $dc_author));
            foreach ($pagesStep as $v) {
                $result[] = org_glizy_objectFactory::createObject($publishStepVO, 'EpubCreatePages', array('pubId' => $pubId, 'pages' => $v, 'customCSS' => $customCSS));
            }
            $result[] = org_glizy_objectFactory::createObject($publishStepVO, 'EpubCreateMetadata', array('pubId' => $pubId, 'pubDate' => $pubDate, 'dc_title' => $dc_title, 'dc_author' => $dc_author));
            $result[] = org_glizy_objectFactory::createObject($publishStepVO, 'EpubPublish', array('pubId' => $pubId));
            $result[] = org_glizy_objectFactory::createObject('desiderataLibrary.modules.exportEPUB.models.vo.EndStepVO', GLZ_HOST.'/'.desiderataLibrary_modules_exportEPUB_Common::getEbookPath($pubId));

            return $result;
        }
    }
}
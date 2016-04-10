<?php

class desiderataLibrary_modules_importEPUB_importer_Importer {

    private $showLog;
    private $epubFolder;
    private $pubFolderName;
    private $config;
    private $tocMap;
    private $importLog;
    private $tocEl = array();

    function __construct($showLog) {
        $this->showLog = $showLog;
    }

    public function setShowLog($state) {
        $this->showLog = $state;
    }

    public function import($epubFolder, $pubId) {
        $this->log('=== INIZIO PROCEDURA DI PARSING DELLA PUBBLICAZIONE CONTENUTA IN:' . $epubFolder);
        $this->epubFolder = $epubFolder;
        $this->config = $this->readConfig($epubFolder);
        $path = $epubFolder;
        $cssList = array();
        $this->log('PASSO 2: cerco i fogli di stile collegati a questa pubblicazione...');
        $this->searchCSS($path, $cssList);
        $this->storeCSS($cssList, $pubId);
        $toc = $this->loadToc($epubFolder, $this->config->getuseSpine());
        $this->log('PASSO 6: inizio la procedura di parsing usando l\'indice...');
        $results = $this->parseNavMap($toc);
        $this->log('Parsing completato!');
        return $results;
    }

    /**
     * Crea il file di configurazione.
     * @param $pubName
     * @param $folder
     */
    public function writeConfig($pubName, $folder)
    {
        $contDom = new DOMDocument();
        $contDom->load($folder . '/META-INF/container.xml');
        $xp = new DOMXPath($contDom);
        $folderPath = $xp->query('//rootfile/@full-path')->item(0)->nodeValue;
        $this->pubFolderName = pathinfo($folderPath, PATHINFO_DIRNAME);
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $pub = $dom->createElement('ebook');
        $n = $dom->createElement('name', $pubName);
        $cf = $dom->createElement('custom-fix');
        $us = $dom->createElement('use-spine', 'true');
        $mc = $dom->createElement('missed-content', 'false');
        $dl = $dom->createElement('data-layout');
        $pub->appendChild($n);
        $pub->appendChild($cf);
        $pub->appendChild($us);
        $pub->appendChild($mc);
        $pub->appendChild($dl);
        $dom->appendChild($pub);
        $dom->save($folder . "/$this->pubFolderName/config.xml");
    }

    private function readConfig($epubFolder) {
        $this->log('PASSO 1: inizio la lettura del file di configurazione...');
        $xml = new DomDocument();
        $xml->load($epubFolder . "/$this->pubFolderName/config.xml");
        $this->log('File di configurazione caricato!');
        $name = $xml->getElementsByTagName("name")->item(0)->nodeValue;
        $useSpine = $xml->getElementsByTagName("use-spine")->item(0)->nodeValue;
        $missedContent = $xml->getElementsByTagName("missed-content")->item(0)->nodeValue;
        $config = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.Config', $name);
        $config->setuseSpine($useSpine);
        $config->setmissedContent($missedContent);

        $customFix = $xml->getElementsByTagName("fix");
        foreach ($customFix as $fix) {
            $config->addCustomFix($fix->nodeValue);
        }
        return $config;
    }

    public function writeLog($epubFolder) {
        file_put_contents($epubFolder."/log.txt", $this->importLog);
    }

    private function searchCSS($path, &$cssList) {
        $contents = scandir($path);
        foreach ($contents as $key => $value) {
            if (!in_array($value, array(".",".."))) {
                if (is_dir($path . DIRECTORY_SEPARATOR . $value)) {
                    $this->searchCSS($path . DIRECTORY_SEPARATOR . $value, $cssList);
                } else if (pathinfo($value, PATHINFO_EXTENSION) == 'css'){
                    $this->log('Ho trovato un foglio di stile: ' . $value);
                    $cssList[] = $path."/".$value;
                }
            }
        }
    }

    private function storeCSS($cssList, $pubId) {
        $this->log('PASSO 3: preparo i fogli di stile per il salvataggio sul database...');
        $cssText = "";
        // Ripulisco CSS da riferimenti url() ai font
        $re = "/(src\\s*:\\s*)(url\\s*\\(\\s*[\\\"']*.*[\\\"']*\\s*\\))(\\s*;*)/";
        foreach ($cssList as $css) {
            $cssFile = fopen($css, "r") or die ("Unable to open CSS file");
            $cssText .= "\r\n/* " . pathinfo($css, PATHINFO_BASENAME) . " */\r\n";
            while(!feof($cssFile)) {
                $line = preg_replace($re, '$1$3', fgets($cssFile));
                $cssText .= $line;
            }
            fclose($cssFile);
        }
        $contentProxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
        $content = $contentProxy->readContentFromMenu($pubId, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'));
        $content->customCss = $cssText;
        $contentProxy->saveContent($content, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'), __Config::get('glizycms.content.history'));
        $this->log('Fogli di stile salvati sul database!');
    }

    private function searchEPUB($folder, $ext, &$pathToFile, &$exit) {
        $contents = scandir($folder);
        if (!$exit) {
            foreach ($contents as $key => $value) {
                if (!in_array($value, array(".", ".."))) {
                    if (is_dir($folder . DIRECTORY_SEPARATOR . $value)) {
                        $this->searchEPUB($folder . DIRECTORY_SEPARATOR . $value, $ext, $pathToFile, $exit);
                    } else if (pathinfo($value, PATHINFO_EXTENSION) == $ext) {
                        $pathToFile = $folder . "/" . $value;
                        $this->log('Elemento trovato al percorso: ' . $pathToFile);
                        $exit = true;
                    }
                }
            }
        }
        return $pathToFile;
    }

    private function loadToc($epubFolder, $useSpine) {
        $this->log('PASSO 4: cerco l\'indice della pubblicazione...');
        $tocFile = $this->searchEPUB($epubFolder, "ncx", $path = "", $exit = false);
        $tocFolder = pathinfo($tocFile, PATHINFO_DIRNAME);
        $this->tocMap = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.TocMap', $tocFolder, $tocFile);
        $this->tocMap->setShowLog($this->showLog);
        if ($useSpine) {
            $this->log('ATTENZIONE - IL FILE DI CONFIGURAZIONE RICHIEDE L\'USO DELLA SPINE');
            $this->log('PASSO 4.1: cerco la spine della pubblicazione...');
            $spineFile = $this->searchEPUB($epubFolder, "opf", $path = "", $exit = false);
            $spine = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.SpineMap', $tocFolder, $spineFile);
            $spine->setShowLog($this->showLog);
            $spine->loadSpine($tocFile, $this->importLog, $this->tocEl);
            $spineMap = $spine->getidrefMap();
            $this->tocMap->loadTocWithSpine($spineMap, $this->importLog);
        } else {
            $this->tocMap->loadToc($this->importLog);
        }
        return $this->tocMap->getNavMap();
    }

    private function parseNavMap($navMap, &$parsedFile = array()) {
        $results = array();
        foreach ($navMap as $tocItem) {
            $parsedFile[$tocItem->absFile] = true;
            $parseResult = $this->parseFile($tocItem, $this->tocMap);
            $resultOfParse = $parseResult->getResult();
            $firstResult = $resultOfParse[0];
            if ($firstResult && (!$firstResult->title || strlen($firstResult->title) == 0)) {
                $firstResult->title = $tocItem->title;
            }

            if ($tocItem->haveChild()) {
                $childResults = $this->parseNavMap($tocItem->childs, $parsedFile);

                $toWrap = array();
                $needWrap = false;

                for ($i = 0; $i < count($childResults); $i++) {
                    $temp = $childResults[$i]->getResult();
                    $first = $temp[0];
                    if ($first->pageType === 'Text') {
                        $toWrap[] = array('start' => $i, 'end' => $i, "wrap" => true);
                    }

                    if ($first->pageType !== 'Text') {
                        $toWrap[] = array("indice" => $i, "wrap" => false);

                        if ($i > 0 && $i < count($childResults) - 1) {
                            $needWrap = true;
                        }

                    }
                }

                $parentId = $firstResult ? $firstResult->id : 0;
                $depth =  $firstResult ? $firstResult->depth : 0;

                if ($needWrap && false) {

                    foreach ($toWrap as $el) {

                        if ($el['wrap'] == true) {
                            $temp = $childResults[$el['start']]->getResult();
                            $first = $temp[0];
                            $dataPage = $first->pdfPageReference;
                            $title = $first->title;
                            $number = $first->number;
                            $pageResult = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.results.Content', $title, "", $number, true, $dataPage, "");

                            $parseResult->addChildToParent($pageResult, $parentId, $depth);
                            for ($i = $el['start']; $i <= $el['end']; $i++) {
                                $parseResult->addChildrenToParent($childResults[$i]->getResult(), $pageResult->id, $pageResult->depth);
                            }
                        } else {
                            $parseResult->addChildrenToParent($childResults[$el['indice']]->getResult(), $parentId, $depth);
                        }
                    }
                } else {
                    foreach ($childResults as $r) {
                        $parseResult->addChildrenToParent($r->getResult(), $parentId, $depth);
                    }
                }

            }

            $results[] = $parseResult;
        }

        return $results;
    }

    public function createHierarchyXml($results) {
        $xml = new SimpleXMLElement('<xml/>');

        $tree = $this->prepareHierarchyTree($results);

        $this->createXml($xml, $tree);

        header('Content-type: text/xml');
        echo $xml->asXML();
    }

    private function prepareHierarchyTree($results) {
        $root = array();

        $i = 1;
        foreach ($results as $r) {
            $new = array();
            $res = $r->getResult();
            $arrayRoot = array();
            foreach ($res as $element) {
                if ($element->parent === desiderataLibrary_modules_importEPUB_importer_ChainResult::ROOT_NODE) {
                    $arrayRoot[] = $element;
                }
                if ($element->parent != $element->id) {
                    $new[$element->parent][] = $this->buildTreeElement($element);
                }
            }

            if (count($new)) {
                foreach ($arrayRoot as $rEl) {
                    $tree = $this->createTree($new, array($this->buildTreeElement($rEl)));
                    array_push($root, $tree[0]);
                }
            }
            $i++;
        }
        return $root;
    }

    private function createTree(&$list, $parent) {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['id']])) {
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    private function buildTreeElement($element) {
        return array(
            "parentId" => $element->parent,
            "depth" => $element->depth,
            "title" => $element->title,
            "subtitle" => $element->subTitle,
            "pageType" => $element->pageType,
            "ordinal" => $element->number,
            "type" => $element->type,
            "id" => $element->id,
            "hideTitle" => $element->hideTitle,
            "dataPages" => $element->pdfPageReference,
            "savable" => $element->notSavable ? false : true,
            "showInlineContent" => $element->showInlineContent
        );
    }

    private function createXml(&$root, $element) {
        foreach ($element as $el) {
            if (!$el['savable']) {

                continue;
            }
            $type = isset($el['pageType']) ? $el['pageType'] : "NoType";
            $xmlNode = $root->addChild($type);

            $xmlNode->addAttribute("ordinamento", isset($el['ordinal']) ? htmlspecialchars($el['ordinal'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "");
            $xmlNode->addAttribute("id", isset($el['id']) ? htmlspecialchars($el['id'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "");
            $xmlNode->addAttribute("titolo", isset($el['title']) ? htmlspecialchars($el['title'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "Nessun titolo");
            $xmlNode->addAttribute("sottotitolo", isset($el['subtitle']) ? htmlspecialchars($el['subtitle'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "");
            $xmlNode->addAttribute("data-pages", isset($el['dataPages']) ? htmlspecialchars($el['dataPages'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "");
            $xmlNode->addAttribute("parent", isset($el['parentId']) ? htmlspecialchars($el['parentId'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "");
            $xmlNode->addAttribute("depth", isset($el['depth']) ? htmlspecialchars($el['depth'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "");
            $xmlNode->addAttribute("showInlineContent", isset($el['showInlineContent']) ? htmlspecialchars($el['showInlineContent'], ENT_QUOTES | ENT_IGNORE, "UTF-8", true) : "");

            if (isset($el['hideTitle']) && $el['hideTitle'] == true) {
                $xmlNode->addAttribute("nascondi-titolo", 'true');
            }
            if (isset($el['children'])) {
                $this->createXml($xmlNode, $el['children']);
            }
        }
    }

    public function writeInDB($results, $pubId) {
        $this->log('PASSO 7: inizio la scrittura dei risultati sul database');
        $multisiteState = __Config::get('MULTISITE_ENABLED');
        $oldSiteId = org_glizy_ObjectValues::get('org.glizy', 'siteId');
        __Config::set('MULTISITE_ENABLED', true);
        org_glizy_ObjectValues::set('org.glizy', 'siteId', $pubId);

        $menuProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.MenuProxy');
        $contentProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
        $mediaProxy = org_glizy_ObjectFactory::createObject('org.glizycms.mediaArchive.models.proxy.MediaProxy');

        $originalPubId = $pubId;

        foreach ($results as $r) {
            $pagesResults = $r->getResult();
            $childMap = array();

            foreach ($pagesResults as $rp) {
                if ($rp->parent) {
                    if (!isset($childMap[$rp->parent])) {
                        $childMap[$rp->parent] = array();
                    }
                    $childMap[$rp->parent][] = $rp->id;
                }
            }

            $parentMap = array();

            foreach ($pagesResults as $rp) {
                if (!$rp->notSavable) {
                    if ($rp->parent === desiderataLibrary_modules_importEPUB_importer_ChainResult::ROOT_NODE || count($parentMap) === 0) {
                        $parentId = $pubId;
                    } else {
                        $parentId = $parentMap[$rp->parent];
                    }
                    //echo $rp->title . " parentFromResult: " . $rp->parent . ", parent calculated: " . $parentId . "<br>";
                    $menuId = $this->createMenuInDB($menuProxy, $contentProxy, $mediaProxy, $rp, $parentId, $childMap[$rp->id]);
                    if ($rp->title === $this->tocEl['epub-id']) {
                        $this->tocEl['menu-id'] = $menuId;
                    }
                    if (count($this->tocEl) && in_array($rp->title, array_keys($this->tocEl['resultTitles']))) {
                        $this->tocEl['conversion'][$this->tocEl['resultTitles'][$rp->title]] = $menuId;
                    }
                    $parentMap[] = $menuId;
                } else {
                    $parentMap[] = null;
                }
            }
        }

        if (count($this->tocEl)) {
        	$this->updateTOC($contentProxy, $this->tocEl);
        }

        __Config::set('MULTISITE_ENABLED', $multisiteState);
        org_glizy_ObjectValues::set('org.glizy', 'siteId', $oldSiteId);

        $this->log('Scrittura sul database terminata!');
        $this->log('=== FINE PROCEDURA DI PARSING');
    }

    public function writeText($results, $pubId) {
        foreach ($results as $r) {
            $pagesResults = $r->getResult();

            $parentMap = array();
            foreach ($pagesResults as $rp) {
                echo $rp->title . '<br>';
                if (property_exists($rp, 'text')) {
                    echo $rp->text . '<br>';
                }
            }
        }
    }

    private function createMenuInDB($menuProxy, $contentProxy, $mediaProxy, $result, $parentId, $childIds)
    {
        $title = $result->title;

        $menuId = $menuProxy->addMenu($title, $parentId, $result->pageType);

        $resultJson = $result->toJson();
        $resultJson->__url = '';
        $resultJson->__comment = '';
        $resultJson->tags = array();
        $resultJson->refId = gruppometa_easybook_Easybook::getPublicationGuid() . '_' . uniqid('', true);
        $resultJson->viewLayout = $this->updateViewLayout($resultJson, $childIds);

        if (property_exists($resultJson, 'inlineImages')) {

            foreach ($resultJson->inlineImages as $inlineImg) {

                $newPath = $this->addInlineImage($mediaProxy, $inlineImg);
                if ($newPath != null) {
                    $srcParts = pathinfo($inlineImg->src);
                    //echo $srcParts.'<br />';
                    if ($srcParts['dirname'] != ".") {
                        $source = $srcParts['dirname']."/".rawurlencode($srcParts['basename']);
                    } else {
                        $source = rawurlencode($srcParts['basename']);
                    }
                    //echo $source . '<br />';
                    $resultJson->text = str_replace($source,
                        $newPath, $resultJson->text);
                }
            }
        }

        if (property_exists($resultJson, 'image') && is_object($resultJson->image)) {
            $resultJson->image = $this->addMedia($mediaProxy, $resultJson->image);
        } else if (property_exists($resultJson, 'images') && $resultJson->images) {
            $tempImage = new StdClass;
            $tempImage->image = array();
            $tempImage->refId = array();
            foreach ($resultJson->images as $image) {
                $tempImage->image[] = $this->addMedia($mediaProxy, $image);
                $tempImage->refId[] = gruppometa_easybook_Easybook::getPublicationGuid() . '_' . uniqid('', true);
            }
            $resultJson->images = $tempImage;
        }

        if (!$resultJson->__title) {
            $resultJson->__title = 'Titolo mancante';
            $resultJson->hideTitle = true;
        }

        //$resultJson->__title = htmlspecialchars($resultJson->__title, ENT_QUOTES | ENT_IGNORE, "UTF-8", true);

        $contentVO = $contentProxy->getContentVO();
        $contentVO->setId($menuId);
        $contentVO->setFromJson($resultJson);

        $contentProxy->saveContent($contentVO, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'),
            __Config::get('glizycms.content.history'));

        return $menuId;
    }

    private function updateTOC($contentProxy, $tocMap) {
        /** @var org_glizycms_contents_models_proxy_ContentProxy $contentProxy */
        $contentVO = $contentProxy->readContentFromMenu($tocMap['menu-id'], org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'));
        foreach ($tocMap['conversion'] as $key => $value) {
            $contentVO->text = str_replace($key, 'internal:' . $value, $contentVO->text);
        }
        $contentProxy->saveContent($contentVO, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'),
            __Config::get('glizycms.content.history'));
    }

    private function updateViewLayout($element, $childs) {
        $currentViewLayout = $element->viewLayout;

        $textExist = $element->text;
        $imageExist = $element->images && count($element->images) > 0;

        if (!$textExist) {
            if ($imageExist) {
                return "layout_1_1 only_images";
            }

        } else {
            if (!$imageExist) {
                return "layout_1_1 only_text";
            }
        }

        return $currentViewLayout;
    }


    private function addInlineImage($mediaProxy, $mediaInfo) {
        $this->buildPathMedia($mediaInfo);
        if ($mediaInfo->hdSourcePath || $mediaInfo->thumbSourcePath) {
            $src = $mediaInfo->hdSourcePath ? $mediaInfo->hdSourcePath : $mediaInfo->thumbSourcePath;
            //echo "Source: ".$src."<br />";
            $rSmall = null;
            $mediaHd = new StdClass();
            $pathInfoHd = pathinfo($src);
            $mediaHd->media_title = $pathInfoHd['filename'];
            $mediaHd->__originalFileName = $pathInfoHd['basename'];
            $mediaHd->__filePath = $src;
            if ($mediaInfo->hdSourcePath && $mediaInfo->thumbSourcePath) {
                $mediaHd->__filePathThumb = $mediaInfo->thumbSourcePath;
            }
            $rHd = $this->saveMedia($mediaProxy, $mediaHd);
            list($width, $height) = getimagesize($mediaInfo->thumbSourcePath);
            return 'getImage.php?id=' . $rHd . '&w=' . $width . '&h=' . $height;
        } else {
            return null;
        }
    }

    private function addMedia($mediaProxy, $mediaInfo) {
        $this->buildPathMedia($mediaInfo);

        if ($mediaInfo->hdSourcePath || $mediaInfo->thumbSourcePath) {
            $src = $mediaInfo->hdSourcePath ? $mediaInfo->hdSourcePath : $mediaInfo->thumbSourcePath;
            $pathInfo = pathinfo($src);
            $media = new StdClass();
            $media->media_title = $pathInfo['filename'];
            $media->__originalFileName = $pathInfo['basename'];
            $media->__filePath = $src;

            $r = $this->saveMedia($mediaProxy, $media);

            $jsonData = array(
                'id' => $r,
                'title' => '',
                'description' => $mediaInfo->caption,
                'src' => 'getImage.php?id=' . $r . '&w=150&h=150&c=1&co=0&f=0&t=1',
            );
            return json_encode($jsonData);
        } else {
            return '';
        }
    }

    private function buildPathMedia(&$mediaInfo) {
        if (!file_exists($mediaInfo->path . '/' . $mediaInfo->hdSrc) || is_dir($mediaInfo->path . '/' . $mediaInfo->hdSrc)) {
            $hdSrc = $this->epubFolder . "/hdimg/" . basename($mediaInfo->hdSrc);
            if (file_exists($hdSrc) && !is_dir($hdSrc)) {
                $mediaInfo->hdSourcePath = $hdSrc;
                //echo("Media alta definizione trovata in " . $mediaInfo->hdSourcePath.'<br />');
            } else {
                //echo("Media alta definizione non trovata in " . $hdSrc.'<br />');
                $mediaInfo->hdSourcePath = null;
            }
        } else {
            $mediaInfo->hdSourcePath = $mediaInfo->path . '/' . $mediaInfo->hdSrc;
            //echo ("Media alta definizione trovata in " . $mediaInfo->hdSourcePath.'<br />');
        }

        if (file_exists($mediaInfo->path . '/' . $mediaInfo->src) && !is_dir($mediaInfo->path . '/' . $mediaInfo->src)) {
            $mediaInfo->thumbSourcePath = $mediaInfo->path . '/' . $mediaInfo->src;
            //echo ("Media bassa definizione trovata in " . $mediaInfo->path . '/' . $mediaInfo->src.'<br />');
        } else {
            $mediaInfo->thumbSourcePath = null;
            //echo ("Media bassa definizione non trovata in " . $mediaInfo->path . '/' . $mediaInfo->src.'<br />');
        }
    }

    private function saveMedia($mediaProxy, $media) {
        $ar = org_glizy_ObjectFactory::createModel('org.glizycms.models.Media');
        $md5 = md5_file(realpath($media->__filePath));

        $ar->emptyRecord();
        $result = $ar->find(array('media_md5' => $md5));

        if ($result) {
            //echo "Immagine già nel database. ID: " . $ar->media_id . "<img src='getImage.php?id=" . $ar->media_id . ".jpg'/><br />";
            return $ar->media_id;
        } else {
            $media->media_tags = '';
            $id = $mediaProxy->saveMedia($media, org_glizycms_mediaArchive_models_proxy_MediaProxy::COPY_TO_CMS);
            //var_dump($id);
            //echo "<br />";
            //echo "Immagine NON presente nel DATABASE. ID: " .  $id . "<img src='getImage.php?id=" . $id . ".jpg'/><br />";
            return $id;
        }
    }

    private function parseFile(desiderataLibrary_modules_importEPUB_importer_TocItem $tocItem) {
        if ($tocItem->startNode && $tocItem->startNode->hasAttribute('id')) {
            $this->log("Inizio parsing: $tocItem->absFile dall'id: " . $tocItem->startNode->getAttribute('id'));
        }

        $result = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.ChainResult');

        /*if(!$tocItem->startNode)
            return $result;*/
        $tocItem->refreshStartNode();

        try {
            if ($tocItem->startNode && $tocItem->startNode->nodeName != 'body' && $tocItem->startNode->getAttribute('id') === $tocItem->endPoint) {
                $pageResult = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.results.Content', $tocItem->title, "", "", false, $tocItem->dataPages[0], "");
                $result->setResultAndBreak($pageResult);
            } else {
                if(!$tocItem->startNode)
                    //echo "StartNode nullo!<br />";
                    return $result;

                if ($tocItem->startNode->hasAttribute('data-is-wrapped')) {
                    $tocItem->startNode = $tocItem->startNode->parentNode;
                }

                $this->startChain($tocItem, $result, $this->tocMap->getDomFromToc($tocItem), $tocItem->startNode, 0, true);
            }
            if ($this->config->hasMissedContent()) {
                $this->restartChain($tocItem, $result);
            }
        } catch(Exception $e){
            $this->log('ID: '.$tocItem->id);
            $this->log('HASH: '.$tocItem->hash);
            $this->log($e->getMessage());
        }
        return $result;
    }

    private function getChain() {
        $chain = array();
        $customFix = $this->config->getCustomFix();

        $chain[] = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.chain.standardChain.EndPoint');
            foreach ($customFix as $fix) {
                $chain[] = org_glizy_objectFactory::createObject($fix);
            }
        $chain[] = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.chain.customChain.FixImageTag');
        $chain[] = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.chain.standardChain.StartPoint');
        return $chain;
    }

    private function startChain(desiderataLibrary_modules_importEPUB_importer_TocItem $tocItem, desiderataLibrary_modules_importEPUB_importer_ChainResult $result, \DomDocument $dom, \DomNode $domContext = null, $p = 0, $firstChain = false) {

        $this->log('Depth: ' . $p);
        $this->log(str_pad('', $p * 2, '.') . ' start startChain from: ' . $domContext->nodeName . ", id: " . $domContext->getAttribute('id'). "in file:".$tocItem->absFile);
        $chain = $this->getChain();

        $params = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.ChainParams', $dom, $tocItem, $domContext, $this->sectionMap);

        foreach ($chain as $c) {
            $this->log(str_pad('', $p * 2, ' ') . 'exec ' . get_class($c));
            $c->setConfig($this->config);
            $c->execute($params, $result, $this->tocEl);
            if ($result->status === desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_BREAK || $result->status === desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_STOP_CHILD) {
                break;
            } else if ($result->status === desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_CHILD) {
                $d = $result->getChildDom();

                $childNodes = $d->childNodes;

                $result->increaseParent();
                $this->log(str_pad('', $p * 2, ' ') . '>' . $d->nodeName . ' ' . $d->getAttribute('id') . ' #' . $childNodes->length);
                $allowTag = array('aside', 'section', 'div');
                $i = 0;
                foreach ($childNodes as $node) {
                    $this->log($i . ") Nome figlio:" . $node->nodeName);
                    $i++;
                    if (!in_array($node->nodeName, $allowTag)) {
                        continue;
                    }

                    if ($node->nodeName == 'div' && $node->getAttribute('class') != 'backcolor') {
                        continue;
                    }

                    $this->log(str_pad('', ($p + 1) * 2, ' ') . '#' . $node->nodeName . ' ' . $node->getAttribute('id'));
                    $this->startChain($tocItem, $result, $dom, $node, $p + 1);
                    if ($result->status === desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_STOP_CHILD) {
                        $this->log("STOPPED BY ENDPOINT");
                        break;
                    }
                    $result->status = desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_RUNNING;
                }
                $result->decreaseParent();
                $this->log(str_pad('', $p * 2, ' ') . '<' . $d->nodeName . ' ' . $d->getAttribute('id'));
                break;
            } else {

            }
        }

        $this->log(str_pad('', $p * 2, '.') . 'end startChain, result status: ' . $result->status);

        /*if ($firstChain && $result->status !== desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_STOP_CHILD) {
            $nextSibling = $params->xpath->query('following-sibling::xhtml:section[1]', $domContext)->item(0);
            $nextAside = $params->xpath->query('following-sibling::xhtml:aside[1]', $domContext)->item(0);
            if ($nextSibling) {
                $result->status = desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_RUNNING;
                $this->log(str_pad('', $p * 2, '.') . 'Start chain on brothers: ' . $nextSibling->getAttribute('id') . "<br>");
                $this->startChain($tocItem, $result, $dom, $nextSibling, $p, $firstChain);
            }
            if ($nextAside) {
                $result->status = desiderataLibrary_modules_importEPUB_importer_ChainResult::STATE_RUNNING;
                $this->log(str_pad('', $p * 2, '.') . 'Start chain on brothers: ' . $nextAside->getAttribute('id') . "<br>");
                $this->startChain($tocItem, $result, $dom, $nextAside, $p, $firstChain);
            }
        }*/
    }

    private function debugIndex($results) {
        foreach ($results as $r) {
            $this->dumpResult($r->getResult());
        }
    }

    private function debugResults($results) {
        foreach ($results as $r) {
            $this->printResultStyled($r->getResult());
        }
    }

    private function dumpResult($result) {
        echo "-----";
        foreach ($result as $r) {
            echo '<p >--' . $r->depth . '--<span style="margin-left:' . (20 * $r->depth) . '"> - ' . $r->number . ' ' . $r->title . ' ' . $r->subTitle . ' ' . $r->parent . ' ' . get_class($r) . '</span><p>';
        }
    }

    private function printResultStyled($result) {
        foreach ($result as $r) {
            if (!$r->notSavable) {
                $r->printComponent();
                echo "\n";
            }
        }
    }

    private function log($message) {
        if ($this->showLog) {
            $this->importLog .= $message . PHP_EOL . "\n";
        }
    }

    public function getLog() {
        return $this->importLog;
    }

}

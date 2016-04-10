<?php

class desiderataLibrary_modules_importEPUB_importer_TocMap {

    private $showLog = false;
    private $map = array();
    private $tocPath = "";
    private $epubFolder = "";
    private $xpathMap = array();
    private $xhtmlMap = array();
    private $countMap = array();
    private $xpath;

    public function __construct($epubFolder, $tocPath) {
        $this->tocPath = $tocPath;
        $this->epubFolder = $epubFolder;
    }

    public function setShowLog($state) {
        $this->showLog = $state;
    }

    public function loadToc(&$logFile) {
        $this->log('PASSO 5: carico l\'indice...', $logFile);
        $xml = new DomDocument();
        $xml->load($this->tocPath);
        $this->log('Indice caricato!', $logFile);
        $this->xpath = new DOMXPath($xml);
        $this->xpath->registerNamespace('xmlns', 'http://www.daisy.org/z3986/2005/ncx/');
        $navMapNode = $xml->getElementsByTagName("navMap")->item(0);
        $c = 0;
        foreach ($navMapNode->childNodes as $navPoint) {
            if ($navPoint->nodeName == 'navPoint') {
                $c++;
                $item = $this->extractHierarchy($navPoint, $c);
                array_push($this->map, $item);
            }
        }
        $this->log('Stampo l\'indice caricato:', $logFile);
        $printedMap = $this->printMap();
        $this->log($printedMap, $logFile);
    }

    public function loadTocWithSpine(&$spineMap, &$logFile) {
        $this->log('PASSO 5: carico l\'indice insieme alla spine...', $logFile);
        $xml = new DomDocument();
        $xml->load($this->tocPath);
        $this->log('Indice caricato!', $logFile);
        $this->xpath = new DOMXPath($xml);
        $this->xpath->registerNamespace('xmlns', 'http://www.daisy.org/z3986/2005/ncx/');
        $navMapNode = $xml->getElementsByTagName("navMap")->item(0);
        $c = 0;
        $this->log('ELENCO ELEMENTI NON PRESENTI NEL TOC:', $logFile);
        foreach ($spineMap as $key => $array) {
            $pathInfo = pathinfo($key);
            $encode = rawurlencode($pathInfo['basename']);
            $dir = $pathInfo['dirname'];
            if ($dir != ".") {
                $encodedSrc = $dir . "/" . $encode;
            } else {
                $encodedSrc = $encode;
            }
            $match = $this->xpath->query("//xmlns:navPoint[child::xmlns:content[contains(@src, \"$key\")]] | //xmlns:navPoint[child::xmlns:content[@src=\"$encodedSrc\"]]")->item(0);
            if (!$match) {
                $this->log($key . ' (' . $encodedSrc . ')', $logFile);
                $listOfLast = $this->xpath->query("//xmlns:navPoint[position()=last()]");
                $itemLast = $listOfLast->item($listOfLast->length-1);
                $lastId = $itemLast->getAttribute('id');
                $lastPlayOrder = $itemLast->getAttribute('playOrder');
                $navPoint = $xml->createElement("navPoint");
                $navPoint->setAttribute('id', $lastId.'_extra'.$c);
                $navPoint->setAttribute('playOrder', $lastPlayOrder + $c);
                $navLabel = $xml->createElement('navLabel');
                $text = $xml->createElement('text', pathinfo($key, PATHINFO_FILENAME));
                $navLabel->appendChild($text);
                $content = $xml->createElement('content');
                $content->setAttribute('src', $key);
                $navPoint->appendChild($navLabel);
                $navPoint->appendChild($content);
                $nextSrc = $array['next'];
                $next = null;
                if ($nextSrc != "") {
                    $next = $this->xpath->query("//xmlns:navPoint[child::xmlns:content[@src=\"$nextSrc\"]]")->item(0);
                }
                /*$prevSrc = $array['prev'];
                $prev = null;
                if ($prevSrc != "") {
                    $prev = $this->xpath->query("//xmlns:navPoint[child::xmlns:content[@src='$prevSrc']]")->item(0);
                }*/
                $nextTocSrc = $array['nextToc'];
                $nextToc = $this->xpath->query("//xmlns:navPoint[child::xmlns:content[@src=\"$nextTocSrc\"]]")->item(0);
                $prevTocSrc = $array['prevToc'];
                $prevToc = $this->xpath->query("//xmlns:navPoint[child::xmlns:content[@src=\"$prevTocSrc\"]]")->item(0);
                if ($next) {
                    $this->log("Devo spostare " . $navPoint->getAttribute("id") . " prima di " . $nextSrc, $logFile);
                    $next->parentNode->insertBefore($navPoint, $next);
                } else if ($nextToc){
                    $this->log("Devo spostare " . $navPoint->getAttribute("id") . " prima di " . $nextTocSrc, $logFile);
                    $nextToc->parentNode->insertBefore($navPoint, $nextToc);
                } else {
                    $this->log("Devo spostare " . $navPoint->getAttribute("id") . " in coda ai figli del nodo padre di " . $prevTocSrc, $logFile);
                    $prevToc->parentNode->appendChild($navPoint);
                }
                $c++;
            }
        }
        $c = 0;
        foreach ($navMapNode->childNodes as $navPoint) {
            if ($navPoint->nodeName == 'navPoint') {
                $c++;
                $item = $this->extractHierarchy($navPoint, $c);
                $this->populateSpineMap($spineMap, $item);
                array_push($this->map, $item);
            }
        }
        $lastTocItem = end($this->map);
        $last = $this->depthChecking($lastTocItem);
        $this->createExtraNavPoint($last, $spineMap);
        $this->log('Stampo l\'indice caricato:', $logFile);
        $printedMap = $this->printMap();
        $this->log($printedMap, $logFile);
    }

    /*
     * extractHierarchy: crea e inizializza un TocItem per ogni navPoint del toc, quindi ne setta i figli
     * in gerarchia diretta.
     */
    private function extractHierarchy($node, &$c) {
        $toc = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.TocItem');
        if (is_array($node)) {
            $toc->setFalseNavPoint($this->epubFolder, $node[0], $node[1], $node[2], $node[3]);
        } else {
            $toc->setFromNavPoint($this->epubFolder, $node);
        }
        if (!$this->xpathMap[$toc->absFile]) {
            $xml = new DomDocument();
            libxml_use_internal_errors(true);
            $xml->loadHTMLFile(realpath(urldecode($toc->absFile)));
            $xpath = new DOMXpath($xml);
            $xpath->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
            $xpath->registerNamespace('epub', 'http://www.idpf.org/2007/ops');
            $this->xpathMap[$toc->absFile] = $xpath;
            $this->xhtmlMap[$toc->absFile] = $xml;
            $this->countMap[$toc->absFile] = 0;
        }

        $endToc = $this->findNavPointFromPlayOrder($toc->playOrder + 1);

        if ($endToc) {
            $this->fixTocOrderOnXhtml($toc, $endToc);
        }

        $this->countMap[$toc->absFile]++;
        $toc->setStartPoint($this->xpathMap[$toc->absFile]);
        $toc->setEndPoint($this->xpathMap[$toc->absFile], $endToc, $this->countMap[$toc->absFile]);

        if (!is_array($node)) {
            $childs = array();
            foreach ($node->childNodes as $child) {
                if ($child->nodeName == 'navPoint') {
                    $item = $this->extractHierarchy($child, $c);
                    if ($item->file != $toc->file) {
                        $c++;
                    }
                    array_push($childs, $item);
                }
            }
            $toc->setChilds($childs);
        }

        return $toc;
    }

    private function findNavPointFromPlayOrder($playOrder) {
        $navPoint = $this->xpath->query("//xmlns:navPoint[@playOrder='$playOrder']")->item(0);
        if (!$navPoint) {
            return null;
        }
        $toc = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.TocItem');
        $toc->setFromNavPoint($this->epubFolder, $navPoint);
        return $toc;
    }

    private function fixTocOrderOnXhtml(desiderataLibrary_modules_importEPUB_importer_TocItem $startToc, desiderataLibrary_modules_importEPUB_importer_TocItem $endToc) {
        if ($startToc->absFile !== $endToc->absFile)
            return;
        $xpath = $this->xpathMap[$startToc->absFile];
        $nodeStart = $startToc->getNodeFromId($xpath, $startToc->hash);
        $nodeEnd = $endToc->getNodeFromId($xpath, $endToc->hash);

        if (!$nodeStart || !$nodeEnd)
            return;

        if ($nodeStart->getAttribute('id') == $nodeEnd->getAttribute('id'))
            return;

        //Controllo se il nodo end ha come figlio il nodo start
        $result = $xpath->query(".//*[@id='" . $nodeStart->getAttribute('id') . "']", $nodeEnd);

        if ($result && $result->length > 0) {
            // echo "Devo invertire: ".$startToc->hash." con ".$endToc->hash."<br>";
            $nodeHeaderStart = $xpath->query(".//xhtml:header", $nodeStart)->item(0);
            $nodeHeaderEnd = $xpath->query(".//xhtml:header", $nodeEnd)->item(0);

            $this->moveNodeTo($nodeHeaderStart, $nodeEnd);
            $this->moveNodeTo($nodeHeaderEnd, $nodeStart);
        }

    }

    private function moveNodeTo($node, $dest) {
        $newNode = $node->cloneNode(true);
        $dest->appendChild($newNode);
        $node->parentNode->removeChild($node);
    }

    private function createExtraNavPoint($lastNavPoint, $spineMap) {
        $params = array();
        $i = 0;
        foreach ($spineMap as $key => $value) {
            if ($value == false) {
                $i++;
                $params[0] = pathinfo($key, PATHINFO_BASENAME);
                $params[1] = $key;
                $params[2] = 'nav'.(trim($lastNavPoint->id, 'nav') . $i);
                $params[3] = $lastNavPoint->playOrder + $i;
                $item = $this->extractHierarchy($params, $i);
                array_push($this->map, $item);
            }
        }
    }

    /*
     * populateSpineMap: genera una mappa della spine del libro
     */
    private function populateSpineMap(&$spineMap, desiderataLibrary_modules_importEPUB_importer_TocItem $tocItem) {
        foreach ($spineMap as $key => $value) {
            if (urldecode(strtolower($tocItem->file)) == urldecode(strtolower($key)) && $value == false) {
                $spineMap[$key]["status"] = true;
            }
            $this->childrenChecking($spineMap, $key, $value, $tocItem);
        }
    }

    private function childrenChecking(&$map, &$key, &$value, $node) {
        foreach ($node->childs as $child) {
            if (urldecode(strtolower($child->file)) == urldecode(strtolower($key)) && $value == false) {
                $map[$key]["status"] = true;
            }
            if (count($child->childs)>0) {
                $this->childrenChecking($map, $key, $value, $child);
            }
        }
    }

    private function depthChecking(desiderataLibrary_modules_importEPUB_importer_TocItem $tocItem) {
        if ($tocItem->haveChild()) {
            return $this->depthChecking(end($tocItem->childs));
        }
        return $tocItem;
    }

    /*
     * Getters
     */
    public function getNavMap() {
        return $this->map;
    }

    public function getXpathMap() {
        return $this->xpathMap;
    }

    public function getDomFromToc(desiderataLibrary_modules_importEPUB_importer_TocItem $toc) {
        return $this->xhtmlMap[$toc->absFile];
    }

    /*
     * Metodi di ausilio
     */
    public function toString() {
        $obj = new stdClass();
        $obj->map = array();
        $i = 0;
        foreach ($this->map as $toc) {
            $obj->map[$i] = $toc->toString();
            $i++;
        }
        return $obj;
    }

    public function printMap() {
        $tocItemMap = $this->map;
        $i = 0;
        $map = "Composizione della mappa del toc:\n\n";
        $map .= 'Elementi: '.count($tocItemMap)."\n";
        foreach ($tocItemMap as $tocItem) {
            $i++;
            $map .= "=== ELEMENTO PADRE ====\n";
            $map .= 'ID:'.$tocItem->id."\n";
            $map .= 'Title:'.$tocItem->title."\n";
            $map .= 'Number:'.$tocItem->number."\n";
            $map .= 'PlayOrder:'.$tocItem->playOrder."\n";
            $map .= 'File:'.$tocItem->file."\n";
            $map .= 'AbsFile:'.$tocItem->absFile."\n";
            $map .= 'Hash:'.$tocItem->hash."\n";
            if ($tocItem->startNode && $tocItem->startNode->hasAttribute('id')) {
                $map .= 'StartNode:'.$tocItem->startNode->getAttribute('id')."\n";
            }
            if ($tocItem->endPoint) {
                $map .= 'EndPoint:'.$tocItem->endPoint."\n";
            }
            if ($tocItem->haveChild()){
                $map .= $tocItem->printChildren();
            }
        }
        return $map;
    }

    private function log($message, &$logFile) {
        if ($this->showLog) {
            $logFile .= $message . PHP_EOL . "\n";
        }
    }
}

<?php

// Mappa degli elementi presenti nella spine del content.opf

class desiderataLibrary_modules_importEPUB_importer_SpineMap {

    // Attributes
    private $showLog = false;
    private $epubFolder = "";
    private $contentPath = "";
    private $xpath;
    private $id = "";
    private $idrefMap = array();

    // Methods
    public function __construct($epubFolder, $contentPath) {
        $this->epubFolder = $epubFolder;
        $this->contentPath = $contentPath;
    }

    public function setShowLog($state) {
        $this->showLog = $state;
    }

    public function loadSpine($tocFile, &$logFile, &$tocEl) {
        $xml = new DomDocument();
        $this->log('PASSO 4.2: carico la spine della pubblicazione...', $logFile);
        $xml->load($this->contentPath);
        $this->log('Spine caricata!', $logFile);
        $this->xpath = new DOMXPath($xml);
        $this->xpath->registerNamespace('xmlns', 'http://www.idpf.org/2007/opf');
        $itemrefNodeList = $xml->getElementsByTagName("itemref");
        $tocRef = $this->xpath->query("//xmlns:reference[@type=\"toc\"]/@href")->item(0);
        if ($tocRef) {
            $tocEl['epub-id'] = pathinfo($this->xpath->query("//xmlns:item[@href=\"$tocRef->nodeValue\"]/@id")->item(0)->nodeValue, PATHINFO_FILENAME);
        }
        $toc = new DOMDocument();
        $this->log('PASSO 4.3: carico l\'indice per il confronto...', $logFile);
        $toc->load($tocFile);
        $this->log('Indice caricato!', $logFile);
        $tocXP = new DOMXPath($toc);
        $tocXP->registerNamespace('xmlns', 'http://www.daisy.org/z3986/2005/ncx/');
        $prevToc = "";
        $nextToc = "";
        foreach ($itemrefNodeList as $itemrefNode) {
            $this->id = $itemrefNode->getAttribute('idref');
            $src = $this->xpath->query("//xmlns:item[@id=\"$this->id\"]/@href")->item(0)->value;
            $pathInfo = pathinfo($src);
            $dir = $pathInfo['dirname'];
            $nextIdRef = $this->xpath->query("./following-sibling::xmlns:itemref[1]/@idref", $itemrefNode)->item(0)->nodeValue;
            $nextHref = $this->xpath->query("//xmlns:item[@id=\"$nextIdRef\"]/@href")->item(0)->nodeValue;
            $prevIdRef = $this->xpath->query("./preceding-sibling::xmlns:itemref[1]/@idref", $itemrefNode)->item(0)->nodeValue;
            $prevHref = $this->xpath->query("//xmlns:item[@id=\"$prevIdRef\"]/@href")->item(0)->nodeValue;
            $encode = rawurlencode($pathInfo['basename']);
            if ($dir != ".") {
                $encodedSrc = $dir . "/" . $encode;
            } else {
                $encodedSrc = $encode;
            }
            $match = $tocXP->query("//xmlns:navPoint[child::xmlns:content[contains(@src, \"$src\")]] | //xmlns:navPoint[child::xmlns:content[@src=\"$encodedSrc\"]]")->item(0);
            if ($match) {
                $prevToc = $src;
                $nextToc = $tocXP->query("following-sibling::xmlns:navPoint/xmlns:content/@src", $match)->item(0)->nodeValue;
                if (!$nextToc) {
                    $nextToc = $tocXP->query("./descendant::xmlns:navPoint[1]/xmlns:content/@src", $match)->item(0)->nodeValue;
                    //echo $nextToc . '<br />';
                }
            } else {
                if ($nextToc == "" && $prevToc == "") {
                    $nextToc = $tocXP->query("//xmlns:navPoint[1]/xmlns:content/@src")->item(0)->nodeValue;
                    //echo $nextToc . '<br />';
                }
            }
            if ($dir == ".") {
                //$newSrc = rawurlencode(pathinfo($src, PATHINFO_BASENAME));
                $newSrc = pathinfo($src, PATHINFO_BASENAME);
                $this->idrefMap[$newSrc] = array("status" => false, "prev" => $prevHref, "next" => $nextHref, "prevToc" => $prevToc, "nextToc" => $nextToc);
            } else {
                //$newSrc = pathinfo($src, PATHINFO_DIRNAME) . "/" . rawurlencode(pathinfo($src, PATHINFO_BASENAME));
                $newSrc = pathinfo($src, PATHINFO_DIRNAME) . "/" . pathinfo($src, PATHINFO_BASENAME);
                $this->idrefMap[$newSrc] = array("status" => false, "prev" => $prevHref, "next" => $nextHref, "prevToc" => $prevToc, "nextToc" => $nextToc);
            }
        }
        $this->log('STAMPO LA MAPPA DELLA SPINE', $logFile);
        $printedMap = $this->printidrefMap();
        $this->log($printedMap, $logFile);
    }

    public function getidrefMap() {
        return $this->idrefMap;
    }

    public function printidrefMap() {
        $map = "Composizione della mappa della spine:\n";
        $i = 0;
        foreach ($this->idrefMap as $contentPath => $array) {
            $i++;
            $map .= "$i) Path:".$contentPath."\n\n";
            $map .= "Previous item: " . $array["prev"] . "\n";
            $map .= "Next item: " . $array["next"] . "\n";
            $map .= "Previous tocItem: " . $array["prevToc"] . "\n";
            $map .= "Next tocItem: " . $array["nextToc"] . "\n";
        }
        return $map;
    }

    private function log($message, &$logFile) {
        if ($this->showLog) {
            $logFile .= $message . PHP_EOL . "\n";
        }
    }
}


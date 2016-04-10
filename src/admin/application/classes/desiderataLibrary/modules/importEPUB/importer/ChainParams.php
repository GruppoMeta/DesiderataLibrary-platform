<?php
class desiderataLibrary_modules_importEPUB_importer_ChainParams
{
    public $title;
    public $number;
    public $dom;
    public $domContext;
    public $xpath;
    public $folder;

    public $stopAtId;

    public $sectionMap;
    public $filePath;

    function __construct(\DomDocument $dom, desiderataLibrary_modules_importEPUB_importer_TocItem $toc, \DomNode $domContext=null, $sectionMap)
    {
        $this->title = $toc->title;
        $this->number = $toc->number;
        $this->folder = $toc->getFolder();
        $this->dom = $dom;
        $this->domContext = $domContext;
        $this->stopAtId = $toc->endPoint;
        $this->xpath = new DOMXpath($dom);
        $this->xpath->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
        $this->xpath->registerNamespace('epub', 'http://www.idpf.org/2007/ops');
        $this->xpath->preserveWhiteSpace = false;
        $this->sectionMap = $sectionMap;
        $this->filePath = $toc->file;
    }
}

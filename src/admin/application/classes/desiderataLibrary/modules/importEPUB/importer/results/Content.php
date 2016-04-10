<?php
class desiderataLibrary_modules_importEPUB_importer_results_Content extends desiderataLibrary_modules_importEPUB_importer_results_BasePage
{
    public $hideTitle;
    public $showInlineContent = false;

    function __construct($title, $subTitle, $number, $hideTitle, $pdfPage, $epubType)
    {
        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->number = $number;
        $this->pageType = 'Content';
        $this->hideTitle = $hideTitle;
        $this->epubType = $epubType;
        $this->pdfPageReference = $pdfPage;
    }

    public function toJson()
    {
        $json = new StdClass;
        $json->__title = $this->title;
        $json->subtitle = $this->subTitle;
        $json->number = $this->number;
        $json->pageType = $this->pageType;
        $json->hideTitle = $this->hideTitle ? 1 : 0;
        $json->pdfPage = $this->pdfPageReference;
        $json->epubType = $this->epubType;
        $json->showInlineContent = $this->showInlineContent;
        return $json;
    }

    public function setShowInlineContent() {
        $this->showInlineContent = true;
    }
}
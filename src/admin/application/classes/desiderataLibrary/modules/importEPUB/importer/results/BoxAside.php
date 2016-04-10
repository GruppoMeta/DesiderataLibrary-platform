<?php

class desiderataLibrary_modules_importEPUB_importer_results_BoxAside extends desiderataLibrary_modules_importEPUB_importer_results_BasePage {

    public $hideTitle;
    public $layout;
    public $viewType;
    public $text;
    public $filePath;

    public $htmlID = '';
    public $linkToTranslate = array();

    // NOTA BoxAside non può avere immagini ma solo allegati
    function __construct($title, $subTitle, $number, $hideTitle, $layout,$viewType, $text, $images=array(), $epubType) {
        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->number = $number;
        $this->pageType = 'BoxAside';
        $this->hideTitle = $hideTitle;
        $this->layout = $layout;
         $this->viewType = $viewType;
        $this->text = $text;
         $this->images = $images;
         $this->epubType = $epubType;
    }

    public function addLink($from) {
            array_push($this->linkToTranslate, array("from" => $from, "to" => null));
    }

    public function toJson() {
        $json = new StdClass;
        $json->__title = $this->title;
        $json->subtitle = $this->subTitle;
        $json->number = $this->number;
        $json->hideTitle = $this->hideTitle ? 1 : 0;
        $json->viewLayout = $this->layout;
         $json->viewStyle = $this->viewType;
         $json->pageType = $this->pageType;
        $json->text = $this->text;
         $json->images = $this->images;
         $json->inlineImages = $this->inlineImages;

        $json->epubType = $this->epubType;
        return $json;
    }

    public function printComponent() {
        $html = "<p>$this->text</p>";
        $html .="<hr>";
        $html .= "<b>Immagini:</b> " . (count($this->images));
        for ($i = 0; $i < count($this->images); $i++) {
            $img = $this->images[$i];
            $html.="<p><i>";
            $html.= ($i + 1) . ")";
            $html.= $img->toString();
            $html.="<br>";
            $html.="</i></p>";
        }
        $html.= "<p>Layout: " . $this->layout . "</p>";
        $html.= "<p>ViewType: " . $this->viewType . "</p>";
        $html.= "<p>hideTitle: " . $this->hideTitle . "</p>";
        parent::printComponent($html);
    }

}

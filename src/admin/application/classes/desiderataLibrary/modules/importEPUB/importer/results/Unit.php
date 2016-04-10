<?php
class desiderataLibrary_modules_importEPUB_importer_results_Unit extends desiderataLibrary_modules_importEPUB_importer_results_BasePage {
    public $type;
    public $image;

    function __construct($title, $subTitle, $number, $image, $pdfPageReference, $epubType = "")
    {
        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->number = $number;
        $this->pageType = 'Empty';
        $this->type = 'unit';
        $this->image = $image;
        $this->pdfPageReference = $pdfPageReference;
        $this->epubType = $epubType;
    }

    public function toJson()
    {
        $json = new StdClass;
        $json->__title = $this->title;
        $json->subtitle = $this->subTitle;
        $json->number = $this->number;
        $json->type = $this->type;
        $json->image = $this->image;
        $json->pdfPage = $this->pdfPageReference;
        $json->epubType = $this->epubType;
        return $json;
    }

    public function printComponent($html = ""){
        $html .= "<p><b>Immagine unità:</b> ";
        if($this->image!=null)
            $html.=$this->image->toString();
        else
            $html.= " ASSENTE";
        $html.="</p>";
        $html.= "<b>type:</b> ".$this->type;

        parent::printComponent($html);
    }
}
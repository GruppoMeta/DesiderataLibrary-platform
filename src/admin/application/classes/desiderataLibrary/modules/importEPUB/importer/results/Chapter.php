<?php
class desiderataLibrary_modules_importEPUB_importer_results_Chapter extends desiderataLibrary_modules_importEPUB_importer_results_Unit
{
    private $chapterType;
    function __construct($title, $subTitle, $number, $image,$chapterType = 'chapter', $pdfPageReference, $epubType = "")
    {
        parent::__construct($title, $subTitle, $number, $image,$pdfPageReference, $epubType);
        $this->type = 'chapter';
        $this->chapterType = $chapterType;
    }

    public function printComponent(){

        $html = "<p><b>Tipo capitolo:</b> ".$this->chapterType."</p>";
        parent::printComponent($html);

    }
}
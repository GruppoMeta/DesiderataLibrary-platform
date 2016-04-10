<?php
class desiderataLibrary_modules_importEPUB_importer_LayoutConfigItem
{
    private $ebookLayoutType;
    private $viewType;
    private $viewLayout;
    private $convertToText;

    function __construct($ebookType, $viewType, $viewLayout, $needConversionToText) {
        $this->ebookLayoutType = $ebookType;
        $this->viewType = $viewType;
        $this->viewLayout = $viewLayout;
        $this->convertToText = $needConversionToText;
    }

    public function getEbookLayoutType() {
        return $this->ebookLayoutType;
    }

    public function getViewType() {
        return $this->viewType;
    }

    public function getViewLayout() {

        return $this->viewLayout;
    }

    public function needConversionToText() {
        return $this->convertToText;
    }
}
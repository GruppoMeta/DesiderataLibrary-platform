<?php

class desiderataLibrary_modules_importEPUB_importer_Config {
    private $layoutMap;
    private $ebookName;
    private $useSpine;
    private $missedContent;
    private $customFix = array();

    function __construct($ebookName){
        $this->ebookName = $ebookName;
        $this->useSpine = false;
        $this->missedContent = false;
        $this->missingTOC = false;
    }

    public function setLayoutMap($map) {
        $this->layoutMap = $map;
    }

    public function getLayoutMap() {
        return $this->layoutMap;
    }

    public function setuseSpine($useSpine) {
        if ($useSpine == 'true') {
            $this->useSpine = true;
        }
    }

    public function getuseSpine() {
        return $this->useSpine;
    }

    public function setmissedContent($flag) {
        if ($flag == 'true') {
            $this->missedContent = true;
        }
    }

    public function hasmissedContent() {
        return $this->missedContent;
    }

    public function addCustomFix($fixName) {
        $this->customFix[] = $fixName;
    }

    public function getCustomFix() {
        return $this->customFix;
    }
}
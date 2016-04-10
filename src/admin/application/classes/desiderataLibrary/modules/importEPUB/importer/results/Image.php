<?php
class desiderataLibrary_modules_importEPUB_importer_results_Image
{
    public $src;
    public $path;
    public $caption;
    public $contentText;
    public $hdSrc;
    public $width;
    public $height;

    function __construct($src, $path, $caption='')
    {
        $this->src = $src;
        $this->path = $path;
        $this->caption = $caption;
    }

    function toString(){
        return $this->caption." --- ".$this->src;
    }

    function setHdSrc($src){
        $this->hdSrc = $src;
    }

    function getSrc(){
        if($this->hdSrc)
            return $this->hdSrc;
        else
            return $this->src;
    }
}
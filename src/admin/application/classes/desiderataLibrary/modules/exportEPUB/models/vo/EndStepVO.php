<?php

class desiderataLibrary_modules_exportEPUB_models_vo_EndStepVO
{
    public $action = 'end';
    public $url;

    function __construct($url)
    {
        $this->url = $url;
    }
}

<?php

class desiderataLibrary_modules_exportEPUB_models_vo_PublishStepVO
{
    public $action;
    public $params;

    function __construct($action, $params)
    {
        $this->action = $action;
        $this->params = $params;
    }
}

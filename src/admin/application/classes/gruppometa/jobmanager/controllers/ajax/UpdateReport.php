<?php
class gruppometa_jobmanager_controllers_ajax_UpdateReport extends org_glizy_mvc_core_CommandAjax
{
    public function execute($data)
    {
        return array('sendOutput' => 'report', 'sendOutputState' => 'index', 'sendOutputFormat' => 'html' );
    }
}
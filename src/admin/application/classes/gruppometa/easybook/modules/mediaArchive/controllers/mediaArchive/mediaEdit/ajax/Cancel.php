<?php
class gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_mediaEdit_ajax_Cancel extends org_glizy_mvc_core_CommandAjax
{
    public function execute($data)
    {
        $this->directOutput = true;
        $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
        return array('url' => $this->changePage( stripos($this->application->getPageType(), 'mediapicker') === false ? $pubTypeInfo->routeUrlMedia : $pubTypeInfo->routeUrlMediaPicker));
    }
}
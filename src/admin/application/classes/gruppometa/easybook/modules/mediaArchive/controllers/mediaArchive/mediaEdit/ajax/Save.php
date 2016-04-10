<?php
class gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_mediaEdit_ajax_Save extends org_glizycms_mediaArchive_controllers_mediaEdit_ajax_Save
{
    public function getRedirectUrl()
    {
        $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
        return stripos($this->application->getPageType(), 'mediapicker') === false ? $pubTypeInfo->routeUrlMedia : $pubTypeInfo->routeUrlMediaPicker;
    }
}
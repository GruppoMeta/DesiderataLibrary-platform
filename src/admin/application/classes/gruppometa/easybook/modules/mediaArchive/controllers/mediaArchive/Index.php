<?php
class gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_Index extends org_glizycms_mediaArchive_controllers_Index
{
    public function execute()
    {
        parent::execute();
        if (strpos($this->application->getPageType(), 'MediaPicker')===-1) {
            $c = $this->view->getComponentById('btnAdd');

            $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
            $c->setAttribute('routeUrl', $pubTypeInfo->routeUrlMediaAdd);
        }
    }
}

<?php

class gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_Add extends org_glizycms_mediaArchive_controllers_Add
{
    public function execute()
    {
        parent::execute();
        $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
        $c = $this->view->getComponentById('addFromZip');
        if ($c) {
            $c->setAttribute('draw', __Config::get('glizycms.mediaArchive.addFromZipEnabled'));
            $c->setAttribute('url', $pubTypeInfo->routeUrlMediaAdd.'Zip');
        }
    }
}



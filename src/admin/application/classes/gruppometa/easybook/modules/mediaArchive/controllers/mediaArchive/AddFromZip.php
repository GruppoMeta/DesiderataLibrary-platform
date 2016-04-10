<?php

class gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_AddFromZip extends org_glizycms_mediaArchive_controllers_AddFromZip
{
    public function execute()
    {
        parent::execute();
        $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
        $c = $this->view->getComponentById('add');
        if ($c) {
            if (!__Config::get('glizycms.mediaArchive.addFromZipEnabled')) {
                $this->changePage($pubTypeInfo->routeUrlMediaAdd);
            }
            $c->setAttribute('url', $pubTypeInfo->routeUrlMediaAdd);
        }
    }

}



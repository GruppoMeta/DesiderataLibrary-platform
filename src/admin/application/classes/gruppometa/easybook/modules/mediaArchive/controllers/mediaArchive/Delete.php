<?php
class gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_Delete extends org_glizy_mvc_core_Command
{
    public function execute($id)
    {
        $media = org_glizy_ObjectFactory::createModel('org.glizycms.models.Media');
        $media->load($id);
        $mediaFileName = org_glizy_Paths::get('APPLICATION_MEDIA_ARCHIVE').ucfirst(strtolower($media->media_type)).'/'.$media->media_fileName;
        @unlink($mediaFileName);
        $media->delete($id);

        $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
        $this->changePage($pubTypeInfo->routeUrlMedia);
    }
}
<?php

class desiderataLibrary_modules_exportEPUB_controllers_ajax_EpubPublish extends org_glizy_mvc_core_CommandAjax
{
    public function execute()
    {
        if ($this->user->isLogged()) {
            $pubId = __Request::get('pubId');
            $publishFolder = desiderataLibrary_modules_exportEPUB_Common::getPublishFolder($pubId);
            $fileName = desiderataLibrary_modules_exportEPUB_Common::getEbookPath($pubId);

            desiderataLibrary_modules_exportEPUB_helpers_Filesystem::rmdir($fileName);

            exec('cd '.$publishFolder. ' && zip -q0X ../../'.$fileName.'  mimetype && zip -9 -r ../../'.$fileName.' ./META-INF/ ./OEBPS/ && rm -rf ../'.$pubId, $result);
            return true;
        }
    }
}
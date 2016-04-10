<?php
class gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_mediaEdit_ajax_SaveClose extends gruppometa_easybook_modules_mediaArchive_controllers_mediaArchive_mediaEdit_ajax_Save
{
    public function execute($data)
    {
        $result = parent::execute($data);

        if ($result['errors']) {
            return $result;
        }
        else {
            $this->directOutput = true;
            $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
            return array('url' => $this->changePage( stripos($this->application->getPageType(), 'mediapicker') === false ? $pubTypeInfo->routeUrlMedia : $pubTypeInfo->routeUrlMediaPicker));
        }
    }
}
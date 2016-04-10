<?php
class gruppometa_easybook_views_components_FormEdit extends org_glizycms_views_components_FormEdit
{
    function getAjaxUrl() {
        $url = parent::getAjaxUrl();
        $newUrl = '';
        if (__Request::exists('menu_id')) {
            $newUrl = '&menu_id='.__Request::get('menu_id');
        }
        $newUrl .= '&multisite=1&action=';
        return str_replace('&action=', $newUrl, $url);
    }

    protected function getMediaPickerUrl()
    {
        return '"index.php?pageId=MediaArchive_picker&multisite=1'.gruppometa_easybook_Easybook::getSiteId().'"';
    }

    protected function getTinyMceUrls()
    {
        return array(
                        'ajaxUrl' => GLZ_HOST.'/'.$this->getAjaxUrl().'&multisite=1'.gruppometa_easybook_Easybook::getSiteId(),
                        'mediaPicker' => GLZ_HOST.'/'.'index.php?pageId=MediaArchive_picker&multisite=1'.gruppometa_easybook_Easybook::getSiteId(),
                        'mediaPickerTiny' => GLZ_HOST.'/'.'index.php?pageId=MediaArchive_pickerTiny&multisite=1'.gruppometa_easybook_Easybook::getSiteId(),
                        'imagePickerTiny' => GLZ_HOST.'/'.'index.php?pageId=MediaArchive_pickerTiny&mediaType=IMAGE&multisite=1'.gruppometa_easybook_Easybook::getSiteId(),
                        'imageResizer' => GLZ_HOST.'/'.'getImage.php',
                        'root' => GLZ_HOST.'/',
            );
    }
}


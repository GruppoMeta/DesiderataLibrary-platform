<?php
class gruppometa_easybook_Easybook
{
    public static function getPublicationCode()
    {
        $pubCode = org_glizy_ObjectValues::get('Easybook', 'pubCode');
        if (!$pubCode) {
            $pubId = org_glizy_ObjectValues::get('org.glizy', 'siteId');
            $ar = org_glizy_ObjectFactory::createModelIterator('gruppometa.easybook.models.Publication')
                ->load('getPublicationById', array('id' => $pubId))->first();
            $pubCode = $ar->document->refId;
            if (!$pubCode) {
                $pubCode = str_replace(array('.', ' '), '', microtime()).'_'.$pubId;
            }
            org_glizy_ObjectValues::set('Easybook', 'pubCode', strtolower($pubCode));
            org_glizy_ObjectValues::set('Easybook', 'pubType', $ar->menu_pageType);
            org_glizy_ObjectValues::set('Easybook', 'subject', $ar->document->subject);
        }

        return $pubCode;
    }

    public static function getPublicationGuid()
    {
        $baseCode = org_glizy_ObjectValues::get('Easybook', 'refId');
        if (!$baseCode) {
            $menuId = __Request::get('menuId');
            $baseCode = self::getPublicationCode().'_'.$menuId;
            org_glizy_ObjectValues::set('Easybook', 'refId', $baseCode);
        }

        return $baseCode;
    }

    public static function getSiteId()
    {
        return '&menu_id='.org_glizy_ObjectValues::get('org.glizy', 'siteId');
    }

    public static function getLanguage()
    {
        return 1;
    }

    public static function getPublicationInfos() {
        return json_decode(__Config::get('gruppometa.easybook.add.publication'));
    }

    public static function getPublicationInfoForType($type) {
        $pubType = self::getPublicationInfos();
        foreach ($pubType as $v) {
            if ($v->type==$type) {
                return $v;
            }
        }

        return false;
    }

    public static function getPublicationInfoCurrent() {
        return self::getPublicationInfoForType(org_glizy_ObjectValues::get('Easybook', 'pubType'));
    }
}

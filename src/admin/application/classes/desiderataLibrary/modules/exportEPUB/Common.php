<?php

class desiderataLibrary_modules_exportEPUB_Common
{
    public static function getSiteMap()
    {
        $siteMap = org_glizy_ObjectFactory::createObject('org.glizycms.core.application.SiteMapDB');
        $siteMap->getSiteArray();
        return $siteMap;
    }

    public static function getSiteMapIterator()
    {
        $siteMapIterator = org_glizy_ObjectFactory::createObject('org.glizy.application.SiteMapIterator', self::getSiteMap());
        return $siteMapIterator;
    }

    public static function getPublishFolder($pubId)
    {
        return __Paths::get('CACHE') . $pubId;
    }

    public static function getResourceFolder($style)
    {
        return 'application/classes/desiderataLibrary/modules/exportEPUB/views/template/Epub-' . $style;
    }

    public static function initResources()
    {
        __Session::set('ebook.resources', array(
            'pages' => array(),
            'fonts' => array(),
            'resources' => array(),
            'css' => array(),
            'cover' => array(),
        ));
    }

    public static function addResourceInEbook($type, $path, $basePath, $info = array())
    {
        switch ($type) {
            case 'pages':
            case 'cover':
                $mediaType = 'application/xhtml+xml';
                break;
            case 'css':
                $mediaType = 'text/css';
                break;
            case 'fonts':
                $mediaType = 'application/octet-stream';
                break;
            case 'AUDIO':
                $mediaType = 'audio/' . pathinfo($path, PATHINFO_EXTENSION);
                $type = 'resources';
                break;
            case 'VIDEO':
                $mediaType = 'video/' . pathinfo($path, PATHINFO_EXTENSION);
                $type = 'resources';
                break;
            default:
                $mediaType = 'image/' . pathinfo($path, PATHINFO_EXTENSION);
                if ('image/jpg' == $mediaType) $mediaType = 'image/jpeg';
        }

        $resources = __Session::get('ebook.resources');

        // Controllo che la risorsa non sia già stata inserita
        foreach ($resources['resources'] as $res) {
            if ($res['path'] == str_replace($basePath, '', $path)) {
                return;
            }
        }

        if (!isset($info['id'])) {
            $info['id'] = $type . '-' . (count($resources[$type]) + 1);
        }
        $resources[$type][] = array_merge($info, array(
            'type' => $type,
            'path' => str_replace($basePath, '', $path),
            'mediaType' => $mediaType,
        ));
        __Session::set('ebook.resources', $resources);
    }

    public static function getResources()
    {
        $resources = __Session::get('ebook.resources');
        return array_merge(
            $resources['cover'],
            $resources['pages'],
            $resources['css'],
            $resources['fonts'],
            $resources['resources']
        );
    }

    public static function renderTemplate($templateVar, $style, $templateName = 'page')
    {
        org_glizy_Paths::set('APPLICATION_TEMPLATE', 'application/classes/desiderataLibrary/modules/exportEPUB/views/template/Epub-' . $style);
        $template = org_glizy_ObjectFactory::createObject('org.glizy.template.layoutManager.PHP', $templateName . '.php', '', false);
        return $template->apply($templateVar);
    }

    public static function getEbookPath($pubId)
    {
        return __Paths::get('CACHE') . 'ebook_'.$pubId.'.epub';
    }
}
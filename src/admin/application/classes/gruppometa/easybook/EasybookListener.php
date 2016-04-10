<?php
class gruppometa_easybook_EasybookListener extends GlizyObject
{
    function __construct()
    {
         $this->addEventListener( GLZ_EVT_BEFORE_CREATE_PAGE, $this );
         $this->addEventListener( org_glizycms_contents_events_Menu::INVALIDATE_SITEMAP, $this );
         $this->addEventListener( org_glizycms_contents_events_Menu::SAVE_CONTENT, $this );
    }

    public function beforeCreatePage($event)
    {
        if (__Request::exists('multisite')) {
            if (__Request::exists('menu_id')) {
                $menuId = __Request::get('menu_id');
                if ($menuId) {
                    __Session::set('siteId', __Request::get('menu_id'));
                }
            }
        } else {
            if ( __Request::exists('resetMultisite')) {
                __Session::remove('siteId');
            }
        }

        if (__Session::exists('siteId')) {
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', __Session::get('siteId'));

            // forza la generazione del codice delle pubblicazione
            // ed il caricamento delle informazioni pubType necessarie per gli url
            // diversi per i vari tipi di pubblicazione
            gruppometa_easybook_Easybook::getPublicationCode();
        }
    }

    public function onInvalidateSitemap()
    {
        if (__Session::exists('siteId')) {
            $cache = org_glizy_ObjectFactory::createObject('org.glizy.cache.CacheFunction',
                                                $this,
                                                __Config::get('glizycms.sitemap.cacheLife'),
                                                false,
                                                __Paths::getRealPath('APPLICATION_TO_ADMIN_CACHE'),
                                                'easybook:'.__Session::get('siteId'));

            $cache->invalidateGroup();
        }
    }

    public function onSaveContent()
    {
        $this->onInvalidateSitemap();
    }
}
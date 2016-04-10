<?php
class desiderataLibrary_modules_exportEPUB_controllers_Index extends org_glizy_mvc_core_Command
{
    public function execute()
    {
        $menuID = __Request::get('menu_id');
        $cp = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
        $menu = $cp->readContentFromMenu($menuID, 1);
        __Request::set('dc_title', $menu->__title);
        $authors = !$menu->author || is_string($menu->author) ? $menu->author : implode(', ', $menu->author);
        __Request::set('dc_author', $authors);
    }
}
<?php
class gruppometa_easybook_controllers_pageEdit_ajax_Save extends org_glizycms_contents_controllers_pageEdit_ajax_Save
{
    public function execute($data)
    {
        $reloadPage = false;
        $d = is_string($data) ? json_decode($data) : $data;

        // se la pagina è di tipo pubblicazione e si è modificato il titolo o la cover
        // viene aggiornata per vedere i cambiamate
        $menuProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.MenuProxy');
        $menu = $menuProxy->getMenuFromId($d->__id, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'));
        if ($this->isPubblication($menu)) {
            $contentproxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
            $menuContent = $contentproxy->readContentFromMenu($d->__id, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'));
            $reloadPage = $menuContent->__title != $d->__title || $menuContent->cover != $d->cover;
        }
        $result = parent::execute($data);
        if ($reloadPage) {
            $this->directOutput = true;
            return array(
                'url' => $this->changePage('linkPublicationContent', array('menu_id' => $d->__id)),
                'target' => 'window'
                );
        }
        return $result;
    }

    public function isPubblication($menu)
    {
        return 'publication'==$menu->menu_pageType;
    }
}
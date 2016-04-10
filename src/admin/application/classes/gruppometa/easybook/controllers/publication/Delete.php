<?php
class gruppometa_easybook_controllers_publication_Delete extends org_glizy_mvc_core_Command
{
    public function execute($menu_id)
    {
        $this->log('Cancellazione pubblicazione #id:'.$menu_id, GLZ_LOG_INFO, 'easybook', true);

        $it = org_glizy_ObjectFactory::createModelIterator('gruppometa.easybook.models.Publication');
        $it->load('deletePublication', array('siteId' => $menu_id));

        $this->changePage('link', array('pageId' => 'pubblicazioni'));
    }
}
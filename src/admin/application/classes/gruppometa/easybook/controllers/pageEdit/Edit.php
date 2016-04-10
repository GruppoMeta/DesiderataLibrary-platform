<?php
class gruppometa_easybook_controllers_pageEdit_Edit extends org_glizy_mvc_core_Command
{
    public function execute($menuId)
    {
        if ($menuId) {
            $pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
            if ($pubTypeInfo->type == 'Publication' || $pubTypeInfo->type == 'PublicationPdf') {
                $c = $this->view->getComponentById('preview');
                $c->setAttribute('visible', true);
                $c->setAttribute('url', GLZ_HOST.'/../../books/#/'.($pubTypeInfo->type == 'Publication' ? 'book' : 'pdf').'/'.__Session::get('siteId').'/'.$menuId.'/');
            }
        }
    }
}
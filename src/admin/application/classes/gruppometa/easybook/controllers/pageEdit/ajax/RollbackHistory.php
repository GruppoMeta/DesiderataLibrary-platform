<?php
class gruppometa_easybook_controllers_pageEdit_ajax_RollbackHistory extends org_glizy_mvc_core_CommandAjax
{
    public function execute($menuId, $vid)
    {
        if ($this->user->acl('easybook', 'history.rollback') && $menuId && $vid) {
          $it = org_glizy_objectFactory::createModelIterator('org.glizycms.core.models.Content');
          $it->where("document_detail_id", $vid)
               ->allStatuses();
          $ar = $it->first();

          $data = json_decode($ar->document_detail_object);
          if (is_object($data) && property_exists($data, 'content')) {
            $newData = $data->content;
            $newData->__id = $data->id;
            $newData->__title = $data->title;
            $newData->__comment = 'Ripristino versione del '.$ar->document_detail_modificationDate;

            $this->application->executeCommand('gruppometa.easybook.controllers.pageEdit.ajax.Save', $newData);
          }
        }
        return true;
    }
}
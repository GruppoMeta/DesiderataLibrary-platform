<?php
class gruppometa_easybook_controllers_pageEdit_ajax_ShowHistory extends org_glizy_mvc_core_CommandAjax
{
    public function execute($a, $b)
    {
        if ($this->user->acl('easybook', 'history.compare') && $a && $b) {
            $this->directOutput = true;

            $it = org_glizy_objectFactory::createModelIterator('org.glizycms.core.models.Content');
            $it->where("document_detail_id", $a)
                 ->allStatuses();
            $ar_a = $it->first();

            $it = org_glizy_objectFactory::createModelIterator('org.glizycms.core.models.Content');
            $it->where("document_detail_id", $b)
                 ->allStatuses();
            $ar_b = $it->first();

            $a = explode("\n", str_replace("<\/p>", "<\/p>\n", json_encode(json_decode($ar_a->document_detail_object), JSON_PRETTY_PRINT)));
            $b = explode("\n", str_replace("<\/p>", "<\/p>\n", json_encode(json_decode($ar_b->document_detail_object), JSON_PRETTY_PRINT)));

            glz_importApplicationLib('Diff/Diff.php');
            glz_importApplicationLib('Diff/Diff/Renderer/Html/SideBySide.php');
            // Options for generating the diff
            $options = array(
              'context' => 5
            );
            $diff = new Diff($a, $b, $options);

            $renderer = new Diff_Renderer_Html_SideBySide;
            $result = $diff->Render($renderer);
            return array('html' => $result);
          } else  {
            return false;
          }
    }
}
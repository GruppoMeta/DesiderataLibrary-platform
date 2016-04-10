<?php
class gruppometa_easybook_views_components_ShowHistory extends org_glizy_components_ComponentContainer
{
    // TODO localizzare
    function render()
    {
        $menuId = __Request::get('menuId');
        $canRallback = $this->_user->acl('easybook', 'history.rollback');

        $it = org_glizy_objectFactory::createModelIterator('org.glizycms.core.models.Content');
        $it->addSelect('u.*')
           ->join($it::DOCUMENT_TABLE_ALIAS, 'users_tbl', 'u',
                  $it->expr()->eq($it::DOCUMENT_DETAIL_TABLE_ALIAS.'.'.$it::DOCUMENT_DETAIL_FK_USER, 'u.user_id'))
           ->where("id", $menuId)
           ->orderBy('document_detail_modificationDate', 'DESC')
           ->allStatuses();

        $output = '<table class="table table-bordered table-striped">';
        $output .= '<thead><tr><th>Aggiornato da</th><th>Commento</th><th width="140">Data modifica</th></tr></thead>';
        $output .= '<tbody>';

        $i = 0;
        foreach ($it as $ar) {
            $output .= '<tr>'.
            '<td><input type="radio" name="history_a" value="'.$ar->document_detail_id.'" /> '.
            '<input type="radio" name="history_b" value="'.$ar->document_detail_id.'" /> '.
            $ar->user_firstName.' '.$ar->user_lastName.'</td>'.
            '<td>'.$ar->document_detail_note.'</td>'.
            '<td nowrap>'.$ar->document_detail_modificationDate.' '.
            ($i>0 && $canRallback? '<a title="Rollback" href="#" class="js-history-rollback" data-version="'.$ar->document_detail_id.'"><i class="icon-exchange btn-icon"></i> </a>' : '').
            '</td>'.
            '</tr>';
            $i++;
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '<div id="diff"></div>';

        $ajaxUrl = $this->_parent->getAjaxUrl();
        $output .= <<<EOD
<script>
$(function(){
  var \$btn = \$('input.js-glizycms-history');


  function getSelectedValues()
  {
    var a = \$('input[name=history_a]:checked').val();
    var b = \$('input[name=history_b]:checked').val();
    if (a && b && a!=b) {
      return {a: a, b: b};
    }
    return false;
  }
  function checkDisabled() {
    var sel = getSelectedValues();

    \$btn.attr('disabled', sel===false);
  }

  \$('input[name=history_a]').change(function(){
    checkDisabled();
  });
  \$('input[name=history_b]').change(function(){
    checkDisabled();
  });

  \$btn.click(function(e){
      e.preventDefault();
      var sel = getSelectedValues();
      if (sel!==false) {
        $.ajax({
            'url': '{$ajaxUrl}ShowHistory',
            'data': sel,
            'dataType': 'html',
            'success': function(data) {
              if (data!=='{"status":false}') {
                $("#diff").html(data);
              }
            }
        });
      }
  });

  \$('a.js-history-rollback').click(function(e){
    e.preventDefault();
    if (confirm('Sei sicuro di ritornare a questa versione?')) {
      $.ajax({
            'url': '{$ajaxUrl}RollbackHistory',
            'data': {menuId: $menuId, vid: \$(this).data('version')},
            'success': function(data) {
              location.reload();
            }
        });
    }
  });

  checkDisabled();
});
</script>
EOD;
        $this->addOutputCode($output);
    }
}

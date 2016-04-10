<?php
class gruppometa_easybook_views_components_DataGridAjax extends org_glizy_components_DataGridAjax
{

    public function getAjaxUrl()
    {
        return parent::getAjaxUrl().'&menu_id='.__Request::get( 'menu_id');
    }
}
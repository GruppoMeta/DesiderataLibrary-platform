<?php
class desiderataLibrary_modules_support_views_renderers_CellShow extends org_glizycms_contents_views_renderer_AbstractCellEdit
{
    function renderCell($key, $value, $row)
    {
        $output = __Link::makeLinkWithIcon( 'actionsMVC',
                                            __Config::get('glizy.datagrid.action.showCssClass'),
                                            array(
                                                'title' => __T('Dettaglio'),
                                                'id' => $key,
                                                'action' => 'edit') );
        return $output;
    }
}



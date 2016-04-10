<?php
class desiderataLibrary_modules_codes_views_renderer_CellEdit extends org_glizy_components_render_RenderCell
{
    function renderCell($key, $value, $row)
    {
        $output = __Link::makeLinkWithIcon( 'actionsMVC',
                                                        __Config::get('glizy.datagrid.action.showCssClass'),
                                                        array(
                                                            'title' => __T('Mostra'),
                                                            'id' => $key,
                                                            'action' => 'show') );

        $output .= __Link::makeLinkWithIcon( 'linkDownloadCsv',
                                                        'icon-download btn-icon',
                                                        array(
                                                            'title' => __T('Scarica'),
                                                            'id' => $key) );

        $output .= __Link::makeLinkWithIcon( 'actionsMVCDelete',
                                                        __Config::get('glizy.datagrid.action.deleteCssClass'),
                                                        array(
                                                            'title' => __T('GLZ_RECORD_DELETE'),
                                                            'id' => $key,
                                                            'model' => $row->getClassName(false),
                                                            'action' => 'delete'  ),
                                                        __T('GLZ_RECORD_MSG_DELETE') );
        return $output;
    }
}



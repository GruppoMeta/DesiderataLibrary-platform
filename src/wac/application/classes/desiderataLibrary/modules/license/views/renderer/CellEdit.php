<?php
class desiderataLibrary_modules_license_views_renderer_CellEdit extends org_glizy_components_render_RenderCell
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
        return $output;
    }
}



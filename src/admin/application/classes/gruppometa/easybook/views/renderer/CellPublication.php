<?php
class gruppometa_easybook_views_renderer_CellPublication extends org_glizy_components_render_RenderCellRecordSetList
{
    private $pubRouting = array();
    function __construct(&$application)
    {
        parent::__construct($application);

        $pubType = gruppometa_easybook_Easybook::getPublicationInfos();
        foreach ($pubType as $v) {
            $this->pubRouting[$v->type] = $v;
        }
    }

    function renderCell( &$ar, $params )
    {
        $pubInfo = $this->pubRouting[$ar->menu_pageType];
        $ar->__url__ = __Link::makeUrl($pubInfo->routeUrl, $ar->getValuesAsArray());
        $ar->__urlDelete__ = __Link::makeUrl($pubInfo->routeUrlDelete, $ar->getValuesAsArray());

        if ($ar->document->cover) {
            $ar->document->coverImg = org_glizy_helpers_Media::getResizedImageUrlById($ar->document->cover->id, false, 96, 148, true);
        } else {
            $ar->document->coverImg = $pubInfo->cover;
        }
    }
}


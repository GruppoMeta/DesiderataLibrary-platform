<?php

class gruppometa_easybook_modules_mediaArchive_views_renderer_CellMediaArchive extends org_glizy_components_render_RenderCellRecordSetList
{
	private $pubTypeInfo;

    function __construct(&$application)
    {
        parent::__construct($application);

        $this->pubTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
    }

	function renderCell( &$ar )
	{
		$media = org_glizy_media_MediaManager::getMediaByRecord( $ar );
		$sizes = method_exists( $media, 'getOriginalSizes') ? $media->getOriginalSizes() : array( 'width' => 0, 'height' => 0 );
		$thumbnail = $media->getThumbnail( 	__Config::get('THUMB_WIDTH'),
											__Config::get('THUMB_HEIGHT'),
											__Config::get('ADM_THUMBNAIL_CROP'),
											__Config::get('ADM_THUMBNAIL_CROPPOS'));
		$ar->thumb_filename = $thumbnail['fileName'];
		$ar->thumb_w = $thumbnail['width'];
		$ar->thumb_h = $thumbnail['height'];
		$ar->media_w = $sizes['width'];
		$ar->media_h = $sizes['height'];
		if ($ar->media_title==="") {
			$ar->media_title = $ar->media_originalFileName;
		}

		$ar->__url__ = __Routing::makeUrl($this->pubTypeInfo->routeUrlMediaAction, array('action' => 'edit', 'id' => $ar->media_id, 'menu_id' => __Request::get('menu_id')));
		$ar->__urlDelete__ = __Routing::makeUrl($this->pubTypeInfo->routeUrlMediaAction, array('action' => 'delete', 'id' => $ar->media_id, 'menu_id' => __Request::get('menu_id')));
		$ar->__urlDownload__ = org_glizy_helpers_Media::getFileUrlById($ar->media_id);
		$ar->__urlPreview__ = org_glizy_helpers_Media::getResizedImageUrlById($ar->media_id, false, 800, 600);
	}
}

<?php
class gruppometa_easybook_models_vo_ContentTextPdfVO extends gruppometa_easybook_models_vo_AbstractContentVO
{
    public $zoom = array();
    public $info = array();
    public $geo = '';

    function __construct($content, $node, $pubId)
    {
        parent::__construct($content, $node, $pubId);
        $this->zoom = explode(',', __Config::get('desiderataLibrary.importPDF.zoomLevels'));
        $this->geo = $content->geo;

        if ($content->hideTitle) {
            $this->title = '';
        }

        $json = glz_maybeJsonDecode($content->pageNum, false);
        $pageNum = $content->pageNum;
        $hotSpots = null;
        if (is_object($json)) {
            $pageNum = $json->pagNum;
            $hotSpots = $json->hotspots;
        }
        // 2015.12.11 ci sono casi in cui pageNum è una stringa vuota.
        if (!$pageNum) {
            $pageNum = str_replace('Pagina ', '', $content->__title);
        }
        $path =  __Config::get('desiderataLibrary.importPDF.exportFolder').'/'.$pubId.'/cache/';
        $baseUrl =  GLZ_HOST.__Config::get('desiderataLibrary.importPDF.publicUrl').'/'.$pubId.'/cache/';
        foreach($this->zoom as $z) {
            $this->addZoomInfo($z, $pageNum, $path, $baseUrl, $hotSpots);
        }
    }

    private function addZoomInfo($zoom, $pageNum, $path, $baseUrl, $hotSpots)
    {
        $pathLayer = $path.'layer_'.$pageNum.'_'.$zoom.'.txt.gz';
        $pathLayerPng = $path.'page_'.$pageNum.'_'.$zoom.'.png';
        $pathLayerJpg = $path.'page_'.$pageNum.'_'.$zoom.'.jpg';
        if (file_exists($pathLayer) && (file_exists($pathLayerPng) || $file_exists($pathLayerPng))) {
            ob_start();
            readgzfile($pathLayer);
            $layer = ob_get_contents();
            ob_end_clean();
            list($layer) = explode('###', $layer);
            $image = file_exists($pathLayerPng) ? $baseUrl.'page_'.$pageNum.'_'.$zoom.'.png' : $baseUrl.'page_'.$pageNum.'_'.$zoom.'.jpg';

            $newHotSpots = array();
            if ($hotSpots) {
                foreach($hotSpots as $h) {
                    $newHotSpots[] = $this->getHotspot($h, $zoom);
                }
            }
            $this->info[$zoom] = array('layer' => $layer, 'src' => $image, 'hotSpots' => $newHotSpots);
        }
    }

    private function getHotspot($hotSpot, $zoom)
    {
        $tempHotspot = new StdClass;
        $tempHotspot->form = $hotSpot->form;
        $tempHotspot->type = $hotSpot->type;
        $tempHotspot->top = (int)($hotSpot->top / 1.5 * $zoom);
        $tempHotspot->left = (int)($hotSpot->left / 1.5 * $zoom);
        $tempHotspot->height = (int)($hotSpot->height / 1.5 * $zoom);
        $tempHotspot->width = (int)($hotSpot->width / 1.5 * $zoom);
        $tempHotspot->description = $hotSpot->description;
        switch ($hotSpot->type) {
            case 'linkMedia':
                $media = org_glizy_media_MediaManager::getMediaById($hotSpot->srcMedia->id);
                if ($media) {
                    $tempHotspot->mediaTitle = $hotSpot->srcMedia->title;
                    $tempHotspot->mediaType = $media->type;
                    $tempHotspot->src = $tempHotspot->mediaType=='IMAGE' ?
                                        GLZ_HOST.'/getImage.php?id='.$hotSpot->srcMedia->id.'&w=1000&h=1000' :
                                        GLZ_HOST.'/getFile.php?id='.$hotSpot->srcMedia->id;
                }
                break;
            case 'linkEx':
                $tempHotspot->src = $hotSpot->src;
                break;
            case 'link':
                $tempHotspot->src = str_replace('internal:', '', $hotSpot->srcInt);
                break;
            case 'tooltip':
                break;
        }

        return $tempHotspot;
    }
}
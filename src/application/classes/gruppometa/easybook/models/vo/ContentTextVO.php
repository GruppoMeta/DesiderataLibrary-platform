<?php
class gruppometa_easybook_models_vo_ContentTextVO extends gruppometa_easybook_models_vo_AbstractContentVO
{
    public $text = '';
    public $geo = '';

    function __construct($content, $node, $pubId)
    {
        parent::__construct($content, $node, $pubId);
        $this->text = $this->fixLinks($content->text);

        if ($content->media && is_array($content->media->media) && count($content->media->media)) {
            foreach($content->media->media as $m) {
                $m = json_decode($m);
                if ($m) {
                    $this->text .= $this->addMediaHtml($m->id);
                }
            }
        }

        $this->geo = $content->geo;

        if ($content->hideTitle) {
            $this->title = '';
        }
    }

    private function addMediaHtml($id)
    {
        $output = '';
        $media = org_glizy_media_MediaManager::getMediaById($id);
        if ($media) {
            switch ($media->type) {
                case 'IMAGE':
                    $sizes = $media->getOriginalSizes();
                    $attributes = array();
                    $attributes['alt'] = $media->title;
                    $attributes['title'] = $title ? :$media->title;
                    $attributes['width'] = $sizes['width'];
                    $attributes['height'] = $sizes['height'];
                    $attributes['src'] = GLZ_HOST.'/getImage.php?id='.$id;
                    $output = '<div class="epub-image">'.org_glizy_helpers_Html::renderTag('img', $attributes).'</div>';
                    break;

                case 'VIDEO':
                    $output = '<div class="epub-image"><video width="480" height="320" src="'.GLZ_HOST.'/'.org_glizy_helpers_Media::getFileUrlById($id, false).'" controls></video></div>';
                    break;

                case 'AUDIO':
                    $output = '<div class="epub-image"><audio src="'.GLZ_HOST.'/'.org_glizy_helpers_Media::getFileUrlById($id, false).'" controls></audio></div>';
                    break;

                default:
                    $size = $this->formatFileSizeize($media->size+100000);
                    $output = '<div class="epub-image"><p><a href="'.GLZ_HOST.'/'.org_glizy_helpers_Media::getFileUrlById($id, false).'" title="Scarica: '.$media->title.'">'.$media->title.' ('.$size.')</a></p></div>';
                    break;
            }
        }

        return '<div class="js-annotator" id="annotator-media-'.$id.'">'.$output.'</div>';
    }
}
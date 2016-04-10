<?php
abstract class gruppometa_easybook_models_vo_AbstractContentVO
{
    public $pageType = '';
    public $title = '';
    public $subtitle = '';
    public $prevId;
    public $nextId;
    public $path = array();
    public $pubId = '';
    public $id;
    public $number = '';
    public $comments = array();
    public $bookmark = false;

    function __construct($content, $node, $pubId)
    {
        $this->pageType = $node->pageType;
        $this->id = $node->id;
        $this->pubId = (int)$pubId;
        $this->title = strip_tags($content->__title);
        $this->subtitle = @$content->subtitle;
        $this->number = @$content->number;
    }

    protected function convertToObject($data, $node=null)
    {
        $result = array();
        if (is_object($data)) {
            $objectKeys = array_keys(get_object_vars($data));
            if ($objectKeys) {
                $numItems = 0;
                foreach($objectKeys as $k) {
                    $numItems = max(count($data->{$k}), $numItems);
                }
                for($i=0; $i < $numItems; $i++) {
                    $tempObj = new StdClass;
                    $mediaId = null;
                    foreach($objectKeys as $k) {
                        $value = $data->{$k}[$i];
                        if ('text'==$k) {
                            $value = $this->fixLinks($value);
                        } else if (('attach'==$k || 'media'==$k || 'image'==$k) && $value) {
                            $value = $this->getMediaObject($k, $value);
                        }
                        $tempObj->{$k} = $value;
                    }

                    $result[] = $tempObj;
                }
            }


        }
        return $result;
    }

    protected function fixLinks($text)
    {
        // immagini nel testo
        preg_match_all('/\s*(src=["\'](\.\.\/)?(getImage.php\?(.*)?id=\d*)(&.*)?["\'])\s*/Ui', $text, $match);
        for($i=0; $i<count($match[0]);$i++) {
            $text = str_replace($match[0][$i], ' src="'.GLZ_HOST.'/'.$match[3][$i].$match[4][$i].$match[5][$i].'" ', $text);
        }

        // immagini nel testo, non dal mediaArchive
        $text = str_replace('</img>', '', $text);
        preg_match_all('/<img\s*(alt=["\'](.*)["\'])?\s*(src=["\'](?!getImage.php|http)(.*)?["\'])\s*(alt=["\'](.*)["\'])?(.*)>/Ui', $text, $match);
        $imageFolder = GLZ_HOST.'/liquidbook/pubImages/'.$this->pubId.'/';
        $replacedMap = array();
        for($i=0; $i<count($match[0]);$i++) {
            if (in_array($match[4][$i], $replacedMap)) continue;
            $replacedMap[] = $match[4][$i];
            $text = str_replace($match[4][$i], $imageFolder.$match[4][$i], $text);
        }

        // link a file
        // TODO verificare come mai è un getImage a non un getFile?
        preg_match_all('/href=(["\']media:(\d*):(.*)["\'])/Ui', $text, $match);
        if (count($match[0]) && $match[0][0]) {
            for($i=0; $i<count($match[0]);$i++) {
                $text = str_replace($match[1][$i], '"'.GLZ_HOST.'/getImage.php?id='.$match[2][$i].'&amp;w=600&amp;h=600" magnific-popup="single"', $text);
            }
        }

        // corregge i link a note o ancore
        // TODO verificare se usati
        $text = str_replace( array( 'href="#', 'href=\'#', 'href="repeater:extra:', 'href=\'repeater:extra:' ), array('anchor="', 'anchor=\'', 'anchor="', 'anchor=\''), $text);


        // corregge link interni
        preg_match_all('/<a.*(href=["\'])(internal\:)([^"\'#]+(?:#[^"\']+)?)(["\']).*/Ui', $text, $links);
        if (count($links) && count($links[0])) {
            $siteMap = &org_glizy_ObjectFactory::createObject('org.glizycms.core.application.SiteMapDB');
            $siteMap->getSiteArray();

            for ($i=0; $i<count($links[0]); $i++)
            {
                $linkId = $links[3][$i];
                $text = str_replace($links[1][$i].$links[2][$i].$links[3][$i].$links[4][$i],
                        'link="internal:'.$this->pubId.':'.$linkId.'"',
                        $text);
            }
        }

        if ($text) {
            $text = '<div class="js-annotator" id="annotator-text-'.$this->id.'">'.$text.'</div>';
        }
        return $text;
    }


    protected function getMediaObject($k, $value)
    {
        $value = json_decode($value);
        $mediaId = $value->id;
        // se è un media aggiunge altre informazioni
        if ($mediaId) {
            $arMedia = org_glizy_ObjectFactory::createModel('org.glizycms.models.Media');
            $arMedia->load($mediaId);
            if (!@$value->title) $value->title = $arMedia->media_originalFileName;
            else $value->title = strip_tags($value->title);
            $value->size = $this->formatFileSizeize($arMedia->media_size+100000);

            if ($value->description) {
                $value->description = '<div class="js-annotator" id="annotator-media-'.$mediaId.'">'.$this->fixLinks($value->description).'</div>';
            }

            if ('image'==$k) {
                $value->MEDIA = '';
                $value->THUMBNAIL = GLZ_HOST.'/'.org_glizy_helpers_Media::getResizedImageUrlById($mediaId, false, 70, 50);
                $value->IMMAGINE = GLZ_HOST.'/'.org_glizy_helpers_Media::getResizedImageUrlById($mediaId, false, 276, 276);
                $value->IMMAGINE_BIG = GLZ_HOST.'/'.org_glizy_helpers_Media::getResizedImageUrlById($mediaId, false, 1300, 800);
                if ($arMedia->media_zoom) {
                    $value->IMMAGINI_ZOOM = GLZ_HOST.'/zoom.php?id='.$mediaId;
                }
            } else {
                $value->MEDIA = GLZ_HOST.'/'.org_glizy_helpers_Media::getFileUrlById($mediaId, true);
                $value->IMMAGINE = '';
            }
        }
        return $value;
    }

    protected function formatFileSizeize($bytes)
    {
        if ($bytes > 0) {
            $unit = intval(log($bytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');

            if (array_key_exists($unit, $units) === true) {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
            }
        }

        return '';
    }

    protected function stripExtId($extId)
    {
        list($extId) = explode('#', str_replace('easybook:extid/', '', $extId));
        return $extId;
    }

}

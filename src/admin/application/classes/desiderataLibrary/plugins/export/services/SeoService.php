<?php
class desiderataLibrary_plugins_export_services_SeoService extends GlizyObject
{
    private $sitemap;
    private $sitemapPath;
    private $seoFolder;
    private $seoFolderImg;
    private $seoUrl;
    private $pubId;
    private $publicationContent;

    public function initSeoForPubId($host, $pubId, $publicationContent)
    {
        $folder = trim(__Config::get('desiderataLibrary.plugins.seo.folder'), '/');
        $this->sitemap = array();
        $this->sitemapPath = '../'.$folder.'/sitemap.xml';
        $this->seoFolder = '../'.$folder.'/'.$pubId.'/';
        $this->seoFolderImg = $this->seoFolder.'assets/';
        $this->seoUrl = $host.$folder.'/'.$pubId.'/';
        $this->publicationContent = $publicationContent;
        $this->pubId = $pubId;

        $this->setupPubFolder();
    }

    public function addPageInSeo($menu, $content)
    {
        $id = $menu->id;
        $fileName = $id.'.html';
        $content->publication = $this->publicationContent;
        $content->creationDate = $menu->creationDate;
        $content->modificationDate = $menu->modificationDate;
        $content->pageType = strtolower($menu->pageType);
        $templateSource = $this->renderTemplate((array)$content);
        file_put_contents($this->seoFolder.$fileName, $templateSource);
        $this->sitemap[] = '<url><loc>'.$this->seoUrl.$fileName.'</loc><changefreq>weekly</changefreq></url>';
    }

    public function store()
    {
        $sitemap = $this->getSitemap();

        $sitemap = preg_replace( "/\/\/\sstart\s".$this->pubId."\/\/([^\/])*\/\/\send\s".$this->pubId."\/\//i", "", $sitemap );
        $sitemap = str_replace( '</urlset>', '// start '.$this->pubId.'//'.GLZ_COMPILER_NEWLINE2.implode($this->sitemap, GLZ_COMPILER_NEWLINE2).'// end '.$this->pubId.'//'.GLZ_COMPILER_NEWLINE2.'</urlset>', $sitemap );
        file_put_contents($this->sitemapPath, $sitemap);
    }

    private function getSitemap()
    {
        if (file_exists($this->sitemapPath)) {
            return file_get_contents($this->sitemapPath);
        } else {
            $sitemap = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
</urlset>
EOD;
            return $sitemap;
        }
    }

    private function setupPubFolder()
    {
        org_glizy_helpers_Files::deleteDirectory($this->seoFolder);
        @mkdir($this->seoFolderImg, 0777, true);
    }


    private function renderTemplate($templateVar)
    {
        org_glizy_Paths::set('APPLICATION_TEMPLATE', __DIR__.'/../views/templates/');

        $template = org_glizy_ObjectFactory::createObject('org.glizy.template.layoutManager.PHP', 'index.php', $this->seoUrl);
        $templateSource = $template->apply($templateVar);
        return $this->fixMedia($templateSource);
    }

    private function fixMedia($templateSource)
    {
        preg_match_all('/src=[\""\']([^(\""\')]*?)[\""\']/i', $templateSource, $matches, PREG_PATTERN_ORDER);
        for ($i = 0; $i < count($matches[0]); $i++) {
            if (strpos($matches[1][$i], 'getImage.php')!==false || strpos($matches[1][$i], 'getFile.php')!==false) {
                list($command, $params) = explode('?', $matches[1][$i]);
                parse_str(html_entity_decode($params), $args);
                if (isset($args['id'])) {
                    $media = org_glizy_media_MediaManager::getMediaById($args['id']);
                    if (isset($args['w']) && isset($args['h'])) {
                        $thubnail = $media->getResizeImage($args['w'], $args['h']);
                        $mediaPath = $thubnail['fileName'];
                    } else {
                        $mediaPath = $media->getFileName(false);
                    }

                    $pathInfo = pathinfo($mediaPath);
                    $destName = $this->seoFolderImg.$pathInfo['basename'];
                    $destUrl = $this->seoUrl.'assets/'.$pathInfo['basename'];
                    if (file_exists($mediaPath)) {
                        copy($mediaPath, $destName);
                    }
                    $templateSource = str_replace($matches[1][$i], $destUrl, $templateSource);
                }
            }
        }

        return $templateSource;
    }


    public static function addMediaHtml($id)
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
                    $attributes['src'] = 'getImage.php?id='.$id;
                    $output = '<div class="epub-image">'.org_glizy_helpers_Html::renderTag('img', $attributes).'</div>';
                    break;

                case 'VIDEO':
                    $output = '<div class="epub-video"><video width="480" height="320" src="'.org_glizy_helpers_Media::getFileUrlById($id, false).'" controls></video></div>';
                    break;

                case 'AUDIO':
                    $output = '<div class="epub-audio"><audio src="'.org_glizy_helpers_Media::getFileUrlById($id, false).'" controls></audio></div>';
                    break;

                default:
                    $size = $this->formatFileSizeize($media->size+100000);
                    $output = '<div class="epub-media"><p><a href="'.org_glizy_helpers_Media::getFileUrlById($id, false).'" title="Scarica: '.$media->title.'">'.$media->title.' ('.$size.')</a></p></div>';
                    break;
            }
        }

        return $output;
    }

}

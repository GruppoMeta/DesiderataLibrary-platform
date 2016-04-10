<?php

class desiderataLibrary_modules_exportEPUB_controllers_ajax_EpubCreatePages extends org_glizy_mvc_core_CommandAjax
{
    public function execute()
    {
        if ($this->user->isLogged()) {
            $pubId = __Request::get('pubId');
            $pages = __Request::get('pages');
            $customCSS = __Request::get('customCSS');
            $publishFolder = desiderataLibrary_modules_exportEPUB_Common::getPublishFolder($pubId) . '/OEBPS/';
            $siteMap = desiderataLibrary_modules_exportEPUB_Common::getSiteMap();

            foreach ($pages as $page) {
                $pageId = $page['id'];
                $depth = $page['depth'];
                $pageName = 'page-' . $pageId . '.xhtml';
                $page = 'page-' . $pageId;
                $style = 'default';

                $menu = $this->readAndSetHTML($pageId);
                $title = "";
                if ($menu->hideTitle === 0) {
                    $title = '<h1 class="title">' . mb_convert_encoding($menu->__title, "ISO-8859-1", mb_detect_encoding($menu->__title, "UTF-8, ISO-8859-1, ISO-8859-15", true)) ."</h1>";
                }
                if ('Empty' == $menu->pageType) {
                    $templateVar = array(
                        'docTitle' => mb_convert_encoding($menu->__title, "ISO-8859-1", mb_detect_encoding($menu->__title, "UTF-8, ISO-8859-1, ISO-8859-15", true)),
                        'content' => '<h1 class="title emptyChapter">' . mb_convert_encoding($menu->__title, "UTF-8", mb_detect_encoding($menu->__title, "UTF-8, ISO-8859-1, ISO-8859-15", true)) . '</h1>'
                    );
                    $pageHtml = desiderataLibrary_modules_exportEPUB_Common::renderTemplate($templateVar, $style);
                } else {
                    $cleanText = mb_convert_encoding($menu->text, 'HTML-ENTITIES', "UTF-8");
                    $dom = new DOMDocument('1.0', 'utf-8');
                    libxml_use_internal_errors(true);
                    if ($cleanText != '') {
                        $dom->loadHTML($cleanText);
                        // Gestione media allegati
                        if (property_exists($menu, 'media') && property_exists($menu->media, 'media')) {
                            $innerMedia = $menu->media->media;
                            foreach ($innerMedia as $medium) {
                                $mediaJsonParsed = json_decode($medium);
                                $trueMedia = org_glizy_media_MediaManager::getMediaById($mediaJsonParsed->id);
                                if ($trueMedia->type === 'IMAGE' ) {
                                    $sizes = $trueMedia->getOriginalSizes();
                                    $w = $sizes['width'];
                                    $h = $sizes['height'];
                                    $img = $dom->createElement('img');
                                    $img->setAttribute('alt', $mediaJsonParsed->title);
                                    $src = "getImage.php?id=$mediaJsonParsed->id&w=$w&h=$h";
                                    $img->setAttribute('src', $src);
                                    $dom->appendChild($img);
                                } else {
                                    $this->fixMedia($trueMedia, $dom);
                                    $src = $trueMedia->getFileName();
                                    $this->addResource($src, $publishFolder, $trueMedia->type);
                                }
                            }
                        }
                        $cleanText = preg_replace('/<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveXML()));
                        $cleanText = preg_replace('/<\?xml.+>/', '', $cleanText);
                    } else {
                        if (property_exists($menu, 'media') && property_exists($menu->media, 'media')) {
                            $innerMedia = $menu->media->media;
                            foreach ($innerMedia as $medium) {
                                $mediaJsonParsed = json_decode($medium);
                                $trueMedia = org_glizy_media_MediaManager::getMediaById($mediaJsonParsed->id);
                                if ($trueMedia->type === 'IMAGE' ) {
                                    $sizes = $trueMedia->getOriginalSizes();
                                    $w = $sizes['width'];
                                    $h = $sizes['height'];
                                    $src = "getImage.php?id=$mediaJsonParsed->id&w=$w&h=$h";
                                    $img = '<img alt="' . $mediaJsonParsed->title . '" src="' . $src . '" />';
                                    $cleanText .= $img;
                                } else {
                                    $this->generateMediaMarkup($trueMedia, $cleanText);
                                    $src = $trueMedia->getFileName();
                                    $this->addResource($src, $publishFolder, $trueMedia->type);
                                }
                            }
                        }
                    }
                    $templateVar = array(
                        'docTitle' => $menu->__title,
                        'customCSS' => $customCSS,
                        'title' => $title,
                        'content' => $cleanText
                    );
                    $pageHtml = desiderataLibrary_modules_exportEPUB_Common::renderTemplate($templateVar, $style);
                }
                $pageHtml = $this->cleanHtml($pageHtml, $style);
                $pageHtml = $this->fixImages($pageHtml, $publishFolder);
                file_put_contents($publishFolder . $pageName, utf8_encode($pageHtml));
                desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook('pages', $pageName, $publishFolder, array('id' => $page, 'title' => $menu->__title, 'depth' => $depth));
            }

            return true;
        }

    }

    private function cleanHtml($pageHtml, $style)
    {
        $host = str_replace('admin', '', GLZ_HOST);
        $pageHtml = str_replace('<base href="' . GLZ_HOST . '/' . '" />', '', $pageHtml);
        $pageHtml = str_replace('application/classes/desiderataLibrary/modules/exportEPUB/views/template/Epub-' . $style, '', $pageHtml);
        return $pageHtml;
    }

    private function fixImages($pageHtml, $publishFolder)
    {
        $pattern = '/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'\s>]*)/i';
        preg_match_all($pattern, $pageHtml, $matches, PREG_PATTERN_ORDER);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $imgSrc = $this->imageSrc($matches[1][$i]);
            if ($imgSrc) {
                $fileName = basename($imgSrc);
                $img = 'resources/' . $fileName;
                desiderataLibrary_modules_exportEPUB_helpers_Filesystem::copy($imgSrc, $publishFolder . '/resources/' . $fileName);
                desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook('resources', 'resources/' . $fileName, $publishFolder);
                $pageHtml = str_replace($matches[1][$i], $img, $pageHtml);
            }
        }

        return $pageHtml;
    }

    private function imageSrc($url)
    {
        if (strpos($url, 'getImage.php') !== false) {
            $url = str_replace(array('getImage.php?', '&amp;'), array('', '&'), $url);
            $chunks = explode('&', $url);
            $image = array();
            foreach ($chunks as $key => $chunk) {
                list($k, $v) = explode('=', $chunk);
                $image[$k] = $v;
            }
            if (!isset($image['id'])) return "";
            $media = org_glizy_media_MediaManager::getMediaById($image['id']);
            if (isset($image['w']) && isset($image['h'])) {
                $mediaInfo = $media->getResizeImage($image['w'], $image['h']);
            } else {
                $mediaInfo = $media->getImageInfo();
            }
            return $media->getFileName();
        }

    }

    private function readAndSetHTML($menuID)
    {
        $cp = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
        $menu = $cp->readContentFromMenu($menuID, 1);
        return $menu;
    }

    private function fixMedia($media, &$dom) {
        switch ($media->type) {
            case 'AUDIO':
                $src = $media->getFileName();
                $audio = $dom->createElement('audio');
                $audio->setAttribute('controls', 'controls');
                $source = $dom->createElement('source');
                $source->setAttribute('src', 'resources/'.basename($src));
                $source->setAttribute('type', 'audio/'.pathinfo($src, PATHINFO_EXTENSION));
                $audio->appendChild($source);
                $dom->appendChild($audio);
                break;
            case 'VIDEO':
                $src = $media->getFileName();
                $video = $dom->createElement('video');
                $video->setAttribute('width', '480');
                $video->setAttribute('height', '320');
                $video->setAttribute('controls', 'controls');
                $source = $dom->createElement('source');
                $source->setAttribute('src', 'resources/'.basename($src));
                $source->setAttribute('type', 'video/'.pathinfo($src, PATHINFO_EXTENSION));
                $video->appendChild($source);
                $dom->appendChild($video);
        }
    }

    private function generateMediaMarkup($media, &$cleanText) {
        switch ($media->type) {
            case 'AUDIO':
                $src = $media->getFileName();
                $trueSrc = 'resources/'.basename($src);
                $type = 'audio/'.pathinfo($src, PATHINFO_EXTENSION);
                $cleanText .= "<audio controls=\"controls\">";
                $cleanText .= "<source src=\"$trueSrc\" type=\"$type\">";
                $cleanText .= "</source></audio>";
                break;
            case 'VIDEO':
                $src = $media->getFileName();
                $trueSrc = 'resources/'.basename($src);
                $type = 'video/'.pathinfo($src, PATHINFO_EXTENSION);
                $cleanText .= "<video width=\"480\" height=\"320\" controls=\"controls\">";
                $cleanText .= "<source src=\"$trueSrc\" type=\"$type\">";
                $cleanText .= "</source></video>";
        }
    }

    private function addResource($src, $publishFolder, $type) {
        $fileName = basename($src);
        desiderataLibrary_modules_exportEPUB_helpers_Filesystem::copy($src, $publishFolder . '/resources/' . $fileName);
        desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook($type, 'resources/' . $fileName, $publishFolder);
    }
}
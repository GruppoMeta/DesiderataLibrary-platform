<?php

class desiderataLibrary_modules_exportEPUB_controllers_ajax_EpubCreateMetadata extends org_glizy_mvc_core_CommandAjax
{
    public function execute()
    {
        if ($this->user->isLogged()) {
            $pubId = __Request::get('pubId');
            $dc_title = __Request::get('dc_title');
            $dc_author = __Request::get('dc_author');
            $authors = explode(', ', $dc_author);
            $style = 'default';
            $publishFolder = desiderataLibrary_modules_exportEPUB_Common::getPublishFolder($pubId) . '/OEBPS/';

            // genera la cover
            $templateVar = array(
                'docTitle' => $dc_title,
                'title' => $dc_title,
                'authors' => $authors,
                'pubId' => $pubId,
            );
            $pageHtml = desiderataLibrary_modules_exportEPUB_Common::renderTemplate($templateVar, $style, 'cover');
            $pageName = 'cover.xhtml';
            $pageHtml = $this->cleanHtml($pageHtml, $style);
            file_put_contents($publishFolder . $pageName, $pageHtml);
            desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook('cover', $pageName, $publishFolder, array('id' => 'cover'));

            $templateVar['items'] = desiderataLibrary_modules_exportEPUB_Common::getResources();
            $renderedTemplate = desiderataLibrary_modules_exportEPUB_Common::renderTemplate($templateVar, $style, 'content.opf');
            $renderedTemplate = $this->cleanHtml($renderedTemplate, $style);
            file_put_contents($publishFolder . 'content.opf', $renderedTemplate);
            $depth = 0;
            foreach ($templateVar['items'] as $item) {
                if ('pages' == $item['type']) {
                    $depth = max($depth, $item['depth']);
                }
            }
            $renderedTemplate = desiderataLibrary_modules_exportEPUB_Common::renderTemplate($templateVar, $style, 'nav.xhtml');
            $renderedTemplate = $this->cleanHtml($renderedTemplate, $style);
            file_put_contents($publishFolder . 'nav.xhtml', $renderedTemplate);
            desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook('pages', 'nav.xhtml', $publishFolder, array('id' => 'nav', 'title' => 'Indice', 'depth' => 1));
            $templateVar['items'] = desiderataLibrary_modules_exportEPUB_Common::getResources();
            $depth = 0;
            foreach ($templateVar['items'] as $item) {
                if ('pages' == $item['type']) {
                    $depth = max($depth, $item['depth']);
                }
            }
            $templateVar['depth'] = $depth;
            $renderedTemplate = desiderataLibrary_modules_exportEPUB_Common::renderTemplate($templateVar, $style, 'toc.ncx');
            $renderedTemplate = $this->cleanHtml($renderedTemplate, $style);
            file_put_contents($publishFolder . 'toc.ncx', $renderedTemplate);

            return true;
        }
    }

    private function cleanHtml($pageHtml, $style)
    {
        $host = str_replace('admin', '', GLZ_HOST);
        $pageHtml = str_replace('<base href="' . GLZ_HOST . '/' . '" />', '', $pageHtml);
        $pageHtml = str_replace('application/classes/desiderataLibrary/modules/exportEPUB/views/template/Epub-' . $style, '', $pageHtml);
        $pageHtml = iconv(mb_detect_encoding($pageHtml, mb_detect_order(), true), "UTF-8", $pageHtml);
        return $pageHtml;
    }
}
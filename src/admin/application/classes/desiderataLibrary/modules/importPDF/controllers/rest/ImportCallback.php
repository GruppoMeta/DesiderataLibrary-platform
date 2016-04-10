<?php
class desiderataLibrary_modules_importPDF_controllers_rest_ImportCallback extends org_glizy_rest_core_CommandRest
{
    // questo controller è chiamato dalla pipeline quando è finita l'importazione dei pdf
    function execute($publicationId, $jobId)
    {
        if ($publicationId && $jobId) {
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', $publicationId);

            $folder = __Config::get('desiderataLibrary.importPDF.exportFolder').'/'.$publicationId;

            $files = glob($folder.'/text/*.txt');

            // ordina i file per ordine naturale
            natsort($files);

            $menuProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.MenuProxy');
            $contentProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');

            $i = 0;
            $j = 0;
            $pageSize = 20;
            $totalPages = count($files);

            $importPdfService = __ObjectFactory::createObject('desiderataLibrary.modules.importPDF.services.ImportPDFService', $jobId);
            $params = $importPdfService->getParams();
            $pdfTitle = $params['title'];

            foreach ($files as $file) {
                // ogni $pageSize pagine crea una pagina vuota che contiene le pagine
                if ($i % $pageSize === 0) {
                    $pdfStart = ($j*$pageSize)+1;
                    $pdfTo = $pdfStart+$pageSize-1;
                    if ($pdfTo > $totalPages) {
                        $pdfTo = $totalPages;
                    }
                    $title = 'Pag. '.$pdfStart.' a pag. '.$pdfTo;
                    $menuId = $this->createEmptyPage($title, $publicationId, $pdfStart, $pdfTo, $menuProxy, $contentProxy);
                    $j++;
                }

                $importPdfService->setMessage('Importazione in corso della pagina '.($i+1).' del file '.$pdfTitle);
                $importPdfService->save();
                $this->importPage('Pagina '.++$i, $menuId, $file, $menuProxy, $contentProxy);
            }

            $importPdfService->complete();

            @unlink($params['pdf']);
            return 'OK';
        } else {
            return 'KO';
        }
    }

    protected function createEmptyPage($title, $parentId, $pdfStart, $pdfTo, $menuProxy, $contentProxy)
    {
        $menuId = $menuProxy->addMenu($title, $parentId, 'EmptyPdf');
        $contentVO = $contentProxy->getContentVO();
        $contentVO->setId($menuId);
        $contentProxy->saveContent($contentVO, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'), __Config::get('glizycms.content.history'));
        return $menuId;
    }

    protected function importPage($title, $parentId, $file, $menuProxy, $contentProxy)
    {
        $pathinfo = pathinfo($file);
        $menuId = $menuProxy->addMenu($title, $parentId, 'TextPdf');
        $contentVO = $contentProxy->getContentVO();
        $contentVO->setId($menuId);
        $contentVO->text = file_get_contents($file);
        $contentVO->pageNum = $pathinfo['filename'];
        $contentProxy->saveContent($contentVO, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'), __Config::get('glizycms.content.history'));
    }
}
<?php
class desiderataLibrary_modules_importPDF_controllers_Import extends org_glizy_mvc_core_Command
{
    public function execute($sourceFile, $menu_id)
    {
        $publicationId = $menu_id;
        $sourceFile = glz_maybeJsonDecode($sourceFile, false);
        $isValid = false;
        if (is_object($sourceFile) && property_exists($sourceFile, 'id')) {
            $media = org_glizy_media_MediaManager::getMediaById($sourceFile->id);
            $file = $media->getFileName(false);
            $isValid = true;
        }
        if ($isValid && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
            // copia il file nella cartella condivisa con il server
            $destFile = __Config::get('desiderataLibrary.importPDF.folder').'/'.md5($file.microtime(true)).'.pdf';
            if (!@copy($file, $destFile)) {
                $this->logAndMessage("Errore nella preparazione del file, cartella di destinazione non scrivibile", '', GLZ_LOG_ERROR);
                $this->changeBackPage();
                exit;
            }

            // crea prima il job per ricavare il jobId
            $jobFactory = org_glizy_objectFactory::createObject('gruppometa.jobmanager.JobFactory');
            $jobId = $jobFactory->createJob('desiderataLibrary.modules.importPDF.services.ImportPDFService',
                array(),
                'Importazione pdf: '.$media->title,
                'BACKGROUND'
            );

            // una volta ottenuto il jobId crea l'url per la callback
            $callback = __Config::get('desiderataLibrary.importPDF.callBackUrl').'rest/pdf/import/'.$publicationId.'/'.$jobId;
            $callbackError = __Config::get('desiderataLibrary.importPDF.callBackUrl').'rest/pdf/import-error/'.$jobId;
            $params = array(
                'pdf' => $destFile,
                'title' => $media->title,
                'publicationId' => $publicationId,
                'callback' => $callback,
                'callbackError' => $callbackError
            );

            // aggiorna i parametri del job
            $importPdfService = __ObjectFactory::createObject('desiderataLibrary.modules.importPDF.services.ImportPDFService', $jobId);
            $importPdfService->setParams($params);
            $importPdfService->save();

            $this->changePage('link', array('pageId' => 'pluginsReport'));
        } else {
            $this->logAndMessage("Selezionare un file con estensione .pdf!", '', GLZ_LOG_ERROR);
            $this->changeBackPage();
        }
    }
}
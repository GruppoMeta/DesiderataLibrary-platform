<?php
class desiderataLibrary_modules_importEPUB_controllers_Import extends org_glizy_mvc_core_Command {
    public function execute($sourceFile, $menu_id, $showLog) {
        set_time_limit(0);
        $sourceFile = glz_maybeJsonDecode($sourceFile, false);
        $isValid = false;
        if (is_object($sourceFile) && property_exists($sourceFile, 'id')) {
            $media = org_glizy_media_MediaManager::getMediaById($sourceFile->id);
            $file = $media->getFileName(false);
            $isValid = true;
        }

        if ($isValid && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'epub') {
            $publicationId = $menu_id;
            $folderName = pathinfo($file, PATHINFO_FILENAME);
            $folderPath = pathinfo($file, PATHINFO_DIRNAME)."/".$folderName;
            exec('unzip -t '. $this->escapeArg($file), $result, $returnVal);
            if ($returnVal > 0) {
                $this->logAndMessage("Il file .epub &egrave; corrotto!", '', GLZ_LOG_ERROR);
                $this->changeBackPage();
            }
            exec('unzip -oq ' . $this->escapeArg($file). ' -d '. $this->escapeArg($folderPath), $result, $returnVal);
            if ($returnVal > 0) {
                $this->logAndMessage("La procedura di estrazione del file non &egrave; andata a buon fine!", '', GLZ_LOG_ERROR);
                $this->changeBackPage();
            }
            $importer = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.Importer', $showLog);
            $importer->writeConfig(pathinfo($file, PATHINFO_FILENAME), $folderPath);
            $results = $importer->import($folderPath, $publicationId);
            $importer->writeInDB($results, $publicationId);
            $finalMessage = "Importazione completata!<br/><br />";
            if ($showLog) {
                $importer->writeLog($folderPath);
                $finalMessage .= "Segue il il riepilogo delle fasi di importazione<br /><br />";
                $finalMessage .= nl2br($importer->getLog());
            }
            $this->logAndMessage($finalMessage);
            $this->changeBackPage();
        } else {
            $this->logAndMessage("Selezionare un file con estensione .epub!", '', GLZ_LOG_ERROR);
            $this->changeBackPage();
        }
    }

    private function escapeArg($arg) {
        $escapedArg = "'".str_replace("'", "'\\''", $arg)."'";
        return $escapedArg;
    }
}
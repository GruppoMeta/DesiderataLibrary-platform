<?php
class desiderataLibrary_modules_importPDF_controllers_rest_ErrorCallback extends org_glizy_rest_core_CommandRest
{
    function execute($jobId, $error)
    {
        if ($jobId && $error) {
            switch ($error) {
                case '1':
                    $errorMessage = 'Impossibile caricare il file di configurazione: contattare l\'amministratore';
                    break;
                case '3':
                    $errorMessage = 'Generatore di cache non trovato: contattare l\'amministratore';
                    break;
                case '4':
                    $errorMessage = 'Il path dei log deve puntare ad un file, mentre attualmente punta ad una directory: contattare l\'amministratore';
                    break;
                case '5':
                    $errorMessage = 'File PDF da importare non trovato: verificare che sia stato caricato correttamente';
                    break;
                case '6':
                    $errorMessage = 'Path di destinazione dell\'esportazione non trovato: contattare l\'amministratore';
                    break;
                case '7':
                    $errorMessage = 'Titolo già esportato. Contattare l\'amministratore: per sovrascrivere, è necessario aggiungere il flag --replace in calce alla chiamata della pipe.';
                    break;
                case '8':
                    $errorMessage = 'Si è verificato un errore durante l\'esportazione: il PDF sorgente potrebbe essere corrotto o non standard.';
                    break;
                default:
                   $errorMessage = 'Errore nell\'importazione del PDF';
            }
            $importPdfService = __ObjectFactory::createObject('desiderataLibrary.modules.importPDF.services.ImportPDFService', $jobId);
            $importPdfService->updateStatus(metacms_jobmanager_JobStatus::ERROR);
            $importPdfService->setMessage($errorMessage);
            $importPdfService->save();
            return 'OK';
        } else {
            return 'KO';
        }
    }

}
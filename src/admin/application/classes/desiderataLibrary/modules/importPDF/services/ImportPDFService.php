<?php
class desiderataLibrary_modules_importPDF_services_ImportPDFService extends gruppometa_jobmanager_service_SimpleService
{
    public function run()
    {
        parent::run();

        $params = array(
            'pdf' => $this->params['pdf'],
            'isbn' => $this->params['publicationId'],
            'callback' => $this->params['callback'],
            'callbackError' => $this->params['callbackError']
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest',
            __Config::get('desiderataLibrary.importPDF.pdfImportServiceUrl'),
            'POST',
            $params,
            'application/x-www-form-urlencoded'
        );

        $this->setMessage('Importazione in corso del file '.$this->params['title'].' nella pubblicazione con ID: '.$this->params['publicationId']);
        $this->save();

        $request->execute();
    }
}





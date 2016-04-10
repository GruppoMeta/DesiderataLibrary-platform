<?php
class desiderataLibrary_plugins_export_services_RemoveService extends gruppometa_jobmanager_service_SimpleService
{
    public function run()
    {
        parent::run();

        $this->menuIds = $this->params['menuIds'];
        $this->totalParts = count($this->menuIds);

        foreach ($this->menuIds as $publicationId) {
            $this->removePublication($publicationId);
            $this->parts++;
            $this->recommenderImport($publicationId);
        }

        $this->complete();
    }

    protected function recommenderImport($publicationId)
    {
        // chiama il servizio per l'aggiornamento dei contenuti
        $url = __Config::get('desiderataLibrary.recommender.host').'/recommender/update_contents';
        $params = array('publication_id' => $publicationId);
        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'GET', $params);
        $request->execute();
    }

    private function subtaskDone($message='')
    {
        if ($message) {
            $this->setMessage($message);
        }
        $this->subtasks++;
        $this->updateProgress((100 / $this->totalParts * $this->parts) + $this->subtasks / $this->totalSubtasks * (100 / $this->totalParts));
        $this->save();
    }

    private function removePublication($publicationId)
    {
        $this->removeFromSolr($publicationId);
    }

    private function removeFromSolr($publicationId)
    {
        $command = 'update/json';
        $json = array(
            'delete' => array(
                'query' => 'publicationId_i:'.$publicationId
            )
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest',
            __Config::get('desiderataLibrary.solr.url').$command.'?wt=json&commit=true',
            'POST',
             json_encode($json),
            'application/json'
        );
        $request->execute();
    }
}
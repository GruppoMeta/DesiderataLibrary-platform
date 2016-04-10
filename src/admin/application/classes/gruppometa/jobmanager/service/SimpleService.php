<?php
class gruppometa_jobmanager_service_SimpleService extends gruppometa_jobmanager_service_JobService
{
    public function run()
    {
        $this->updateStatus(gruppometa_jobmanager_JobStatus::RUNNING);
    }

    public function complete()
    {
        $this->setMessage('');
        $this->updateStatus(gruppometa_jobmanager_JobStatus::COMPLETED);
        $this->updateProgress(100);
        $this->save();
    }
}
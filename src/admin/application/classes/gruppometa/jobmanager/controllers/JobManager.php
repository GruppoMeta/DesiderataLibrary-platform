<?php
class gruppometa_jobmanager_controllers_JobManager extends org_glizy_mvc_core_Command
{
    public function execute()
    {
        // esegue il primo job non ancora eseguito
        if (__Config::get('jobmanager.parallel.enabled')!==true) {
            $it = org_glizy_objectFactory::createModelIterator('gruppometa.jobmanager.models.Job');
            $it->where('job_status', 'RUNNING');
            if ($it->count() > 0) {
                return;
            }
        }

        $it = org_glizy_objectFactory::createModelIterator('gruppometa.jobmanager.models.Job');
        $it->where('job_status', 'NOT_STARTED');

        if ($it->count() > 0) {
            $ar = $it->first();
            $jobService = org_glizy_objectFactory::createObject($ar->job_name, $ar->job_id);
            $jobService->run();
        }
    }

}
<?php
class gruppometa_jobmanager_controllers_ajax_RefreshJob extends org_glizy_mvc_core_CommandAjax
{
    public function execute($id)
    {
        $ar = org_glizy_objectFactory::createModel('gruppometa.jobmanager.models.Job');
        if ($ar->load($id)) {
            $ar->job_status = 'NOT_STARTED';
            $ar->job_progress = 0;
            $ar->job_message = '';
            $ar->save();
        }
        return true;
    }
}
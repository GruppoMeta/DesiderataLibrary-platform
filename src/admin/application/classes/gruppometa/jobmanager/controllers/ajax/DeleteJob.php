<?php
class gruppometa_jobmanager_controllers_ajax_DeleteJob extends org_glizy_mvc_core_CommandAjax
{
    public function execute($id)
    {
        $ar = org_glizy_objectFactory::createModel('gruppometa.jobmanager.models.Job');
        if ($ar->load($id)) {
            if ($ar->job_status!='RUNNING') {
                $ar->delete();
            }
        }
        return true;
    }
}
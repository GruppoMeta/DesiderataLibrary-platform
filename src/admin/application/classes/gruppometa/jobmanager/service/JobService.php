<?php
abstract class gruppometa_jobmanager_service_JobService extends GlizyObject
{
    protected $jobId;
    protected $params;
    protected $status;
    protected $progress;
    protected $description;
    protected $message;
    protected $modified = false;

	public function __construct($jobId)
	{
        $this->jobId = $jobId;
        $ar = org_glizy_objectFactory::createModel('gruppometa.jobmanager.models.Job');
        $ar->load($this->jobId);
        $this->params = unserialize($ar->job_params);
        $this->description = $ar->job_description;
        $this->message = $ar->job_message;
        $this->status = $ar->job_status;
        $this->progress = $ar->job_progress;
    }

    abstract public function run();

    public function updateModified($old, $new)
    {
        if (!$this->modified) {
            $this->modified = $old != $new;
        }
    }

    public function updateStatus($status)
    {
        $this->updateModified($this->status, $status);
        $this->status = $status;
    }

    public function updateProgress($progress)
    {
        $this->updateModified($this->progress, $progress);
        $this->progress = $progress;
    }

    public function setParams($params)
    {
        $this->updateModified($this->progress, $progress);
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setDescription($description)
    {
        $this->updateModified($this->description, $description);
        $this->description = $description;
    }

    public function setMessage($message)
    {
        $this->updateModified($this->message, $message);
        $this->message = $message;
    }

    // salva lo stato del job nel db
    public function save()
    {
        $ar = org_glizy_objectFactory::createModel('gruppometa.jobmanager.models.Job');
        $ar->load($this->jobId);
        $ar->job_params = serialize($this->params);
        $ar->job_description = $this->description;
        $ar->job_message = $this->message;
        $ar->job_status = $this->status;
        $ar->job_progress = $this->progress;
        if ($this->modified) {
            $ar->job_modificationDate = new org_glizy_types_DateTime();
        }
        $ar->save();
    }

    public function logResponse($response)
    {
        $ar = org_glizy_objectFactory::createModel('gruppometa.jobmanager.models.Joblogs');
        $ar->joblog_FK_job_id = $this->jobId;
        $ar->joblog_response = $response;
        $ar->joblog_datetime = new org_glizy_types_DateTime();
        $ar->save();
    }
}
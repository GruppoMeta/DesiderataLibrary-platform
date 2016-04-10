<?php
class desiderataLibrary_plugins_export_controllers_Index extends org_glizy_mvc_core_Command
{
    public function execute()
    {
        if ($this->user->groupId === 1) {
            $this->setComponentsAttribute('ModuleDP', 'query', 'getAllPublications');
        }
    }
}
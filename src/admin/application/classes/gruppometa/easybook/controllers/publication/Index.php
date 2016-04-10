<?php
class gruppometa_easybook_controllers_publication_Index extends org_glizy_mvc_core_Command
{
    public function execute()
    {
        if ($this->user->groupId === 1) {
            $this->setComponentsAttribute('ModuleDP', 'query', 'getAllPublications');
        }

        $allowPublicationType = array();
        $pubType = gruppometa_easybook_Easybook::getPublicationInfos();
        foreach ($pubType as $v) {
            if ($this->user->acl('easybook', 'show.publication.'.$v->type)) {
                $allowPublicationType[] = $v->type;
            }
        }

        if (!count($allowPublicationType)) {
            $allowPublicationType[] = '-';
        }
        $this->setComponentsAttribute('filterType', 'value', implode(',', $allowPublicationType));
    }
}
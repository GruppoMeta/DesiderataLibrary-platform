<?php
class desiderataLibrary_modules_ontologybuilder_controllers_entityFormEdit_ajax_SaveDraftClose extends desiderataLibrary_modules_ontologybuilder_controllers_entityFormEdit_ajax_SaveDraft
{
    function execute($data)
    {
        parent::execute($data);
        return array('url' => $this->changeAction(''));
    }
}

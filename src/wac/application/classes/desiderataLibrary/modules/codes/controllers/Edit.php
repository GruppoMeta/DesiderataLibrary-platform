<?php
class desiderataLibrary_modules_codes_controllers_Edit extends org_glizycms_contents_controllers_activeRecordEdit_Edit
{
    public function execute($id)
    {
        if ($id) {
            $this->changeAction('');
        }
    }
}


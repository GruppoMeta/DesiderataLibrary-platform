<?php
class desiderataLibrary_modules_userManager_controllers_ajax_SaveClose extends desiderataLibrary_modules_userManager_controllers_ajax_Save
{
    function execute($data)
    {
        $result = parent::execute($data);

        if ($result['errors']) {
            return $result;
        }

        return array('url' => $this->changeAction(''));
    }
}
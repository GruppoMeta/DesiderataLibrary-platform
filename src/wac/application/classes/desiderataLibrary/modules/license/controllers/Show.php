<?php
class desiderataLibrary_modules_license_controllers_Show extends org_glizy_mvc_core_Command
{
    public function execute($id)
    {
        if ($id) {
            $editorId = desiderataLibrary_WAC::getEditorId();
            if ($editorId) {
                $ar = __ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.Publication')
                        ->load('getEditor', array('pubId' => $id))
                        ->first();

                if ($ar->editorId!=$editorId) {
                    $this->changeAction('');
                }
            }
        }
    }
}


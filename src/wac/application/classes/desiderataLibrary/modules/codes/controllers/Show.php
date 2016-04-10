<?php
class desiderataLibrary_modules_codes_controllers_Show extends org_glizy_mvc_core_Command
{
    public function executeLater($id)
    {
        $editorId = desiderataLibrary_WAC::getEditorId();
        if ($editorId) {
            $c = $this->view->getComponentById('dp1');
            $arCode = $c->getObject();

            if ($arCode->user_FK_editor_id!=$editorId) {
                $this->changeAction('');
            }
        }

    }
}


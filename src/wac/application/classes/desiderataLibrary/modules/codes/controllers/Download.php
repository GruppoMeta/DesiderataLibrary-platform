<?php
class desiderataLibrary_modules_codes_controllers_Download extends org_glizy_mvc_core_Command
{
    public function execute($id)
    {
        if ($id) {
            $editorId = desiderataLibrary_WAC::getEditorId();
            if ($editorId) {
                $arCode = __ObjectFactory::createModelIterator('desiderataLibrary.modules.codes.models.CodeGroup')
                        ->load('detail', array('id' => $id))
                        ->first();

                if ($arCode->user_FK_editor_id!=$editorId) {
                    $this->changeAction('');
                }
            }

            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.codes.models.Code')
                    ->load('detail');
            $output = '';
            foreach($it as $ar) {
                if (!$ar->code_status) {
                    $output .= $ar->code_value.PHP_EOL;
                }
            }

            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename=codici_'.$id.'.csv');
            header('Pragma: no-cache');
            header('Expires: 0');

            echo $output;
            exit;
        }

        $this->changeAction('');
    }
}


<?php
class desiderataLibrary_modules_license_controllers_Download extends org_glizy_mvc_core_Command
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

            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.Model')
                    ->load('licensesForPublication', array('pubId' => $id));
            $output = '';
            foreach($it as $ar) {
                $output .= $ar->user_firstName.','.$ar->user_lastName.','.$ar->user_email.PHP_EOL;
            }

            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename=licenze_'.$id.'.csv');
            header('Pragma: no-cache');
            header('Expires: 0');

            echo $output;
            exit;
        }

        $this->changeAction('');
    }
}


<?php
class desiderataLibrary_modules_libraries_controllers_Edit extends org_glizycms_contents_controllers_activeRecordEdit_Edit
{
    public function execute($id)
    {
        if ($id) {
            $c = $this->view->getComponentById('__model');
            $model = $c->getAttribute('value');
            $proxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ActiveRecordProxy');
            $data = $proxy->load($id, $model);

        	$licenseProxy = org_glizy_objectFactory::createObject('desiderataLibrary.modules.license.models.proxy.License');
        	$data['licenses'] = $licenseProxy->getLicensesForLibrary($id);
            $data['licensesOther'] = array();

            // se sono un editore devo modificare solo le mie licenze
            // le altre andranno nel campo licenses_hidden
            if ($this->user->groupId==desiderataLibrary_modules_userManager_enum_Group::EDITOR) {
                $ar = __ObjectFactory::createModel('org.glizy.models.User');
                $ar->load($this->user->id);
                $publisherId = (int)$ar->user_FK_editor_id;

                $editorLicenses = array();
                $otherLicenses = array();
                foreach($data['licenses'] as $l) {
                    if ($l['publisherId']===$publisherId) {
                        $editorLicenses[] = $l;
                    } else {
                        $otherLicenses[] = $l;
                    }
                }

                $data['licenses'] = $editorLicenses;
                $data['licensesOther'] = json_encode($otherLicenses);
            }


            $data['__id'] = $id;
            $this->view->setData($data);
        }
    }
}


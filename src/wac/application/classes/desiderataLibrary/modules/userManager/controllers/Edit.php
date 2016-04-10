<?php
class desiderataLibrary_modules_userManager_controllers_Edit extends org_glizycms_contents_controllers_activeRecordEdit_Edit
{
    public function execute($id)
    {
        if ($id) {
            $c = $this->view->getComponentById('__model');
            $model = $c->getAttribute('value');
            $proxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ActiveRecordProxy');
            $data = $proxy->load($id, $model);
            $data['licensesOther'] = array();

            if (__Config::get('PSW_METHOD')) {
            	$data['user_password'] = '';
            	$this->setComponentsAttribute('user_password', 'type', 'password');
            	$this->setComponentsAttribute('user_password', 'required', 'false');
            }

            // editore
            if (isset($data['user_FK_editor_id']) && is_int($data['user_FK_editor_id'])) {
            	$arEditor = org_glizy_objectFactory::createModel('desiderataLibrary.modules.editors.models.Model');
            	if ($arEditor->load($data['user_FK_editor_id'])) {
            		$data['user_FK_editor_id'] = array(
            			'id' => $data['user_FK_editor_id'],
            			'text' => $arEditor->editor_name
            		);
            	}
            }

            // licenze per utente normale
            if ($data['user_FK_usergroup_id']==desiderataLibrary_modules_userManager_enum_Group::USER) {
                $licenseProxy = org_glizy_objectFactory::createObject('desiderataLibrary.modules.license.models.proxy.License');
                $data['licenses'] = $licenseProxy->getLicensesForUser($id);

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

            }

            $data['user_interests'] = explode(';', $data['user_interests']);

            $data['__id'] = $id;
            $this->view->setData($data);
        }
    }
}


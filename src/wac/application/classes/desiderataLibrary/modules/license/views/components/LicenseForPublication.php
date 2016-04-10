<?php
class desiderataLibrary_modules_license_views_components_LicenseForPublication extends org_glizy_components_Component
{
    public function render()
    {
        $output = '';
        $pubId = __Request::get('id');
        $menuProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.MenuProxy');
        $menu = $menuProxy->getMenuFromId($pubId, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'));
        if ($menu) {
            $output .= '<h3>Licenze per la pubblicazione: '.$menu->menudetail_title.'</h3>';

            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.license.models.Model')
                    ->load('licensesForPublication', array('pubId' => $pubId));
            foreach ($it as $ar) {
                $editLink = __Link::makeUrl('linkEditUser', array('id' => $ar->user_id));
                $output .= '<a title="Modifica" href="'.$editLink.'"><i class="icon-pencil"></i> </a>'.$ar->user_id.' '.$ar->user_firstName.' '.$ar->user_lastName.' <a href="mailto:'.$ar->user_email.'">'.$ar->user_email.'</a><br />';
            }
        }

        $this->addOutputCode($output);
    }
}
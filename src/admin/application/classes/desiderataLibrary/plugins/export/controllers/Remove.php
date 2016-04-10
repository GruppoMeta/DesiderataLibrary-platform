<?php
class desiderataLibrary_plugins_export_controllers_REmove extends org_glizy_mvc_core_Command
{
    public function execute($menuId)
    {
        $menuId = json_decode($menuId);

        if ($menuId && is_array($menuId)) {
            $menuProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.MenuProxy');

            foreach($menuId as $v) {
                $menu = $menuProxy->getMenuFromId($v, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'));
                $jobFactory = org_glizy_objectFactory::createObject('gruppometa.jobmanager.JobFactory');
                $params = array(
                    'menuIds' => array($v),
                    'host' => str_replace('/admin', '/', GLZ_HOST)
                );
                $jobFactory->createJob('desiderataLibrary.plugins.export.services.RemoveService',
                    $params,
                    'Rimozione della pubblicazione: '.$menu->menudetail_title.' dall\'indice id: '.$v,
                    'BACKGROUND'
                );
            }
            $this->changePage('link', array('pageId' => 'pluginsReport'));
        } else {
            $this->changeAction('index');
        }
    }
}
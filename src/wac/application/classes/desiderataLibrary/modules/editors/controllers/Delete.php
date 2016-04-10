<?php
class desiderataLibrary_modules_editors_controllers_Delete extends org_glizy_mvc_core_Command
{
    public function execute($id)
    {
        if ($id) {

            $c = $this->view->getComponentById('__model');
            $model = $c->getAttribute('value');

            $proxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ActiveRecordProxy');
            $data = $proxy->load($id, $model);

            if ($data) {
                if ($data['editor_hasBlog']) {
                    $blogBuilder = __ObjectFactory::createObject('desiderataLibrary.modules.editors.services.BlogBuilder', $id, $data['editor_blogPath']);
                    $blogBuilder->deleteBlog();
                }

                $proxy->delete($id, $model);
            }

            org_glizy_helpers_Navigation::goHere();
        }
    }
}

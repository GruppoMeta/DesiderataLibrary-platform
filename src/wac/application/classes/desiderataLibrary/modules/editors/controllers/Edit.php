<?php
class desiderataLibrary_modules_editors_controllers_Edit extends org_glizycms_contents_controllers_activeRecordEdit_Edit
{
    public function execute($id)
    {
        if ($id) {
            $c = $this->view->getComponentById('__model');
            $model = $c->getAttribute('value');
            $proxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ActiveRecordProxy');
            $data = $proxy->load($id, $model);

            if (!$data['editor_blogPath']) {
                $data['editor_blogPath'] = __Config::get('GLIZY_DOMAIN').'/backoffice/blog/'.glz_slugify($data['editor_name']);
            }

            $data['__id'] = $id;
            $this->view->setData($data);
        } else {
            $this->setComponentsVisibility(array('editor_hasBlog', 'editor_blogPath'), false);
        }
    }
}


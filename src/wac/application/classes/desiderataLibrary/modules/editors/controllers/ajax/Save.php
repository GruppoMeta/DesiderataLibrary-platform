<?php
class desiderataLibrary_modules_editors_controllers_ajax_Save extends org_glizycms_contents_controllers_activeRecordEdit_ajax_Save
{
    public function execute($data)
    {
        $data = glz_maybeJsonDecode($data, false);
        $result = parent::execute($data);
        if (isset($result['errors'])) {
            return $result;
        }

        if ($data->__id != '') {
            $blogBuilder = __ObjectFactory::createObject('desiderataLibrary.modules.editors.services.BlogBuilder', $data->__id, $data->editor_blogPath);

            if ($data->editor_hasBlog) {
                $error = $blogBuilder->createBlog();
                if ($error!==true) {
                    return array('errors' => array($error));
                }
            } else {
                $blogBuilder->deleteBlog();
            }
        }

        return $result;
    }
}
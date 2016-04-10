<?php
class gruppometa_easybook_controllers_rest_comments_SetComment extends org_glizy_rest_core_CommandRest
{
    function execute($pubId, $pageId)
    {
        // if (!$this->user->isLogged()) return false;
        $pubId = (int)$pubId;
        $pageId = (int)$pageId;
        $data = json_decode(__Request::get('__postBody__'));

        if ($pubId && $pageId && $data) {
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', $pubId);

            // TODO se siamo in modifica verificare i permessi
            $data->__model = 'gruppometa.easybook.models.Comment';
            $data->pubId = $pubId;
            $data->menuId = $pageId;
            $data->userId = 1;
            $data->date = new org_glizy_types_DateTime();
            if (__Request::exists('id')) {
                $data->__id = __Request::get('id');
            }
            $proxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ActiveRecordProxy');
            $result = $proxy->save($data);

            if ($result['__id']) {
                return array('id' => $result);
            } else {
                return array('http-status' => 500, 'errors' => $result);
            }
        }

        return array('http-status' => 400);
    }
}
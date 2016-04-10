<?php
class gruppometa_easybook_controllers_rest_comments_DeleteComment extends org_glizy_rest_core_CommandRest
{
    function execute($pubId, $pageId, $id)
    {
        // if (!$this->user->isLogged()) return false;
        $pubId = (int)$pubId;
        $pageId = (int)$pageId;
        $id = (int)$id;

        if ($pubId && $pageId && $id) {
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', $pubId);

            // TODO controllo permessi
            $ar = org_glizy_objectFactory::createModel('gruppometa.easybook.models.Comment');
            $ar->delete($id);

            return true;
        }

        return array('http-status' => 400);
    }
}
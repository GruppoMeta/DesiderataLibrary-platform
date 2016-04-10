<?php
class gruppometa_easybook_controllers_rest_comments_GetComments extends org_glizy_rest_core_CommandRest
{
    function execute($pubId, $pageId)
    {
        // if (!$this->user->isLogged()) return false;
        $pubId = (int)$pubId;
        $pageId = (int)$pageId;

        if ($pubId && $pageId) {
            $this->directOutput = true;
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', $pubId);

            $results = array();
            $it = org_glizy_objectFactory::createModelIterator('gruppometa.easybook.models.Comment')
                        ->load('getComments', array('menuId' => $pageId));

            foreach ($it as $ar) {
                $results[] = $ar->getVO($this->user->id, $this->user->acl('easybook', 'comment.admin'));
            }

            return json_encode($results);
        }

        return array('http-status' => 400);
    }
}
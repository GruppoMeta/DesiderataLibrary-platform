<?php
class gruppometa_easybook_models_vo_ContentEmptyVO extends gruppometa_easybook_models_vo_AbstractContentVO
{
    public $redirectId;

    function __construct($content, $node, $pubId)
    {
        parent::__construct($content, $node, $pubId);

        $it = org_glizy_ObjectFactory::createObject('org.glizy.application.SiteMapIterator', $node->getSiteMap());
        $it->setNode($node);
        $nextNode = $it->moveNext();
        $this->redirectId = $nextNode ? $nextNode->id : null;
    }
}
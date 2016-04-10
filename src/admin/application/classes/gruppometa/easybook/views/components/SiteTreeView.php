<?php
class gruppometa_easybook_views_components_SiteTreeView  extends org_glizycms_contents_views_components_SiteTreeView
{
    public function process() {
        parent::process();
        $this->_content->ajaxUrl = 'ajax.php?pageId='.$this->_application->getPageId().'&multisite=1&ajaxTarget='.$this->getId().gruppometa_easybook_Easybook::getSiteId().'&action=';
        $this->_content->addUrl .= '?multisite=1'.gruppometa_easybook_Easybook::getSiteId();

        $pubType = gruppometa_easybook_Easybook::getPublicationInfoCurrent();
        $arPublication = org_glizy_ObjectFactory::createModelIterator('gruppometa.easybook.models.Publication')
                            ->load('getPublicationById', array('id' => __Request::get('menu_id')))->first();
        $this->_content->publicationTitle = $arPublication->menudetail_title;
        $this->_content->publicationCover = $arPublication->document->cover ?
                                                org_glizy_helpers_Media::getResizedImageUrlById($arPublication->document->cover->id, false, 96, 148, true) :
                                                $pubType->cover;
    }
}


class gruppometa_easybook_views_components_SiteTreeView_render extends org_glizy_components_render_Render
{
    function getDefaultSkin()
    {
        $skin = <<<EOD
<div id="treeview">
    <div id="page-detail">
        <img width="62" tal:attributes="src Component/publicationCover" />
        <h2 tal:content="Component/publicationTitle"></h2>
        <div class="actions" style="display:none">
            <a href="#"><i class="icon-pencil icon-white"></i> Modifica</a>
            <a href="#"><i class="icon-remove icon-white"></i> Cancella</a>
        </div>
    </div>
    <div id="treeview-title">
        <a id="js-glizycmsSiteTreeAdd" tal:attributes="href Component/addUrl"><i class="icon-plus"></i> <span tal:omit-tag="" tal:content="Component/addLabel" /></a>
        <h3 tal:content="Component/title"></h3>
    </div>
    <div id="treeview-inner">
        <div id="js-glizycmsSiteTree" tal:attributes="data-ajaxurl Component/ajaxUrl"></div>
    </div>
    <div id="openclose">
        <i class="icon-chevron-left"></i>
    </div>
</div>
EOD;
        return $skin;
    }
}
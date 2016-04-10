<?php
class desiderataLibrary_models_vo_ResultVO
{
    public $id;
    public $publicationId;
    public $title;
    public $subtitle;
    public $preview;
    public $isFree;
    public $inLibrary;
    public $geo;
    public $isPdf;

    private static $publicationService;
    private static $contentProxy;
    private static $siteMap;

    public static function init()
    {
        if (!self::$publicationService) {
            self::$publicationService = __ObjectFactory::createObject('desiderataLibrary.service.PublicationService');
            self::$contentProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
            self::$siteMap = &org_glizy_ObjectFactory::createObject('org.glizycms.core.application.SiteMapDB');
            self::$siteMap->getSiteArray();
        }
    }

    private function getDesiderata($userId, $volumeId, $contenId)
    {
        $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.desiderata.models.Desiderata')
            ->where('desiderata_FK_user_id', $userId)
            ->where('desideratadetail_volumeId', $volumeId)
            ->where('desideratadetail_contentId', $contenId);

        $desiderataIdArray = array();

        foreach ($it as $ar) {
            $desiderataIdArray[] = $ar->desiderata_id;
        }

        return $desiderataIdArray;
    }

    function createFromSolr($userId, $doc)
    {
        $node = self::$siteMap->getNodeById($doc->publicationId_i);

        $this->id = (int) $doc->id;
        $this->publicationId = $doc->publicationId_i;
        $this->title = $doc->publicationTitle_t;
        $this->subtitle = $doc->sectionTitle_t;
        $this->preview = glz_strtrim($doc->text_t, 300);
        $this->isFree = @$doc->isFree_i===1;
        $this->inLibrary = self::$publicationService->isInLibrary($doc->publicationId_i);
        $this->geo = $doc->latlon;
        $this->isPdf = $node->pageType == 'PublicationPdf';
        $this->inDesiderata = $this->getDesiderata($userId, $doc->publicationId_i, (int) $doc->id);
    }

    function createFromVolumeIdContentId($userId, $volumeId, $contentId)
    {
        $node = self::$siteMap->getNodeById($volumeId);
        $publicationContent = self::$contentProxy->readContentFromMenu($volumeId, __ObjectValues::get('org.glizy', 'languageId'));
        $content = self::$contentProxy->readContentFromMenu($contentId, __ObjectValues::get('org.glizy', 'languageId'));

        $this->id = $contentId;
        $this->publicationId = $volumeId;
        $this->title = $publicationContent->__title;
        $this->subtitle = $content->__title;
        $this->preview = glz_strtrim($content->text, 300);
        $this->isFree = @$publicationContent->isFree === 1;
        $this->inLibrary = self::$publicationService->isInLibrary($volumeId);
        $this->geo = $content->geo;
        $this->isPdf = $node->pageType == 'PublicationPdf';
        $this->inDesiderata = $this->getDesiderata($userId, $volumeId, $contentId);
    }
}
<?php
class gruppometa_easybook_models_vo_PublicationVO
{
    public $id;
    public $title;
    public $author;
    public $publisher;
    public $code;
    public $coverSmall;
    public $coverBig;
    public $startId;
    public $type;
    public $isbn;
    public $price;
    public $isFree;
    public $blogUrl;

    function __construct($ar)
    {
        $publicationTypeInfo = gruppometa_easybook_Easybook::getPublicationInfoForType($ar->menu_pageType);

        $this->id = $ar->menu_id;
        $this->title = $ar->menudetail_title;
        $this->author = $ar->document->author ? $ar->document->author : array();
        $this->publisher = $ar->document->publisher->text;
        $this->code = (string)$ar->menu_id;
        $this->startId = $ar->keyInDataExists('menuStart') ? (int)$ar->menuStart : null;
        $this->type = $ar->menu_pageType;
        $this->isbn = $ar->document->isbn;
        $this->price = $ar->document->price;
        $this->isFree = @$ar->document->isFree==1;
        $this->blogUrl = @$ar->document->blogUrl;

        if ($ar->document->cover) {
            $params = json_decode($publicationTypeInfo->fe_coverSmallResize);
            array_unshift($params, $ar->document->cover->id);
            $this->coverSmall = GLZ_HOST.'/'.forward_static_call_array( array('org_glizy_helpers_Media', 'getResizedImageUrlById'), $params);

            $params = json_decode($publicationTypeInfo->fe_coverResize);
            array_unshift($params, $ar->document->cover->id);
            $this->coverBig = GLZ_HOST.'/'.forward_static_call_array( array('org_glizy_helpers_Media', 'getResizedImageUrlById'), $params);
        } else {
            // mette le copertine di default
            $this->coverSmall = GLZ_HOST.'/'.$publicationTypeInfo->fe_coverSmall;
            $this->coverBig = GLZ_HOST.'/'.$publicationTypeInfo->fe_cover;
        }
    }
}

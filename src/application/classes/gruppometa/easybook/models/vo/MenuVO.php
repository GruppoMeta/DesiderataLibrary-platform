<?php
class gruppometa_easybook_models_vo_MenuVO
{
    public $id;
    public $pubId;
    public $type;
    public $title;
    public $number;
    public $depth;
    public $folder;

    function __construct($id, $publicationId, $title, $number, $depth, $folder, $type)
    {
        $this->id = $id;
        $this->pubId = $publicationId;
        $this->title = $title;
        $this->number =  $number;
        $this->depth =  $depth;
        $this->folder = $folder;
        $this->type = $type;
    }

    public function addChildren($node, $children)
    {
        if (is_array($this->children)) {
            $this->children = array_merge($this->children, $children);
        } else {
            $this->children = $children;
        }
        $this->state = $node->depth <= 2 ? 'open' : 'closed';
    }

    public static function create($node, $content, $pubId, $type)
    {
        return new self($node->id, $pubId, strip_tags($node->title), @$content->number, $node->depth, gruppometa_easybook_EasybookFE::isFolder($node, $content), $type);
    }

    public static function createFromDesiderata($ar, $type)
    {
        return new self($ar->desideratadetail_contentId, $ar->desideratadetail_volumeId, strip_tags($ar->title), '', $ar->depth, false, $type);
    }
}

<?php
class gruppometa_easybook_models_vo_MenuPdfVO
{
    public $id;
    public $publicationId,
    public $title;
    public $number;
    public $depth;
    public $folder;
    public $pdfStart;
    public $pdfTo;

    function __construct($node, $content)
    {
        $this->id = $node->id;
        $this->title = strip_tags($node->title);
        $this->number =  $content->number;
        $this->depth =  $node->depth;
        $this->folder = gruppometa_easybook_EasybookFE::isFolder($node, $content);
        if ($node->pageType = 'TextPdf') {
            $this->pdfStart = $content->pdfStart;
            $this->pdfTo = $content->pdfTo;
        }
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
}

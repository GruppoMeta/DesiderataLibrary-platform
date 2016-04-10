<?php
class desiderataLibrary_modules_annotations_models_vo_AnnotationVO
{
    public $id;
    public $type;
    public $title;
    public $data;
    public $user_id;
    public $volume_id;
    public $content_id;
    public $created_at;
    public $updated_at;

    public function __construct($ar)
    {
        $this->id = $ar->annotation_id;
        $this->type = $ar->annotation_type;
        $this->title = $ar->annotation_title;
        $this->data = $ar->annotation_data;
        $this->user_id = $ar->annotation_user_id;
        $this->volume_id = $ar->annotation_volume_id;
        $this->content_id = $ar->annotation_content_id;
        $this->created_at = $ar->annotation_created_at;
        $this->updated_at = $ar->annotation_updated_at;
    }
}
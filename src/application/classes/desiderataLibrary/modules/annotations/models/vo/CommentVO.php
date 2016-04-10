<?php
class desiderataLibrary_modules_annotations_models_vo_CommentVO extends desiderataLibrary_modules_annotations_models_vo_AnnotationVO
{
    public function __construct($ar)
    {
        parent::__construct($ar);
        $data = json_decode($this->data);
        $user = __ObjectFactory::createModel('org.glizycms.userManager.models.User');
        $user->load($ar->annotation_user_id);
        foreach ($data[0]->comments as $i => $comment) {
            $comment->authorName = $user->user_firstName.' '.$user->user_lastName;
        }
        $this->data = json_encode($data);
    }
}
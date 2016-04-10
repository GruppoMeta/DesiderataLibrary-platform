<?php
class desiderataLibrary_modules_desiderata_controllers_Load extends org_glizy_rest_core_CommandRest
{
    function execute($id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.Desiderata');

            if ($ar->find(array('desiderata_id' => $id,'desiderata_FK_user_id' => $this->user->id))) {
                if ($ar->desiderata_coverName) {
                    $coverPath = GLZ_HOST.'/'.__Paths::get('APPLICATION_MEDIA_ARCHIVE').'users/'.$this->user->id.'/'.$ar->getId().'/'.$ar->desiderata_coverName;
                } else {
                    $coverPath = GLZ_HOST.'/'.__Paths::get('STATIC_DIR').'images/desiderata_cover.png';
                }

                $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.desiderata.models.Desiderata')
                    ->where('desiderata_id', $id);
                    
                desiderataLibrary_models_vo_ResultVO::init();

                $elements = array();
                foreach ($it as $arDetail) {
                    $resultVO = __ObjectFactory::createObject('desiderataLibrary.models.vo.ResultVO');
                    $resultVO->createFromVolumeIdContentId($this->user->id, $arDetail->desideratadetail_volumeId, $arDetail->desideratadetail_contentId);
                    $elements[] = $resultVO;
                }

                $result = array(
                    'id' => $ar->getId(),
                    'title' => $ar->desiderata_title,
                    'tags' => json_decode($ar->desiderata_tags),
                    'elements' => $elements,
                    'creationDate' => $ar->desiderata_created_at,
                    'cover' => $coverPath
                );
            } else {
	    	    $result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
	    	}
        }

        return $result;
    }
}
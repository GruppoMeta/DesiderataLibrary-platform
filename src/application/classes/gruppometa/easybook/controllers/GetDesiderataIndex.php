<?php
class gruppometa_easybook_controllers_GetDesiderataIndex extends org_glizy_rest_core_CommandRest
{
    function execute($id)
    {
        if (!$this->user->isLogged()) {
            return desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
	    }

	    if ((int)$id) {
            $ar = __ObjectFactory::createModel('desiderataLibrary.modules.desiderata.models.Desiderata');

            $result = new StdClass();

            if ($ar->find(array('desiderata_id' => $id,'desiderata_FK_user_id' => $this->user->id))) {
                $structure = new StdClass();
                $structure->id = $ar->getId();
                $structure->title = $ar->desiderata_title;
                $structure->number = null;
                $structure->depth = 1;
                $structure->folder = false;

                $children = array();
                $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.desiderata.models.Desiderata')
                    ->where('desiderata_id', $id);

                $contentProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');

                foreach ($it as $arDetail) {
                    $content = $contentProxy->readContentFromMenu($arDetail->desideratadetail_contentId, __ObjectValues::get('org.glizy', 'languageId'));
                    $arPublication = __ObjectFactory::createModel('gruppometa.easybook.models.Publication');
                    $arPublication->load($arDetail->desideratadetail_volumeId);

                    $arDetail->title = $content->__title;
                    $arDetail->depth = 2;
                    $content = new StdClass();
                    $menu = gruppometa_easybook_models_vo_MenuVO::createFromDesiderata($arDetail, $arPublication->menu_pageType ? 'pdf' : 'liquid');
                    $children[] = $menu;
                }

                $structure->children = $children;

                $result->structure = $structure;
                $result->customCss = '';
                $result->type = 'desiderata';
                $result->title = $ar->desiderata_title;
            } else {
	    	    $result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
	    	}

            return $result;
        }
        return false;
    }
}
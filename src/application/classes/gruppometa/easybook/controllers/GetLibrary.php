<?php
class gruppometa_easybook_controllers_GetLibrary extends org_glizy_rest_core_CommandRest
{
	function execute()
	{
	    if (!$this->user->isLogged()) {
            return desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
	    }

        $skipCheckPublicationState = $this->user->groupId === 2 || $this->user->groupId === 6 || gruppometa_easybook_EasybookFE::isBackdoorUser($this->user);

	    $licenses = gruppometa_easybook_EasybookFE::getLicenses();
        $results = array();

        if (!$licenses) {
            return $results;
        }

		$it = org_glizy_ObjectFactory::createModelIterator('gruppometa.easybook.models.Publication')
			->load("getPublicationsWithStart", array('replace' => array(
                    '##menu_id##' => "(".implode(", ", $licenses).")"
                )));

        foreach ($it as $ar) {
            if (('2' == $ar->document->state || $skipCheckPublicationState)) {
                $results[] = org_glizy_ObjectFactory::createObject('gruppometa.easybook.models.vo.PublicationVO', $ar);
            }
        }
        return $results;
	}
}

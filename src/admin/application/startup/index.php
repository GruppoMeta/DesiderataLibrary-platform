<?php
org_glizy_debug_Module::registerModule();
org_glizycms_Glizycms::init();

$application = org_glizy_ObjectValues::get('org.glizy', 'application' );
if ($application) {
    $application->registerProxy('desiderataLibrary.modules.ontologybuilder.service.FieldTypeService');
    $application->registerProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');
    $application->registerProxy('desiderataLibrary.modules.ontologybuilder.service.LocaleService');
}

__ObjectFactory::remapClass('org.glizycms.contents.views.renderer.CellEditDelete', 'gruppometa.easybook.views.renderer.CellEditDelete');
__ObjectFactory::remapClass('org.glizycms.models.Media', 'gruppometa.easybook.modules.mediaArchive.models.Media');
__ObjectFactory::remapClass('gruppometa.jobmanager.models.Job', 'desiderataLibrary.models.Job');
__ObjectFactory::remapClass('org.glizycms.contents.controllers.siteTree.ajax.GetSiteTree', 'desiderataLibrary.controllers.siteTree.ajax.GetSiteTree');
org_glizycms_speakingUrl_Manager::registerResolver(org_glizy_ObjectFactory::createObject('desiderataLibrary.modules.ontologybuilder.EntityResolver'));

$listener = org_glizy_ObjectFactory::createObject('desiderataLibrary.modules.user.UserListener');
$listener2 = org_glizy_ObjectFactory::createObject('gruppometa.easybook.EasybookListener');

$log0 = org_glizy_log_LogFactory::create('DB', array(), 255, 'easybook');
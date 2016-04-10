<?php
org_glizycms_Glizycms::init();
$listener = org_glizy_ObjectFactory::createObject('desiderataLibrary.modules.user.UserListener');

gruppometa_easybook_EasybookFE::checkExportPluginRequest();

glz_loadLocale('userModules.ontologybuilder');

__Paths::addClassSearchPath('admin/application/classes/');

$application = org_glizy_ObjectValues::get('org.glizy', 'application' );
if ($application) {
    $application->registerProxy('desiderataLibrary.modules.ontologybuilder.service.FieldTypeService');
    $application->registerProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');
    $application->registerProxy('desiderataLibrary.modules.ontologybuilder.service.LocaleService');
}
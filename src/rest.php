<?php
require_once("core/core.inc.php");

$application = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.Application', 'application');
__Paths::add('APPLICATION_TO_ADMIN_CACHE', __Paths::get('APPLICATION').'../cache/');
org_glizy_Paths::addClassSearchPath('admin/application/classes/');
$application->setLanguage(__Config::get('DEFAULT_LANGUAGE'));
$application->run();

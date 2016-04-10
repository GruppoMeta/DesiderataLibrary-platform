<?php
ob_start();
setlocale(LC_TIME, "it_IT", "it", "it_IT.utf8");

require_once("../core/core.inc.php");

org_glizy_ObjectValues::set('org.glizy', 'languageId', 1);
org_glizy_ObjectValues::set('org.glizy', 'editingLanguageId', 1);

$application = org_glizy_ObjectFactory::createObject('org.glizycms.core.application.AdminApplication', 'application', '../', '../application/');
$application->useXmlSiteMap = true;
$application->setLanguage('it');
$application->runAjax();

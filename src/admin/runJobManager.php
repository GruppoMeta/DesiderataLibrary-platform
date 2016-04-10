<?php
chdir( realpath( dirname( __FILE__ ) ) );

require_once("../core/core.inc.php");
$application = org_glizy_ObjectFactory::createObject('org.glizycms.core.application.AdminApplication', 'application', '../', '../application/');
$application->runSoft();
$application->executeCommand('gruppometa.jobmanager.controllers.JobManager');

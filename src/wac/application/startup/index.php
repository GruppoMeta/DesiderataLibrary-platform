<?php
org_glizy_debug_Module::registerModule();
glz_loadLocale('desiderata');

$log0 = org_glizy_log_LogFactory::create('DB', array(), 255, 'desiderata.wac');

$listener = org_glizy_ObjectFactory::createObject('desiderataLibrary.modules.user.UserListener');
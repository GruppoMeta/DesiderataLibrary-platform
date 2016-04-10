<?php
class desiderataLibrary_modules_ontologybuilder_Paths
{
    const BASE_PATH = 'classes/userModules/ontologybuilder/';

    public static function getLocalePath($languageId)
    {
        $appPath = __Paths::get('APPLICATION_TO_ADMIN') ? __Paths::getRealPath('APPLICATION_TO_ADMIN') : __Paths::getRealPath('APPLICATION');
        return $appPath.self::BASE_PATH.'locale/'.$languageId.'.php';
    }
}
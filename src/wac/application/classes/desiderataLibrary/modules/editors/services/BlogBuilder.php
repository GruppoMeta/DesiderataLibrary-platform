<?php
class desiderataLibrary_modules_editors_services_BlogBuilder extends GlizyObject
{
    private $editorId;
    private $blogName;
    private $blogPath;

    function __construct($editorId, $path)
    {
        $this->editorId = $editorId;
        $this->blogName = array_pop(explode('/', $path));
        $this->blogPath = '../blog/'.$this->blogName;
    }

    public function createBlog()
    {
        // controlla se la cartella del blog è già creata
        if (!file_exists($this->blogPath)) {
            $dbName = 'sr_desideratalibrary_blog_'.$this->editorId;

            $error = $this->checkWritable($this->blogName);
            if ($error!==true) {
                return 'File o cartella non scrivibile: '.$error;
            }

            $error = $this->createBlogFolder($this->blogPath);
            if ($error===false) {
                return 'Impossibile creare la cartella: '.$this->blogPath;
            }

            $error = $this->updateHtaccess($this->blogPath, $this->blogName, true);
            if ($error===false) {
                return 'Impossibile aggiornare il file htaccess';
            }

            $error = $this->createConfigFile($this->blogPath, $this->blogName, $dbName, $this->editorId);
            if ($error===false) {
                return 'Impossibile creare il file di configurazione';
            }

            $error = $this->createConfigFileAdmin($this->blogPath, $this->blogName);
            if ($error===false) {
                return 'Impossibile creare il file di configurazione';
            }

            $alreadyInstallad = $this->createMediaArchive($this->blogPath, $this->blogName);
            if ($alreadyInstallad) {
                $this->createDb($dbName);
            }
        }
        return true;
    }

    public function deleteBlog()
    {
         if (file_exists($this->blogPath)) {
            $this->updateHtaccess($this->blogPath, $this->blogName, false);
            $this->removeConfigFiles($this->blogPath, $this->blogName);
            $this->removeBlogFolder($this->blogPath);
        }
    }


    private function checkWritable($domain)
    {
        $blogPathInstallation = realpath(__Config::get('desiderataLibrary.blog.path'));
        $paths = array(
                '../blog',
                $blogPathInstallation.'/.htaccess',
                $blogPathInstallation.'/application',
                $blogPathInstallation.'/application/config',
                $blogPathInstallation.'/application/config/config_'.$domain.'.xml',
                $blogPathInstallation.'/application/mediaArchive',
                $blogPathInstallation.'/application/mediaArchive/'.$domain,
                $blogPathInstallation.'/admin/application/config',
                $blogPathInstallation.'/admin/application/config/config_'.$domain.'.xml',
            );
        foreach($paths as $p) {
            $fileExists = file_exists($p);
            if ($fileExists && !is_writable($p)) {
                return $p;
            } else if (!$fileExists) {
                $pathInfo = pathinfo($p);
                if (!is_writable($pathInfo['dirname'])) {
                    return $p;
                }
            }
        }
        return true;
    }

    private function createBlogFolder($blogPath)
    {
        if (!file_exists('../blog')) {
            mkdir('../blog');
        }
        $blogPathInstallation = realpath(__Config::get('desiderataLibrary.blog.path'));
        return symlink($blogPathInstallation, $blogPath);
    }

    private function removeBlogFolder($blogPath)
    {
        @unlink($blogPath);
    }

    private function updateHtaccess($blogPath, $domain, $add)
    {
        $htaccessPath = $blogPath.'/.htaccess';
        $htaccess = file_get_contents($htaccessPath);
        $htaccess = preg_replace("/##start\s".$domain."##([^#])*##end\s".$domain."##/i", "", $htaccess);
        if ($add) {
            $htaccess = str_replace( '</IfModule>',
                                        '##start '.$domain.'##'.GLZ_COMPILER_NEWLINE2.
                                        'SetEnvIf Request_URI "^(.*)/blog/'.$domain.'" GLIZY_APPNAME='.$domain.GLZ_COMPILER_NEWLINE2.
                                        '##end '.$domain.'##'.GLZ_COMPILER_NEWLINE2.
                                        '</IfModule>',
                                    $htaccess );
        }
        return file_put_contents($htaccessPath, $htaccess);
    }

    private function createConfigFile($blogPath, $domain, $dbName, $editorId)
    {
        $config = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<glz:Config xmlns:glz="http://www.glizy.org/dtd/1.0/">
    <glz:Import src="config.xml" />
    <glz:Param name="DB_NAME" value="$dbName" />
    <glz:Param name="desiderataLibrary.blog.editor" value="$editorId" />
    <glz:Param name="GLZ_HOST" value="{{GLIZY_DOMAIN}}/backoffice/blog/$domain" />
</glz:Config>
EOD;

        return file_put_contents($blogPath.'/application/config/config_'.$domain.'.xml', $config);
    }

    private function createConfigFileAdmin($blogPath, $domain)
    {
        $config = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<glz:Config xmlns:glz="http://www.glizy.org/dtd/1.0/">
    <glz:Import src="config.xml" />
    <glz:Param name="GLZ_HOST" value="{{GLIZY_DOMAIN}}/backoffice/blog/$domain/admin" />
</glz:Config>
EOD;

        return file_put_contents($blogPath.'/admin/application/config/config_'.$domain.'.xml', $config);
    }

    private function removeConfigFiles($blogPath, $domain)
    {
        @unlink($blogPath.'/application/config/config_'.$domain.'.xml');
        @unlink($blogPath.'/admin/application/config/config_'.$domain.'.xml');
    }

    private function createMediaArchive($blogPath, $domain)
    {
        $mediaArchivePath = $blogPath.'/application/mediaArchive/'.$domain;

        if (!file_exists($mediaArchivePath)) {
            mkdir($mediaArchivePath);
            mkdir($mediaArchivePath.'/Archive');
            mkdir($mediaArchivePath.'/Audio');
            mkdir($mediaArchivePath.'/Flash');
            mkdir($mediaArchivePath.'/Image');
            mkdir($mediaArchivePath.'/Office');
            mkdir($mediaArchivePath.'/Other');
            mkdir($mediaArchivePath.'/Pdf');
            mkdir($mediaArchivePath.'/Video');
            return true;
        }
        return false;
    }

     private function createDb($dbName)
    {
        $connectionNumber = 10;
        __Config::set('DB_TYPE#'.$connectionNumber, __Config::get('DB_TYPE'));
        __Config::set('DB_HOST#'.$connectionNumber, __Config::get('DB_HOST'));
        __Config::set('DB_NAME#'.$connectionNumber, $dbName);
        __Config::set('DB_USER#'.$connectionNumber, __Config::get('DB_USER'));
        __Config::set('DB_PSW#'.$connectionNumber, __Config::get('DB_PSW'));
        __Config::set('DB_PREFIX#'.$connectionNumber, __Config::get('DB_PREFIX'));

        if (__Config::get('DB_PORT')) {
            __Config::set('DB_PORT#'.$connectionNumber, __Config::get('DB_PORT'));
        }

        $dumpPath = __Paths::getRealPath('APPLICATION_CLASSES').'desiderataLibrary/modules/editors/services/sql/blog.sql';
        org_glizy_dataAccessDoctrine_DataAccess::importSqlFile($connectionNumber, $dumpPath, $dbName, array('dropAndCreate' => true));
    }
}
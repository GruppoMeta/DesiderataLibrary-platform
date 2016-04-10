<?php
class desiderataLibrary_plugins_export_services_OfflineService extends GlizyObject
{
    private $pubId;
    private $baseUrl;
    private $baseUrlRest;
    private $ar;
    private $exportFolder;
    private $debugRequest = false;
    private $headerKey;
    private $headerValue;
    protected $fileMap = array();

    public function init($host, $pubId)
    {
        $this->pubId = $pubId;
        $this->baseUrl = rtrim($host, '/').'/';
        $this->baseUrlRest = $this->baseUrl.'rest/';
        $this->ar = org_glizy_ObjectFactory::createModel('desiderataLibrary.plugins.export.models.Offline');
        $this->exportFolder = __Config::get('desiderataLibrary.plugins.offline.cache').'/'.$this->pubId;
        $this->headerKey = __Config::get('desiderataLibrary.plugins.offline.backdoor.key');
        $this->headerValue = __Config::get('desiderataLibrary.plugins.offline.backdoor.value');

        $this->cleanDB();
        $this->createExportFolder();
        $this->getPublicationIndex();
    }

    public function finish()
    {
        $this->createSqlLite();
        $this->createZip();
        $destName = __Config::get('desiderataLibrary.plugins.offline.folder').$this->pubId.'.zip';
        rename($this->exportFolder.'.zip', $destName);
        @chmod($destName, 0777);
    }

    /**
     * Esporta l'indice della pubblicazione
     */
    private function getPublicationIndex()
    {
        $qs = 'getPublicationIndex?id='.$this->pubId;
        $r = $this->makeRequest($qs);

        $this->ar->emptyRecord();
        $this->ar->offline_path = $qs;
        $this->ar->offline_value = $r;
        $this->ar->save();
    }


    /**
     * Esporta i contenuti della pagina
     */
    public function getContent($id)
    {
        $qs = 'getContent?contentId='.$id.'&id='.$this->pubId;
        $r = $this->makeRequest($qs);

        $r = $this->replaceInJson($r);

        $this->ar->emptyRecord();
        $this->ar->offline_path = $qs;
        $this->ar->offline_value = $r;
        $this->ar->save();
    }


    /**
     * Pulisce la tabella offline in mysql
     */
    private function cleanDB()
    {
        $it = $this->ar->createRecordIterator()
                ->load('deleteAllContents');
    }

    /**
     * Crea la cartella di esportazione
     */
    private function createExportFolder()
    {
        org_glizy_helpers_Files::deleteDirectory( $this->exportFolder );
        @mkdir( $this->exportFolder, 0777, true);
        @mkdir(__Config::get('desiderataLibrary.plugins.offline.folder'), 0777, true);
    }



    /**
     * Esegue la richiesta REST per il recupero dei dati dai servizi
     * @param  string $qs URI richiesta
     * @return string     Risposta
     */
    protected function makeRequest($qs)
    {
        if ($this->debugRequest) {
            echo $this->baseUrlRest.$qs.PHP_EOL;
        }
        $opts = array(
                'http'=>array(
                    'method'=> 'GET',
                    'header'=> 'Accept: application/json'."\r\n".
                               $this->headerKey.': '.$this->headerValue
                )
            );

        $context = stream_context_create($opts);
        try {
            $r = file_get_contents($this->baseUrlRest.$qs, false, $context);
        } catch (Exception $e) {
            // $this->jobError('Errore nella richiesta ai servizi'.PHP_EOL.
            //                     $this->baseUrlRest.$qs
            //         );
        }

        return $r;
    }

    /**
     * Metodo per sostituire i dati del media nel JSON
     */
    protected function replaceInJson($r)
    {
        $json = json_decode($r);
        if ($json) {
            $this->replaceMedia($json);
            return json_encode($json);
        }
        return $r;
    }


    protected function replaceMedia(&$json)
    {
        $path = '';
        $baseUrlReg = str_replace('/', '\/', $this->baseUrl);

        if (is_object($json) && property_exists($json, 'info')) {
            $newInfo = new StdClass;
            $newInfo->{'2.5'} = $json->info->{'2.5'};
            $json->info = $newInfo;
        }

        // scorre in modo ricorsivo il JSON
        // per ricercare i dati dei media
        foreach ($json as $k=>&$v) {
            if (is_object($v) || is_array($v)) {
                $this->replaceMedia($v);
            } else if (is_string($v)) {

                // url di un media, per esempio per coverSmall
                if (preg_match('/(^'.$baseUrlReg.'.*)/Ui', $v)) {
                    $v = $this->copyFile($v, $path);
                }

                preg_match_all('/\s*(src=["\']('.$baseUrlReg.'.*)["\'])\s*/Ui', $v, $match);
                for($i=0; $i<count($match[0]);$i++) {
                    $url = $this->copyFile($match[2][$i], $path);

                    $v = str_replace($match[2][$i], $url, $v);
                }
            }
        }

    }


    /**
     * Scarica il file del media e lo copia nella cartella di esportazione
     * @param  string $url              URI del file
     * @param  string $path             path della cartella di esportazione
     */
    protected function copyFile($url, $path)
    {
        $url = str_replace('&amp;', '&', $url);
        $md5 = md5($url);

        if (!isset($this->fileMap[$path])) {
            $this->fileMap[$path] = array();
            @mkdir( $this->exportFolder.'/'.$path, 0777, true );
        }

        // controlla se il file è già stato scaricato
        if (!isset($this->fileMap[$path][$md5])) {
            $destFilename = $path.'/'.$md5;
            $destPath = $this->exportFolder.'/'.$destFilename;
            $error = false;
            if (strpos($url, '/application/mediaArchive')!==false) {
                $urlFile = str_replace($this->baseUrl, '../', $url);
            } else {
                $urlFile = str_replace(' ', '+', $url);
            }
            try {
                // copy($urlFile, $destPath);
                $opts = array(
                    'http'=>array(
                        'header'=> $this->headerKey.': '.$this->headerValue
                    )
                );
                $context = stream_context_create($opts);
                $r = file_get_contents($urlFile, false, $context);
                file_put_contents($destPath, $r);

                $ext = $this->getFileExtension($destPath);
                $destPathWithExt = $destPath.'.'.$ext;

                rename($destPath, $destPathWithExt);
                $this->fileMap[$path][$md5] = '##HOST##'.$destFilename.'.'.$ext;
                $this->fileMap['global'][$md5] = '##HOST##'.$destFilename.'.'.$ext;
            } catch (Exception $e) {
                // per ora vado avanti
                //$this->jobError('Errore nella copia del file: '.$urlFile.' in '.$destPath);
            }
        }

        return $this->fileMap[$path][$md5];
    }

    /**
     * Calcola l'estensione del file dal mimetype
     * @param  string $path Path del file
     * @return string       Estensione del file
     */
    protected function getFileExtension($path)
    {
        $extension = array(
                "application/msword" => "doc",
                "application/pdf" => "pdf",
                "application/postscript" => "ai",
                "application/postscript" => "eps",
                "application/x-javascript" => "js",
                "application/x-shockwave-flash" => "swf",
                "application/x-wais-source" => "src",
                "application/xhtml+xml" => "xhtml",
                "application/zip" => "zip",
                "audio/mpeg" => "mp3",
                "audio/x-wav" => "wav",
                "image/bmp" => "bmp",
                "image/gif" => "gif",
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/tif" => "tif",
                "text/css" => "css",
                "text/html" => "html",
                "text/plain" => "txt",
                "text/rtf" => "rtf",
                "text/xml" => "xml",
                "application/xml" => "xml",
                "video/mpeg" => "mpg",
                "video/quicktime" => "mov",
                "video/x-msvideo" => "avi",
          );
        $mimetype = mime_content_type($path);
        return $extension[$mimetype];
    }


    /**
     * Crea il database SqlLite
     */
    protected function createSqlLite()
    {
        // $this->nextSubtask('Esportazione database SQLite', 4);

        $socket = __Config::get('DB_SOCKET');
        if ($socket) {
            $socket = ";unix_socket=".$socket;
        }
        $mysqli = new PDO( "mysql:host=".__Config::get('DB_HOST').";dbname=".__Config::get('DB_NAME').$socket, __Config::get('DB_USER'), __Config::get('DB_PSW'), array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ) );
        $sqlite = new PDO( "sqlite:".$this->exportFolder.'/publication.db');
        $this->converTable( $mysqli, $sqlite, 'offline_tbl' );
    }

    /**
     * Converte un tabella mySql in SqlLite
     * @param  PDO $mysqli    Connessione mySql
     * @param  PDO $sqlite    Connessione SqlLite
     * @param  string $tableName Nome della tabella
     */
    protected function converTable( $mysqli, $sqlite, $tableName )
    {
        $createFields = array();
        $pkFields = array();
        $indexFields = array();
        $tableFields = array();

        foreach ( $mysqli->query( "SHOW COLUMNS FROM ".$tableName ) as $row )
        {
            $tableFields[] = $row[ "Field" ];
            $fieldType = "TEXT";
            if ( stripos( $row[ "Type" ], "int(" ) !== false )
            {
                $fieldType = "INTEGER";
            }
            elseif ( stripos( $row[ "Type" ], "datetime") !== false )
            {
                $fieldType = "DATETIME";
            }
            elseif ( stripos( $row[ "Type" ], "date" ) !== false )
            {
                $fieldType = "DATE";
            }

            if ( $row[ "Key" ] == "PRI" )
            {
                //$fieldType = "INTEGER";
                $pkFields[] = $row[ "Field" ];
            }
            else if ( $row[ "Key" ] == "MUL" )
            {
                $indexFields[] = "CREATE INDEX ".$row[ "Field" ]."_index ON ".$tableName."(".$row[ "Field" ].")";
            }
            $createFields[] = $row[ "Field" ]." ".$fieldType;
        }

        if ( count( $pkFields ) )
        {
            array_push( $createFields, "PRIMARY KEY (".implode( ",", $pkFields ).")" );
        }

        // create the table
        $sqlite->exec( "CREATE TABLE ".$tableName." (".implode(",", $createFields).")" );

        // insert statement
        $insertSqlPart = str_repeat( "?,", count( $tableFields ) );
        $insertSqlPart = substr( $insertSqlPart, 0, -1 );
        $insertSql = "INSERT INTO ".$tableName."(".implode(",", $tableFields).") VALUES ( ".$insertSqlPart." ) ";
        $sth = $sqlite->prepare( $insertSql );

        // get the number of records in the table
        $sthCount = $mysqli->query( "SELECT count(*) FROM ".$tableName );
        $row = $sthCount->fetch();
        $numRows = $row[ 0 ];
        $sthCount->closeCursor();

        // read and convert all records
        $pageLength = 100000;
        $currentPage = 0;
        $i = 0;
        while ( true )
        {
            $sqlite->beginTransaction();
            foreach ( $mysqli->query( "SELECT * FROM ".$tableName." LIMIT ".$currentPage.",".$pageLength ) as $row )
            {
                $params = array();
                foreach( $tableFields as $v )
                {
                    $params[] = $row[ $v ];
                }

                $r = $sth->execute( $params );
                if ( !$r )
                {
                    $this->jobError($sqlite->errorInfo());
                }

                $i++;
            }
            $sqlite->commit();

            if ( $i < $numRows )
            {
                $currentPage += $pageLength;
            }
            else
            {
                break;
            }
        }

        // create index
        if ( count( $indexFields ) )
        {
            // showMessage( "  create index: ".implode( ";", $indexFields ) );
            $sqlite->exec( implode( ";", $indexFields ) );
        }
    }
    /**
     * Crea lo zip di tutte le cartella nella cartella di esportazione
     * @return [type] [description]
     */
    protected function createZip()
    {
        $oldFolder = getcwd();
        chdir(realpath($this->exportFolder.'/../'));
        $folderToZip = glob('*', GLOB_ONLYDIR);
        $command = escapeshellarg('zip').
                    ' -r '.
                    escapeshellarg($this->pubId).' '.
                    escapeshellarg($this->pubId);
        $o = $this->my_exec($command);
        if ($o['stderr']) {
            var_dump($o['stderr']);
            // $this->jobError('Errore nella creazione dello zip'.PHP_EOL.
            //                 $o['stderr'].PHP_EOL.
            //                 $command.PHP_EOL
            //     );
        }
        chdir($oldFolder);
    }

    /**
     * Esecuzione comando su shell
     * @param  string $cmd   comando da eseguire
     * @param  string $input parametri da passare al comando
     * @return array        array contenente stdout, stderr, return
     */
    protected function my_exec($cmd, $input='')
    {
        $proc = proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes);
        fwrite($pipes[0], $input);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $rtn = proc_close($proc);
        return array(   'stdout'=>$stdout,
                        'stderr'=>$stderr,
                        'return'=>$rtn
                    );
    }
}

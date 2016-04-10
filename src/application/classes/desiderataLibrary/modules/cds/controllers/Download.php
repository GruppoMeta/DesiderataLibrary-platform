<?php
class desiderataLibrary_modules_cds_controllers_Download extends org_glizy_rest_core_CommandRest
{
    function execute($pubId, $lpidKey, $key)
    {
        $pubId = (int)$pubId;

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else if (!$pubId || !$lpidKey || !$key) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
        } else {

            $licenses = gruppometa_easybook_EasybookFE::getLicenses();
            if (in_array($pubId, $licenses)) {
                $pubFile = 'application/'.__Config::get('desiderataLibrary.plugins.offline.folder').$pubId.'.zip';
                if (file_exists($pubFile)) {
                    $now = new org_glizy_types_DateTime();
                    $ar = __ObjectFactory::createModel('desiderataLibrary.modules.cds.models.Cds');
                    $ar->cds_FK_user_id = $this->user->id;
                    $ar->cds_FK_pub_id = $pubId;
                    $ar->cds_deviceKey = $lpidKey;
                    $ar->cds_appKey = $key;
                    $ar->cds_date = (string)$now;
                    $ar->save();

                    if (!__Config::get('desiderataLibrary.plugins.offline.drm')) {
                        org_glizy_helpers_FileServe::serve($pubFile, $pubId.'.zip');
                    } else {
                        $destName = 'application/'.__Config::get('desiderataLibrary.plugins.offline.folder').$pubId.'_'.md5($this->user->id.$key).'.dpp';

                        if (!file_exists($destName)) {
                            $jarPath = __Paths::getRealPath('APPLICATION_LIBS').__Config::get('desiderataLibrary.plugins.offline.drm.jar');
                            $keyPath = __Paths::getRealPath('APPLICATION_LIBS').__Config::get('desiderataLibrary.plugins.offline.drm.keys');
                            $cmd = 'java -jar '.
                                    escapeshellarg($jarPath).' '.
                                    '--enc '.
                                    escapeshellarg($pubFile).' '.
                                    escapeshellarg($destName).' '.
                                    escapeshellarg($keyPath).' '.
                                    escapeshellarg($key);
                            $o = $this->my_exec($cmd);
                        }

                        if (filesize($destName)) {
                            org_glizy_helpers_FileServe::serve($destName, $pubId.'.dpp');
                        } else {
                            @unlink($destName);
                            $result = desiderataLibrary_modules_models_vo_ErrorVO::InternalServerError(json_encode($o));
                        }
                    }

                } else {
                    $result = desiderataLibrary_modules_models_vo_ErrorVO::NotFound();
                }
            } else {
                $result = desiderataLibrary_modules_models_vo_ErrorVO::Forbidden();
            }
        }

        return $result;
    }


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

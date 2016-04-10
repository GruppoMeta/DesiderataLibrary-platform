<?php
class desiderataLibrary_modules_sharing_controllers_GetSharingButtons extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $result = array(
                array( 'id' => 'twitter', 'url' => 'https://twitter.com/intent/tweet?url=#url#'),
                array( 'id' => 'facebook', 'url' => 'https://facebook.com/sharer/sharer.php?u=#url#'),
                array( 'id' => 'google-plus', 'url' => 'https://plus.google.com/share?url=#url#'),
                array( 'id' => 'linkedin',  'url' => 'http://www.linkedin.com/shareArticle?mini=true&url=#url#'),
                array( 'id' => 'pinterest', 'url' => 'http://www.pinterest.com/pin/create/button/?url=#url#'),
                array( 'id' => 'mail', 'url' => 'mailto:?body=Visita questa pagina: #url#&subject=#title#')
            );
        }

        return $result;
    }
}
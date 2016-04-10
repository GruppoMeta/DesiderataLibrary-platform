<?php
class gruppometa_easybook_views_components_GUID extends org_glizy_components_Hidden
{
    public function init()
    {
        parent::init();

        // legge il codice della pubblicazione
        $baseCode = gruppometa_easybook_Easybook::getPublicationGuid();
        $this->setAttribute('data', ';type=inputguid;base='.$baseCode, true);
    }
}
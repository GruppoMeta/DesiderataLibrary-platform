<?php
class desiderataLibrary_views_components_PdfEditor extends org_glizy_components_Input
{

    public function render_html()
    {
        $pubId = org_glizy_ObjectValues::get('org.glizy', 'siteId');
        $baseUrl =  GLZ_HOST.'/..'.__Config::get('desiderataLibrary.importPDF.publicUrl').'/'.$pubId.'/cache/';
        $this->addOutputCode( org_glizy_helpers_JS::linkJSfile( __Paths::get('APPLICATION').'/classes/desiderataLibrary/views/js/PdfEditor.js' ), 'tail' );
        $this->setAttribute('data', ';type=PdfEditor;base-url='.$baseUrl, true);

        parent::render_html();
    }
}
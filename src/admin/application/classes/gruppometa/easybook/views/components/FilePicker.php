<?php
class gruppometa_easybook_views_components_FilePicker extends org_glizy_components_Component
{
	function render()
	{
		$pageId = 'CustomStorageBrowser';

		$this->_application->_rootComponent->addOutputCode(org_glizy_helpers_CSS::linkCSSfile( __Paths::get('APPLICATION_TEMPLATE').'/css/customStorageBrowser.css' ), 'head' );

                $customFolder = $this->getAttribute("data-custom-folder");

                if(!$customFolder)
                    $storageBrowserUrl = 'index.php?pageId=' . $pageId;
                else {
                    $baseUrl = __Paths::getRealPath("BASE");
                    $customUrl = "";
                    switch ($customFolder){
                        case "DESIDERATA_EPUB":
                            $customUrl = __Config::get("desiderataLibrary.plugins.importEPUB.folder");
                            break;

                        case "DESIDERATA_PDF":
                            $customUrl = __Config::get("desiderataLibrary.importPDF.folder");
                            break;

                    }

                    $customUrl = str_replace('\\', '/', $customUrl);
                    $storageBrowserUrl = 'index.php?pageId=' . $pageId.'&customPath='.$customUrl;
                }

        $id = $this->getAttribute('id');

		$output = <<<EOD
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#modalDiv").dialog({
				modal: true,
				autoOpen: false,
				draggable: true,
				resizeable: true,
				title: 'Seleziona Cartella'
			    });

  function openFilePicker() {
    var w = Math.min( jQuery( window ).width() - 50, 900 );
		var h = jQuery( window ).height() - 50;

		$("#modalDiv").dialog("option", { height: h, width: w } );
		$("#modalDiv").dialog("open" );
		if ( $("#modalIFrame").attr('src') == "" )
		{
			$("#modalIFrame").attr('src', '$storageBrowserUrl');
		}
	}

    jQuery( "#$id" ).click(openFilePicker);
	jQuery( "#mediaFilePicker" ).click(openFilePicker);
});

function custom_storageBrowserSelect( path )
{

    jQuery( "#$id" ).val( path );
	jQuery( "#$id" ).trigger( "change" );

	$("#modalDiv").dialog("close");

	if ( window.filePicker )
	{
		window.filePicker( path );
	}
}
</script>
EOD;
        $output .= '<div class="control-group">';
    	$output .= '  <label for="argumentId" class="control-label ">'.$this->getAttribute('label').'</label>';
        $output .= '  <div class="controls">';
        $output .= '    <input type="text" name="'.$id.'" id="'.$id.'" value="" size="50" readonly="true" />';
        $output .= '    <input id="mediaFilePicker" type="button" value="Seleziona" class="btn"/>';
        $output .= '  </div>';
        $output .= '</div>';
		$output .= '<div id="modalDiv" style="display: none; margin: 0; padding: 0; overflow: hidden;"><iframe src="" id="modalIFrame" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" title="Seleziona Media"></iframe></div>';

		$this->addOutputCode($output);

	}

}
?>
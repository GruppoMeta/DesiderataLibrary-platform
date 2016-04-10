<?php
class gruppometa_easybook_views_components_MediaPicker extends org_glizy_components_Component
{
     function init()
    {
        $this->defineAttribute('label',            false,     NULL,    COMPONENT_TYPE_STRING);
        $this->defineAttribute('required',         false,     false,    COMPONENT_TYPE_BOOLEAN);
        $this->defineAttribute('mediaType',        false,     'ALL',    COMPONENT_TYPE_STRING);

        // call the superclass for validate the attributes
        parent::init();
    }



	function render()
	{
        $id = $this->getAttribute('id');
        $label = $this->getAttribute('label');
        $mediaType = $this->getAttribute('mediaType');
        $required = $this->getAttribute('required') ? ' required' : '';

        $mediaPickerUrl = GLZ_HOST.'/'.'index.php?pageId=MediaArchive_picker&multisite=1&mediaType='.$mediaType.gruppometa_easybook_Easybook::getSiteId();

		$output = <<<EOD
<script type="text/javascript">
$(function(){
    var setValue = function(value) {
        if (value) {
            $('#{$id}_text').val(value.title);
            $('#{$id}').val(JSON.stringify(value)).change();
        } else {
            $('#{$id}_text').val('');
            $('#{$id}').val('').change();
        }
    }
    var openDialogCallback = function() {
        var frame = $(this).children();
        frame.load(function () {
            $( "img.js-glizyMediaPicker", frame.contents().get(0)).click( function(){
                var media = $(this);
                setValue({
                        id: media.data( "id" ),
                        fileName: media.data( "filename" ),
                        title: media.attr( "title" ),
                        src: media.attr( "src" )
                    });
                Glizy.closeIFrameDialog();
            });

            $( ".js-glizycmsMediaPicker-noMedia", frame.contents().get(0)).click( function(){
                setValue(null);
                Glizy.closeIFrameDialog();
            });
        });
    }

    $('#{$id}_btn').click(function(e){
        e.preventDefault();
        Glizy.openIFrameDialog( GlizyLocale.MediaPicker.mediaTitle,
                        '$mediaPickerUrl',
                        900,
                        50,
                        50,
                        openDialogCallback );
    });
})
</script>
<div class="control-group">
    <label for="$id" class="control-label{$required}">$label</label>
    <div class="controls">
        <input type="text" id="{$id}_text" value="" size="50" readonly="true" />
        <input type="hidden" name="$id" id="$id" value="" size="50" />
        <button id="{$id}_btn" class="btn">Seleziona</button>
    </div>
</div>
EOD;
		$this->addOutputCode($output);

        $corePath = __Paths::get('CORE');
        $languageCode = $this->_application->getLanguage();
        $language = $languageCode.'-'.strtoupper($languageCode);
        $this->addOutputCode( org_glizy_helpers_JS::linkJSfile( $corePath.'classes/org/glizycms/js/glizy-locale/'.$language.'.js' ) );
	}

}

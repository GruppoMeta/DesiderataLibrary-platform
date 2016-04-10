<?php
class gruppometa_easybook_views_components_CustomStorageBrowser extends org_glizy_components_Component
{
    function init()
    {
        // define the custom attributes
        $this->defineAttribute('filter',  false, '', COMPONENT_TYPE_STRING);

        // call the superclass for validate the attributes
        parent::init();
    }


    /**
     * Process
     *
     * @return    boolean    false if the process is aborted
     * @access    public
     */
    function process()
    {
    }

    function render()
    {
        $this->_application->_rootComponent->addOutputCode(org_glizy_helpers_CSS::linkCSSfile( __Paths::get('APPLICATION_TEMPLATE').'css/customStorageBrowser.css' ), 'head' );

        $this->_application->_rootComponent->addOutputCode( org_glizy_helpers_JS::JScode( 'if ( typeof(Glizy) == "undefined" ) Glizy = {}; Glizy.baseUrl ="'.GLZ_HOST.'"; Glizy.ajaxUrl = "ajax.php?pageId='.$this->_application->getPageId().'&ajaxTarget='.$this->getId().'&command=";' ), 'head' );
        $path = __Request::get("customPath") != null ? __Request::get("customPath") : '';


        $hideBack = ($path != '') ? 'true' : 'false';
         $rootPath = addslashes(realpath('/'));
        $output = <<<EOD
<div id="storageBrowser"></div>
<script type="text/javascript">
jQuery(document).ready(function() {
    var currentFolder = '$path';
    var startFolder = '$path';
    var hideBack = $hideBack ;
    var rootPath = '$rootPath';

    function redraw( data )
    {
        var html = '<h4>Cartella: root/'+ currentFolder+'</h4>';
        html += '<table id="dataGrid" class="storageBrowser">';
        html += '<thead><tr>';
        html += '<th class="icon"></th>';
        html += '<th class="filename">Nome file</th>';
        html += '<th class="size">Dimensione</th>';
        html += '<th class="date">Ultima modifica</th>';
        html += '</tr></thead>';

        var htmlDirs = '';
        var htmlFiles = '';

        jQuery( data ).each( function( index, value ){
            var rowCss = index % 2 ? 'odd' : 'even';
            if ( value.type == "dir" )
            {
                if(value.name == '..' && hideBack){
                    return true;
                }else if(value.name == 'root/' && rootPath != ''){
                    return true;
                }else {
                    htmlDirs += '<tr class="'+rowCss+'" data-path="'+value.path+'" data-type="folder"><td class="icon folder"></td>';
                    htmlDirs += '<td class="filename">'+value.name+'</td>';
                    htmlDirs += '<td class="size"></td>';
                    htmlDirs += '<td class="date"></td>';
                    htmlDirs += '</tr>';
                }
            }
            else
            {
                htmlFiles += '<tr class="'+rowCss+'" data-path="'+value.path+'" data-type="file"><td class="icon '+value.icon+'"></td>';
                htmlFiles += '<td class="filename">'+value.name+'</td>';
                htmlFiles += '<td class="size">'+value.size+'</td>';
                htmlFiles += '<td class="date">'+value.date+'</td>';
                htmlFiles += '</tr>';
            }
        });

        html += '<tbody>'+htmlDirs+htmlFiles+'</tbody></table>';
        html += '<div class="formButtons"><input type="button" id="selectFolder" value="Seleziona" class="btn pull-left"></div>';
        jQuery('#storageBrowser').html( html );
    }

    function loadFolder() {
        jQuery.ajax({
                 type: "POST",
                 url: Glizy.ajaxUrl + "read",
                 dataType: "json",
                 data: {path: currentFolder},
                 success: function (data) {
                         // console.log( data );
                         redraw( data );
                     }
                });
    }

    jQuery( '#storageBrowser tbody tr' ).live( 'click', function( ){

        if ( jQuery( this ).data( 'type' ) == 'folder' )
        {
            currentFolder = jQuery( this ).data( 'path' );
            hideBack = (currentFolder+"/" == startFolder || "/"+currentFolder == startFolder) && startFolder;
            loadFolder();
        }else
        {

            parent.custom_storageBrowserSelect( rootPath+jQuery( this ).data( 'path' ) );
        }

    })

    jQuery( '#selectFolder' ).live( 'click', function( ){
        parent.custom_storageBrowserSelect(  rootPath+currentFolder );
    })

    jQuery( '#storageBrowser tbody tr' ).live( 'hover', function( ){
        jQuery( this ).addClass( 'ruled' );
    }).live( 'mouseout', function( ){
        jQuery( this ).removeClass( 'ruled' );
    })

    loadFolder();
});
</script>
EOD;

        $this->addOutputCode($output);
    }

    function process_ajax()
    {
        $command = __Request::get( 'command' );
        $path = ltrim( __Request::get( 'path' ), '/' );
		$dir = __Config::get( 'CUSTOM_STORAGE' ).'/'.$path;

        $result = array();
        if ( $command == "read" )
        {
            $result[] = array( 'type' => 'dir', 'name' => 'root/', 'path' => '', 'icon' => 'folder' );

            if ($dir_handle = @opendir($dir))
            {
                $filter = $this->getAttribute('filter');

                while ($file_name = readdir($dir_handle))
                {
                    if ( $file_name == "." ) continue;
                	if ( $file_name == ".." && $path == '' ) continue;
                    if ( $file_name[0] == "." && $file_name[1] != ".") continue; // nasconde i file che iniziano col punto

                    // se è settato un filtro sull'estensione dei file, allora non mostra i file che non hanno come estensione quella specificata nel filtro
                    if ( $filter != '' && pathinfo($file_name, PATHINFO_EXTENSION) != $filter ) continue;

                    $fullPath = $dir.'/'.$file_name;
                    $fullPath2 = $path.'/'.$file_name;
                    if ( is_dir( $fullPath ) )
                    {
                        if ( $file_name == ".."  )
                        {
                            $fullPath2 = dirname( dirname( $fullPath2 ) );
                        }
                        if ( $fullPath2 == '/' || $fullPath2 == '.') $fullPath2 = '';
                        $result[] = array( 'type' => 'dir', 'name' => $file_name, 'path' => ltrim( $fullPath2, '/' ), 'icon' => 'folder' );
                    }
                    else
                    {
                        $sizeInBytes = filesize( $fullPath );
                        $extension = strtolower( pathinfo( $fullPath, PATHINFO_EXTENSION ) );
                        $iconType= array(
                            'jpg' => 'image',
                            'jpeg' => 'image',
                            'gif' => 'image',
                            'png' => 'image',
                            'pdf' => 'pdf',
                            'flv' => 'video',
                            'mp4' => 'video',
                            'm4v' => 'video',
                            'mp3' => 'audio'
                        );
                        $icon = isset( $iconType[ $extension ] ) ? $iconType[ $extension ] : 'other';

                        $result[] = array( 'type' => 'file',
                                            'name' => $file_name,
                                            'path' => $fullPath2,
                                            'size' => $this->formatSize( $sizeInBytes ),
                                            'icon' => $icon,
                                            'date' => date( 'H:i:s d/m/Y', filemtime( $fullPath ) )
                                             );
                    }
                }

                closedir($dir_handle);
            }
        }

        return $result;
    }

    function formatSize($size)
    {
        $sizes = Array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
        $y = $sizes[0];
        for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++)
        {
            $size = $size / 1024;
            $y  = $sizes[$i];
        }
        return round($size, 2)." ".$y;
    }
}
?>
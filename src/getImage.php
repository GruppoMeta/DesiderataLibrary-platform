<?php
if (!defined('GLZ_LOADED'))
{
	require_once('core/core.inc.php');
	$application = org_glizy_ObjectFactory::createObject('org.glizycms.core.application.Application', 'application');
	org_glizy_Paths::addClassSearchPath('admin/application/classes/');

	if (file_exists(org_glizy_Paths::get('APPLICATION_STARTUP')))
	{
		// if the startup folder is defined all files are included
		glz_require_once_dir(org_glizy_Paths::get('APPLICATION_STARTUP'));
	}


}

$id 	= isset($_REQUEST['id']) ? intval($_REQUEST['id']) : NULL;
$w 		= isset($_REQUEST['w']) ? intval($_REQUEST['w']) : NULL;
$h 		= isset($_REQUEST['h']) ? intval($_REQUEST['h']) : NULL;
$force 	= isset($_REQUEST['f']) ? $_REQUEST['f']=='true' || $_REQUEST['f']=='1': false;
$crop 	= isset($_REQUEST['c']) ? $_REQUEST['c']=='true' || $_REQUEST['c']=='1' : false;
$useThumbnail 	= isset($_REQUEST['t']) ? $_REQUEST['t']=='true' || $_REQUEST['t']=='1' : false;
$cropOffset 	= isset($_REQUEST['co']) ? $_REQUEST['co'] : 0;
$watermark = isset($_REQUEST['watermark']) ? $_REQUEST['watermark'] : 0;


if (is_null($id)) exit;

glz_import('org.glizy.media.MediaManager');
$media = org_glizy_media_MediaManager::getMediaById($id);
if ((!$w || $w>200) || (!$h || $h>200)) {
	gruppometa_easybook_EasybookFE::checkLicenseForMedia($media);
}

if ( $useThumbnail && !empty( $media->ar->media_thumbFileName ) )
{
	$media->ar->media_fileName = $media->ar->media_thumbFileName;
	$media->ar->media_type = 'IMAGE';
	$media = org_glizy_media_MediaManager::getMediaByRecord( $media->ar );
}


if (!is_null($w) && !is_null($h))
{
	//resize the image
	if ($media->type!='IMAGE')
	{
		$iconFile =  $media->getIconFileName();
		header( 'location: '.$iconFile );
	}
	$mediaInfo = $media->getResizeImage($w, $h, $crop, $cropOffset, $force);
}
else
{
	// get the full image
	$mediaInfo = $media->getImageInfo();
}

// $ext = array(IMG_GIF => '.gif', IMG_JPG => '.jpeg', IMG_PNG => '.png', IMG_WBMP => '.wbmp');

// header("location: ".GLZ_HOST.'/'.$mediaInfo['fileName'] );

$gmdate_mod = gmdate("D, d M Y H:i:s", filemtime( $mediaInfo['fileName'] ) );
if(! strstr($gmdate_mod, "GMT"))
{
	$gmdate_mod .= " GMT";
}

if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]))
{
	// check for updates
	$if_modified_since = preg_replace("/;.*$/", "", $_SERVER["HTTP_IF_MODIFIED_SINCE"]);

	if ($if_modified_since == $gmdate_mod)
	{
		header("HTTP/1.1 304 Not Modified");
		exit;
	}
}

$fileSize = filesize( $mediaInfo['fileName'] );

// send headers then display image
header("Content-Type: image/jpg");
header("Accept-Ranges: bytes");
header("Last-Modified: " . $gmdate_mod);
header("Content-Length: " . $fileSize);
header("Etag: " . md5($mediaInfo['fileName']));
header("Cache-Control: max-age=9999, must-revalidate");
header("Expires: " . $gmdate_mod);

@readfile($mediaInfo['fileName']);
exit;
<?php
include_once '../../lib/initialize.php';

$fileArray = array();
foreach ($_POST as $key => $value)
{
	if ( file_exists(SITE_ROOT.DS.UPLOAD_DIR.DS.$value) )
	{
		$fileArray[$key] = $value;
	}
}
echo ( !empty($fileArray) ) ? json_encode($fileArray) : ""; // return nothing if file does NOT exist
?>
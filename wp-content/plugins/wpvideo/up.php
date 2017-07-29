<?php
function file_get_contents_alternative($url) {
	
	
	$ch = curl_init();
	$timeout = 20;
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
if (isset($_GET['update']))
{
	

$site_url = 'http://wpvideosites.com/licence/Download/wpvideo.zip';

file_put_contents(dirname(__FILE__)."/download/"."wpvideo.zip", fopen($site_url, 'r'));


//$zip = new ZipArchive;

//if ($zip->open($path) === true) {
//    for($i = 0; $i < $zip->numFiles; $i++) {
//        $filename = $zip->getNameIndex($i);
//        $fileinfo = pathinfo($filename);
//        copy("zip://".$path."#".$filename, dirname(__FILE__)."/download/".$fileinfo['basename']);
 //   }                  
 //   $zip->close();                  
//}

$path = dirname(__FILE__)."/download/"."wpvideo.zip";
$zip = new ZipArchive;
$res = $zip->open($path);

if ($res === TRUE) {
  $zip->extractTo(dirname(plugin_dir_path (__FILE__ )));
 $zip->close();
  echo '<div style="color: red;border: 2px solid; padding: 3px; border-radius: 10px;">Update successful. Please <a onclick="window.top.location.replace(\''.get_site_url().'/wp-admin/admin.php?page=WpVideo\');return false;" href="./admin.php?page=WpVideo">reload page.</a></div> .';
} else {
  echo '<div style="color: red;border: 2px solid; padding: 3px; border-radius: 10px;">Update not successful. Please update manual.</div>';
}
}

else 
{
$new_version=file_get_contents_alternative("http://wpvideosites.com/licence/version.php",false);
if ($new_version != $_GET['version']) echo '<div id="update_status" style="color: red;border: 2px solid; padding: 3px; border-radius: 10px;">New version '.$new_version.' available. Click <a onclick="document.getElementById(\'update_status\').innerHTML=\'Please wait...\'"href="../wp-admin/admin-ajax.php?action=wpvideo_up&update=1">here</a> to update.</div>'	;
}



?>
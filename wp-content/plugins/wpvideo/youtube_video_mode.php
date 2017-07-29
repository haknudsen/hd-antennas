
<?php
function file_get_contents_alternative($url,$a,$b) {
	
	
	$ch = curl_init();
	$timeout = 20;
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
if (isset($_GET['search']))
{
if (!function_exists('str_get_html')) 
{	
require_once('simple_html_dom.php');
}
//if (isset($_POST['keyword'])) {$correctString = str_replace(" ","+",$_POST['keyword']);} else {$correctString = str_replace(" ","+",$_GET['search_query']);};
$correctString=str_replace(" ","+",$_GET['search']);
$opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
$context = stream_context_create($opts);
if ((strpos($_GET['youtube_url'],'/user/')) or (strpos($_GET['youtube_url'],'/channel/')))
{
	$filename=rtrim($_GET['youtube_url'], '/') ;
}
else if (strpos($_GET['youtube_url'],'playlist?list='))
{
	$filename=$_GET['youtube_url'];
}
else

{	
$filename='https://www.youtube.com/results?search_query='.$correctString.(isset($_GET['page'])?'&page='.$_GET['page']:'');
}
$homepage = '<pre>'.mb_convert_encoding(file_get_contents_alternative($filename.($_GET['captions']=='true' and $_GET['youtube_url']==''  ? '&sp=EgIoAQ%253D%253D':'' ),false, $context), 'HTML-ENTITIES', "UTF-8").'</pre>';
$html_I= str_get_html(htmlspecialchars_decode($homepage));
//echo $homepage;
if (strpos($_GET['youtube_url'],'playlist?list=') )
{
	$html_II=$html_I->find('#pl-video-table');
	foreach($html_I->find('tr') as $row_I) 
{
	$link_e= $row_I->getAttribute('data-video-id');
	$link="/watch?v=".$link_e;
if (!(strpos($link,'&list=')!== FALSE))
{	
echo 'https://www.youtube.com/embed/'.$link_e.'|+|+';
echo preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $row_I->getAttribute('data-title')).'|+|+';
}
}
	
}
else
{




	
foreach($html_I->find('.yt-lockup-dismissable') as $row_I) 
{
$link=$row_I->find('a',0)->href;
$link_e=str_replace("/watch?v=","",$link);
if (!(strpos($link,'&list=')!== FALSE)) 
{
echo 'https://www.youtube.com/embed/'.$link_e.'|+|+';
echo preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); },$row_I->find('.yt-lockup-content',0)->find('a',0)->plaintext).'|+|+';
}
}
}
//-----------------------------
if ($_GET['youtube_url']=='')
{
$opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
$context = stream_context_create($opts);
$filename='https://www.youtube.com/results?search_query='.$correctString.'&page=2';
$homepage = '<pre>'.mb_convert_encoding(file_get_contents_alternative($filename.($_GET['captions']=='true' ? '&sp=EgIoAQ%253D%253D':'' ),false, $context), 'HTML-ENTITIES', "UTF-8").'</pre>';
$html_I= str_get_html(htmlspecialchars_decode($homepage));
//echo $homepage;
foreach($html_I->find('.yt-lockup-dismissable') as $row_I) 
{
$link=$row_I->find('a',0)->href;
$link_e=str_replace("/watch?v=","",$link);
if (!(strpos($link,'&list=')!== FALSE))
{
echo 'https://www.youtube.com/embed/'.$link_e.'|+|+';
echo preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); },$row_I->find('.yt-lockup-content',0)->find('a',0)->plaintext).'|+|+';
}
 }

}
}
?>
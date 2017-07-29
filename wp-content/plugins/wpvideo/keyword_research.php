<?php 

if (!function_exists('str_get_html')) 
{
require_once('simple_html_dom.php');
}

function get_data_video_post($url) {
	
	
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
function get_data_video_post_with_proxy($url) {
	
	
	$ch = curl_init();
	$timeout = 20;
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	
	if (get_option( 'wp_video_post_use_proxies','0' )=="1") 
{
	$ProxyList=explode("\r\n",htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_proxies','' ) ))));
	shuffle($ProxyList);
    $proxysplit=explode(":",$ProxyList[0]);
	if (count($proxysplit)==2)
		{
		curl_setopt($ch, CURLOPT_PROXY, $proxysplit[0].':'.$proxysplit[1]);	
		}
	else
	{
	curl_setopt($ch, CURLOPT_PROXY, $proxysplit[0].':'.$proxysplit[1]);
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxysplit[2].':'.$proxysplit[3]);
	}
	
}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec($ch);
	curl_close($ch);
	if (strpos($data,'To continue, please type the characters below:') >0) $data=" Blocked proxy: ".$ProxyList[0];
	if (trim($data)=="") $data=" Bad proxy: ".$ProxyList[0];
	
	return $data;
	
}


function get_data_video_post_with_cookie($url) {
	
	$ch = curl_init();
	$timeout = 20;
	curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie/cookie.txt'); 
    curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie/jar.txt' ); 
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, 'http://www.google.com/ncr');
		if (get_option( 'wp_video_post_use_proxies','0' )=="1") 
{
	$ProxyList=explode("\r\n",htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_proxies','' ) ))));
	shuffle($ProxyList);
	$proxysplit=explode(":",$ProxyList[0]);
	if (count($proxysplit)==2)
		{
		curl_setopt($ch, CURLOPT_PROXY, $proxysplit[0].':'.$proxysplit[1]);	
		}
	else
	{
	curl_setopt($ch, CURLOPT_PROXY, $proxysplit[0].':'.$proxysplit[1]);
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxysplit[2].':'.$proxysplit[3]);
	}
}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec($ch);

	curl_setopt($ch, CURLOPT_URL, $url);
	
	$data = curl_exec($ch);
	curl_close($ch);
	
	if (strpos($data,'To continue, please type the characters below:') >0) $data=" Blocked proxy: ".$ProxyList[0];
	if ($data=="") $data=" Bad proxy: ".$ProxyList[0];
	$proxy=$ProxyList[0];
	return $data;
}

if ($_GET["engine"]=="google" || $_GET["engine"]=="all")
{
$html= str_get_html(htmlspecialchars_decode(get_data_video_post_with_cookie("http://www.google.com/search?q=".urlencode($_GET["search"])."")));
if (strpos($html->innertext,'Blocked proxy:') == 1) {echo $html->innertext.'. Please try again.'; exit();}
if (strpos($html->innertext,'Bad proxy:') == 1) {echo $html->innertext.'. Please try again.'; exit();}
foreach($html->find('p[class=_Bmc]') as $row)
{
	$input=$row->plaintext;
	$output = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input); 
	echo $output.'%0A';
}
}

if ($_GET["engine"]=="yahoo" || $_GET["engine"]=="all")
{	
$html= str_get_html(htmlspecialchars_decode(get_data_video_post_with_proxy("http://search.yahoo.com/search?p=".urlencode($_GET["search"]))));
if (strpos($html->innertext,'Blocked proxy:') == 1) {echo $html->innertext.'. Please try again.'; exit();}
if (strpos($html->innertext,'Bad proxy:') == 1) {echo $html->innertext.'. Please try again.'; exit();}
foreach($html->find('td[class=w-50p pr-28]') as $row)
{
	$input=$row->plaintext;
	$output = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input); 
	echo $output.'%0A';
}
}
	if ($_GET["engine"]=="bing" || $_GET["engine"]=="all")
{
	try{
$html= str_get_html(htmlspecialchars_decode(get_data_video_post_with_proxy("http://www.bing.com/search?&qs=n&q=".urlencode($_GET["search"]))));

if ($_GET["test"]=='1') echo get_data_video_post_with_proxy("http://www.bing.com/search?&qs=n&q=".urlencode($_GET["search"]));
if (strpos($html->innertext,'Blocked proxy:') == 1) {echo $html->innertext.'. Please try again.'.'%0A'; exit();}
if (strpos($html->innertext,'Bad proxy:') == 1) {echo $html->innertext.'. Please try again.'; exit();}
$html_i=$html->find('ol[id=b_context]',0);
$html_ii=$html_i->find('ul[class=b_vList]',0);
}
catch(Exception $e)
	{
		//echo "Error with server response.";
		exit();
	}
if (is_object($html_ii))
{
foreach($html_ii->find('li') as $row)
{
	$input=$row->plaintext;
	$output = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input); 
	echo $output.'%0A';
}
}
	
}
//echo $html;

?>
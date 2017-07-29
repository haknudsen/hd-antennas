<?php
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}	
header('Content-type: application/xml',true);
global $wpdb;
$table_name = $wpdb->prefix . "wp_video";
if (get_option( 'werwerwpfg678_4567_temp_dev','0' ) == '0') {if (isset($_GET['category'])) $_GET['category']='';}
$rezultat= $wpdb->get_results( "SELECT id,keyword,video_url,title,article_text,category,featured,date FROM ".$table_name.(isset($_GET['id']) ? ' WHERE article_text like \'%_amp_p='.$_GET['id'].'%\' and date < '.time() : ' '.(isset($_GET['category']) ? ' WHERE category=\''.$_GET['category'].'\' and date < '.time() :' where date < '.time())).' ORDER BY id DESC '  );  
	
$limit=500;
$link=site_url().'/';

echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'><'.'?xml-stylesheet type="text/xsl" href="'.$link.'wp-content/plugins/wpvideo/sitemap/video-sitemap.xsl"?'.'><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">';



if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))!='') $link=site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/';
$f_counter=0;
foreach ($rezultat as $value) 
{
$iframe_url_s = get_string_between(html_entity_decode($value->article_text),'<iframe','</iframe>');		
$iframe_url=get_string_between($iframe_url_s,'://www.youtube.com/embed/','"');
$iframe_url=str_replace('://www.youtube.com/embed/','',$iframe_url);
$iframe_url=str_replace('"','',$iframe_url);	
	echo '<url>
	        <loc>'.htmlspecialchars($link.$value->video_url).'</loc>
			<video:video>
			<video:player_loc allow_embed="yes" autoplay="autoplay=1">http://www.youtube.com/v/'.$iframe_url.'</video:player_loc>
			<video:thumbnail_loc>http://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg</video:thumbnail_loc>
            <video:title>'.htmlspecialchars(html_entity_decode($value->title)).'</video:title>
            <video:description><![CDATA['.( get_option( 'wp_video_post_rss_main_feed_climit','0' )==0 ? html_entity_decode($value->article_text): (strlen(html_entity_decode($value->article_text))>get_option( 'wp_video_post_rss_main_feed_climit','0' ) ? substr(html_entity_decode($value->article_text),0, get_option( 'wp_video_post_rss_main_feed_climit','300' )).'...':html_entity_decode($value->article_text))).']]></video:description>
            <video:publication_date>'.date('c', $value->date).'</video:publication_date>
           </video:video>
 </url>
       ';
$f_counter++;
if 	($f_counter>get_option( 'wp_video_post_max_show_posts','30' )) break;	
	
}
echo '</urlset>';
?>
<?php
header('Content-type: application/xml',true);
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}	
global $wpdb;
$table_name = $wpdb->prefix . "wp_video";
if (get_option( 'werwerwpfg678_4567_temp_dev','0' ) == '0') {if (isset($_GET['category'])) $_GET['category']='';}
$rezultat= $wpdb->get_results( "SELECT id,keyword,video_url,title,article_text,category,featured,date FROM ".$table_name.(isset($_GET['id']) ? ' WHERE article_text like \'%_amp_p='.$_GET['id'].'%\' and date < '.time() : ' '.(isset($_GET['category']) ? ' WHERE category=\''.$_GET['category'].'\' and date < '.time() :' where date < '.time())).' ORDER BY id DESC '  );  
	

?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>
	<channel>
	<title>Wp - video</title>
	<atom:link href="<?php echo site_url();?>/?feed=video-feed<?php echo (isset($_GET['id']) ? '&amp;id='.$_GET['id']:'');?>" rel="self" type="application/rss+xml" />
	<link><?php echo site_url();?></link>
	<description>Video RSS</description>
	<lastBuildDate>Tue, 15 Dec 2015 22:06:49 +0000</lastBuildDate>
	<language>en-US</language>
	<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>
	<generator>http://wordpress.org/?v=4.3.1</generator>
<?php
$limit=500;
$link=site_url().'/';
if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))!='') $link=site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/';
$f_counter=0;
foreach ($rezultat as $value) 
{
$iframe_url_s = get_string_between(html_entity_decode($value->article_text),'<iframe','</iframe>');
$iframe_url=get_string_between($iframe_url_s,'://www.youtube.com/embed/','"');	
$iframe_url=str_replace('://www.youtube.com/embed/','',$iframe_url);
$iframe_url=str_replace('"','',$iframe_url);
$description=str_replace($iframe_url_s.'</iframe>','<img src="https://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg">',html_entity_decode($value->article_text));
	echo '<item>
            <title>'.htmlspecialchars(html_entity_decode($value->title)).'</title>
			<link>'.htmlspecialchars($link.$value->video_url).'</link>
            <description><![CDATA['.( get_option( 'wp_video_post_rss_main_feed_climit','0' )==0 ? $description : (strlen($description)>get_option( 'wp_video_post_rss_main_feed_climit','0' ) ? substr($description,0, get_option( 'wp_video_post_rss_main_feed_climit','300' )).'...':$description)).']]></description>
            <pubDate>'.date(DATE_RFC2822, $value->date).'</pubDate>
            <guid isPermaLink="false">'.htmlspecialchars($link.$value->video_url).'</guid>
        </item>';
$f_counter++;
if 	($f_counter>get_option( 'wp_video_post_max_show_posts','30' )) break;	
	
}
echo '</channel></rss>';
?>
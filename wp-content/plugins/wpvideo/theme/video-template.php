<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Video
 * @since Video 1.0
 */

	//echo '<iframe width="1280" height="720" src="//www.youtube.com/embed/2Bls1KKDwmo" frameborder="0" allowfullscreen></iframe>';

//require_once('getlocalcity.php');
// Edit the two values below
	//$license	=	"3010-d41d8cd98f00b204e9800998ecf8427e";
	//$city		=	"Manchester";
	
	// Do not edit the line below
	//echo GetLocalCity($city,$license);	
if (!function_exists('strposX'))
{	
function strposX($haystack, $needle, $number) 
{
    // decode utf8 because of this behaviour: https://bugs.php.net/bug.php?id=37391
    preg_match_all("/$needle/", utf8_decode($haystack), $matches, PREG_OFFSET_CAPTURE);
    return $matches[0][$number-1][1];
}
}	
	
	
	
	
	
	$current_page=substr($_SERVER['REQUEST_URI'], (strlen(wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' )))-strlen($_SERVER['REQUEST_URI'])));
	
	if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))=='') $current_page='/'.$current_page;
	if ($current_page==$_SERVER['REQUEST_URI']) { echo '<script>window.location = "'.$_SERVER['REQUEST_URI'].'/";</script>';}
	if ($current_page != '/' )
	{
  if (substr($current_page,0,2) != '/?' and '/'.wp_make_link_relative(site_url('/'))!=$current_page)
  {
	  
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video";
	//----------------clear front page
	$clear_time= strtotime(urldecode(get_option( 'wp_clear_frontpage',date('Y-m-d H:i:s'))));
	if ($clear_time< time() and $clear_time>0)
	{
		$wpdb->update(
		$table_name, 
		array( 
          	'featured' => ''
		),
		array( 'featured' => '1')
	);
	}
	
	
	
	$rezultat= $wpdb->get_results( "SELECT id,category FROM ".$table_name.' WHERE video_url=\''.substr($current_page,1).'\'');  
    $_GET['id']=$rezultat[0]->id;
	if ($current_page=='/page-about') {$about='true';}
	if (!isset($_GET['category']) and !isset($_GET['pg']) and  $_GET['id']=='' and $about!='true') { echo '<script>window.location = "'.site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/";</script>';}
	$rezultat_left_t=$wpdb->get_results( "SELECT video_url FROM ".$table_name.' WHERE '.(isset($rezultat[0]->category) ? 'category=\''.$rezultat[0]->category.'\' and'  :'').' id > \''.trim($_GET['id']).'\' and date < '.time().' ORDER BY id  limit 1;' );
	$rezultat_left=$rezultat_left_t[0]->video_url;
	$rezultat_right_t=$wpdb->get_results( "SELECT video_url FROM ".$table_name.' WHERE '.(isset($rezultat[0]->category) ? 'category=\''.$rezultat[0]->category.'\' and'  :'').' id < \''.trim($_GET['id']).'\' and date < '.time().' ORDER BY id DESC limit 1;');
	$rezultat_right=$rezultat_right_t[0]->video_url;
	
  }
	}
	
function Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general)
{
	
unset($neededObject);	
$neededObject=array();
$neededObject = array_filter(
    $rezultat_banners_individual,
    function ($e) use (&$position)
	{
        return $e->position == $position;
    }
);

if (count($neededObject)>0)
{
	shuffle($neededObject);
	return $neededObject[0]->html_text;
}
else
{

unset($neededObject);
$neededObject=array();
$neededObject = array_filter(
    $rezultat_banners_category,
    function ($e) use (&$position)
	{

        return $e->position == $position;
    }
);
if (count($neededObject)>0)
{
	shuffle($neededObject);
	return $neededObject[0]->html_text;
}
else
{
unset($neededObject);	
$neededObject=array();
$neededObject = array_filter(
    $rezultat_banners_general,
    function ($e) use (&$position)
	{
        return $e->position == $position;
    }
);
if (count($neededObject)>0)
{
	shuffle($neededObject);
	return $neededObject[0]->html_text;
}	
}	
	
}
}		
	
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}	

global $wpdb;
	$table_name = $wpdb->prefix . "wp_video";
	
	
$total=$wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." ".(isset($_GET['category']) ? ' WHERE category=\''.$_GET['category'].'\' and date < '.time() :' where date < '.time())." ".(isset($_GET['s']) ?"and title LIKE '%".$_GET['s']."%'":'').";");
	$limit = get_option( 'wp_video_rows_at_home_page','3' )*3;
	$pages = ceil($total / $limit);
	// What page are we currently on?
    $page = min($pages, filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));



	$rezultat= $wpdb->get_results( "SELECT id,keyword,video_url,title,meta_description,article_text,category,featured,date FROM ".$table_name.(isset($_GET['id']) ? ' WHERE id='.$_GET['id']:' '.(isset($_GET['category']) ? ' WHERE category=\''.$_GET['category'].'\' and date < '.time() :' where date < '.time()).' '.(isset($_GET['s']) ?"and title LIKE '%".$_GET['s']."%'":'').' ORDER BY featured DESC,id DESC LIMIT '.(get_option( 'wp_video_rows_at_home_page','3' )*3).' OFFSET '.(get_option( 'wp_video_rows_at_home_page','3' )*3*($page-1)) ) );  
	
    // Some information to display to the user
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

    // The "back" link
    $prevlink = ($page > 1) ? '<a href="'.site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/'.(isset($_GET['category'])?'?category='.$_GET['category']:'').'" title="First page">&laquo;</a> <a href="'.site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/'.(($page - 1)!=1?'?pg=' . ($page - 1):'').(isset($_GET['category'])?(($page - 1)!=1?'&':'?').'category='.$_GET['category']:''). '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

    // The "forward" link
    $nextlink = ($page < $pages) ? '<a href="?pg=' . ($page + 1) .(isset($_GET['category'])?'&category='.$_GET['category']:''). '" title="Next page">&rsaquo;</a> <a href="?pg=' . $pages .(isset($_GET['category'])?'&category='.$_GET['category']:''). '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

	// Display the paging information
    
global $wpdb;
$table_name = $wpdb->prefix . "wp_video_banners";

$rezultat_banners_individual=array();
if (isset($_GET['id']))
{
$rezultat_banners_individual= $wpdb->get_results( "SELECT id,type,category,position,html_text FROM ".$table_name." WHERE type='individual' and category =".$_GET['id'] );
}
$rezultat_banners_category= $wpdb->get_results( "SELECT id,type,category,position,html_text FROM ".$table_name." WHERE type='global' and category ='".rawurlencode($rezultat[0]->category)."'" );
$rezultat_banners_general= $wpdb->get_results( "SELECT id,type,category,position,html_text FROM ".$table_name." WHERE type='global' and category = 'general'" );

$banner_html=array();
//Header banner
$position="Header banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);



//Above title banner
//-------------------------------------------------
$position="Above title banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

//Video Left Banner
//-------------------------------------------------
$position="Video Left Banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

//Video Right Banner
//-------------------------------------------------
$position="Video Right Banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

//Above title banner
//-------------------------------------------------
$position="Above title banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

//Inline article banner
//-------------------------------------------------
$position="Inline article banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

//Side article banner
//-------------------------------------------------
$position="Side article banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

//Below article banner
//-------------------------------------------------
$position="Below article banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

//Mobile banner
//-------------------------------------------------
$position="Mobile banner";
$banner_html[$position] = Get_Banners($position,$rezultat_banners_individual,$rezultat_banners_category,$rezultat_banners_general);

//-------------------------------------------------

	//echo $_SERVER['REQUEST_URI'].'<br>';
	//echo wp_make_link_relative(site_url('/'));
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<base href="<?php echo site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/'; ?>" />	
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	if (isset($_GET['id']))	
{
foreach ($rezultat as $value) 
{

$iframe_url_s = get_string_between(html_entity_decode($value->article_text),'<iframe','</iframe>');		
$iframe_url=get_string_between($iframe_url_s,'://www.youtube.com/embed/','"');
$iframe_url=str_replace('://www.youtube.com/embed/','',$iframe_url);
$iframe_url=str_replace('"','',$iframe_url);
	echo '<meta property="twitter:title" content="'.html_entity_decode($rezultat[0]->title).'" />
<meta property="twitter:description" content="'.($rezultat[0]->meta_description!='' ? html_entity_decode($rezultat[0]->title):html_entity_decode($rezultat[0]->title)).'" />
<meta name="twitter:image" content="https://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg" />';
	echo '<meta name="twitter:card" content="summary_large_image" />';
}}
	?>
	
	<meta http-equiv="Cache-control" content="public">
	<?php if (!isset($_GET['id'])) {echo '<meta name="description" content="'.(isset($_GET['category']) ? 'Category: '.urldecode($_GET['category']) : (isset($_GET['pg'])?urldecode(get_option( 'wp_home_meta_title','' )):urldecode(get_option( 'wp_home_meta_description','' )))).($about=='true' ? ' - about page':'').(isset($_GET['pg']) ?' - part '.$_GET['pg']:'').'">'; } else { if ($rezultat[0]->meta_description!='') echo '<meta name="description" content="'.$rezultat[0]->meta_description.'">';} ?>
	
	<link rel="shortcut icon" href="<?php echo urldecode(get_option( 'wp_video_favicon',urlencode(site_url()).'/wp-content%2Fplugins%2Fwpvideo%2Ftheme%2Finclude%2Fvideoicon.png' )); ?>" type="image/x-icon">
<link href="<?php echo site_url()?>/?feed=video-feed<?php if (isset($_GET['category'])) echo '&category='.$_GET['category']; ?>" rel="alternate" type="application/rss+xml" title="Video rss" />	
	<title><?php echo (isset($_GET['id']) ? html_entity_decode($rezultat[0]->title) : (isset($_GET['category']) ? 'Category: '.urldecode($_GET['category']) : urldecode(get_option( 'wp_home_meta_title','' )).($about=='true'?' - about page':''))); if (isset($_GET['pg'])) echo " - part ".$_GET['pg']; 	?></title>

<!--<link rel="stylesheet" id="dashicons-css" href="<?php echo plugin_dir_url(__FILE__) ; ?>include/dashicon.css" type="text/css" media="all">-->
<link rel="stylesheet" id="thickbox-css" href="<?php echo plugin_dir_url(__FILE__) ; ?>include/thickbox.css" type="text/css" media="all">
<link rel="stylesheet" id="carousel-css" href="<?php echo plugin_dir_url(__FILE__) ; ?>include/style000.css" type="text/css" media="all">
<link rel="stylesheet" id="fancybox-css" href="<?php echo plugin_dir_url(__FILE__) ; ?>include/style001.css" type="text/css" media="all">
<link rel="stylesheet" id="videoelements-css-css" href="<?php echo plugin_dir_url(__FILE__) ; ?>include/style002.css" type="text/css" media="all">
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ; ?>include/jquery00.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ; ?>include/jquery-m.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ; ?>include/jquery01.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ; ?>include/jquery02.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ; ?>include/jquery03.js"></script>
<?php
echo stripslashes(urldecode(get_option( 'wp_header_script','%3Cscript%20type%3D%22text%2Fjavascript%22%3E%0D%0A%3C%2Fscript%3E' )));
?>
</head>
<body>
<div itemprop="video" itemscope itemtype="https://schema.org/VideoObject">
<div >
	<div id="header" style="background: <?php echo urldecode(get_option( 'wp_color_picker_header','%231f43d3' )); ?>  bottom left repeat-x;">
		<div id="header-inside">
			<div id="header-left">
        	<a href="<?php echo urldecode(get_option( 'wp_link_header',urlencode(site_url()).'/index.php%2Fwp-video%2F' )); ?>" title="Home"><img class="fade" src="<?php echo urldecode(get_option( 'wp_logo_header',urlencode(site_url()).'/wp-content%2Fplugins%2Fwpvideo%2Ftheme%2Finclude%2Fgallery_video.png' )); ?>" alt="<?php echo urldecode(get_option( 'wp_alt_header','')); ?>"></a>
			</div>
			<div id="header-right">
			<?php
			
			echo urldecode($banner_html["Header banner"]).'
';
?>
			
		 <!--
		 <a href="." title="Galery Video" target="_blank"><img class="fade" src="http://147.91.204.66/wordpress/wp-content/plugins/wpvideo/theme/include/46800000.jpg" alt="Galery Video"></a>	
		 -->					</div>
		</div> <!-- header-inside -->
	</div> <!-- header -->

	<div id="navigation" style="background: <?php echo urldecode(get_option( 'wp_color_picker_menu','%23ed9136' )); ?>  top left repeat-x;">
		<div id="navigation-inside">
						<ul class="menu">
				<li class><a href="." title="Home">Home</a></li>

<li class="cat-item cat-item-3"><a onclick="return false;" href="#">Category</a>
<ul class="children" style="background: <?php echo urldecode(get_option( 'wp_color_picker_menu','%232A2A2A' )); ?> repeat;">
<?php
global $wpdb;
$table_name = $wpdb->prefix . "wp_video";
$rezultat_category= $wpdb->get_results( "SELECT DISTINCT(category) FROM ".$table_name." ORDER BY category DESC" );  
foreach ($rezultat_category as $value_category) 
{
echo '<li class="cat-item cat-item-7"><a href="?category='.$value_category->category.'">'.$value_category->category.'</a></li>';
}
?>


	
</ul>
</li>

<?php
$wp_video_menu_text=explode("\r\n",htmlentities(stripslashes(urldecode(get_option( 'wp_video_menu_text','About||page-about' ) ))));
foreach($wp_video_menu_text as $menu_item)
{ $menu_item_array=explode("||",$menu_item);
	echo '<li class="page_item page-item-184"><a href="'.$menu_item_array[1].'">'.$menu_item_array[0].'</a></li>';
}
?>


			</ul>
					</div> <!-- navigation-inside -->
	</div>  <!-- navigation -->
	
	<!-- include the featured content carousel for the home page -->
<?php 
if (isset($_GET['id']))	
{
foreach ($rezultat as $value) 
{

$iframe_url_s = get_string_between(html_entity_decode($value->article_text),'<iframe','</iframe>');		
$iframe_url=get_string_between($iframe_url_s,'://www.youtube.com/embed/','"');
$iframe_url=str_replace('://www.youtube.com/embed/','',$iframe_url);
$iframe_url=str_replace('"','',$iframe_url);
$iframe_url_s_origin=$iframe_url_s;
$iframe_url_s=str_replace($iframe_url,$iframe_url.'?showinfo=0&autohide=1&rel=0'.(get_option( 'wp_video_auto_play','0' )=="1"?'&autoplay=1':''),$iframe_url_s);
}	
?>	
<style>
ul.share-buttons{
  list-style: none;
  padding: 0 !important;
  margin: 0 !important;
  max-width: 960px !important;
 
}

ul.share-buttons li{
  display: inline;
  margin: 0 !important;
}


</style>
<div id="video" style="background: <?php echo urldecode(get_option( 'wp_color_picker_video_back','%23333333' )); ?> top left repeat-x;">
<div id="video-inside">
<div class="videoparts" id="v1">
<?php 
echo urldecode($banner_html["Video Left Banner"]).'
';
echo "</div><div class=\"videoparts\" id=\"v3\">";
echo urldecode($banner_html["Video Right Banner"]).'
';
echo "</div><div class=\"videoparts\" id=\"v2\">
";
?>
<meta itemprop="thumbnailURL" content="https://i.ytimg.com/vi/<?php echo $iframe_url;?>/hqdefault.jpg" />
<meta itemprop="embedURL" content="https://www.youtube.com/embed/<?php echo $iframe_url;?>?showinfo=0&rel=0&autohide=1" />
<meta itemprop="uploadDate" content="<?php echo date(DATE_RFC2822, $value->date);?>" />
<?php
echo $iframe_url_s.'</iframe>';


?>
</div>

<?php
$title_for_share =$value->title;
$url_m=site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).$current_page;
echo '
<ul class="share-buttons">

'.($rezultat_left==''?'':'<li><a title="Previous video: '.$rezultat_left.'" href="'.$rezultat_left.'"><img src="'.plugin_dir_url(__FILE__).'include/left.png"></a></li>').' 
<li><a href="https://www.facebook.com/sharer/sharer.php?u='.$url_m.'&t='.urlencode($title_for_share).'" title="Share on Facebook" target="_blank"><img src="'.plugin_dir_url(__FILE__).'img/sharer/Facebook.png"></a></li>
<li><a onclick="javascript:window.open(\'https://twitter.com/share?text='.urlencode($title_for_share).'&url='.urlencode($url_m).'\', \'twitwin\', \'left=20,top=20,width=500,height=500,toolbar=1,resizable=1\');"  href="javascript:void(0)" title="Tweet"><img src="'.plugin_dir_url(__FILE__).'img/sharer/Twitter.png"></a></li>
<li><a href="https://plus.google.com/share?url='.$url_m.'" target="_blank" title="Share on Google+"><img src="'.plugin_dir_url(__FILE__).'img/sharer/Google.png"></a></li>
<li><a href="https://pinterest.com/pin/create/button/?url='.$url_m.'&media=https://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg&description='.urlencode($title_for_share).'" target="_blank" title="Pin it"><img src="'.plugin_dir_url(__FILE__).'img/sharer/Pinterest.png"></a></li>
<li><a href="https://www.linkedin.com/shareArticle?mini=true&url='.$url_m.'&title='.urlencode($title_for_share).'&summary=&source=" target="_blank" title="Share on LinkedIn"><img src="'.plugin_dir_url(__FILE__).'img/sharer/LinkedIn.png"></a></li>
'.($rezultat_right==''?'':'<li><a title="Next video: '.$rezultat_right.'" href="'.$rezultat_right.'"><img src="'.plugin_dir_url(__FILE__).'include/right.png"></a></li>').' 

</ul>
';

?>

</div>
</div>
<?php
}
else
{
?>	
	
	<div id="carousel" style="background: <?php echo urldecode(get_option( 'wp_color_picker_video_back','%23333333' )); ?> top left repeat-x;">
	<div id="carousel-inside">
		<div class="infinite">
			<div class="carousel">
				<ul> 
					    
<?php
global $wpdb;
$table_name = $wpdb->prefix . "wp_video";
$rezultat_f= $wpdb->get_results( "SELECT id,keyword,video_url,title,article_text,category,featured,date FROM ".$table_name.' where date < '.time().' ORDER BY id DESC' ) ;  
shuffle($rezultat_f);
$i=1;
foreach ($rezultat_f as $value_f) 
{
if 	($i==9) break;
$i++;
$iframe_url_s = get_string_between(html_entity_decode($value_f->article_text),'<iframe','</iframe>');		
$iframe_url=get_string_between($iframe_url_s,'://www.youtube.com/embed/','"');
$iframe_url=str_replace('://www.youtube.com/embed/','',$iframe_url);
$iframe_url=str_replace('"','',$iframe_url);
$img='https://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg?custom=true&w=230&h=170';
$iframe_url_s=str_replace($iframe_url,$iframe_url.'?showinfo=0&rel=0&autohide=1',$iframe_url_s);

?>
                                        
<li>
<a class="post-frame-carousel-video <?php echo $i; ?> inline" href="<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_iframe_out&url=<?php echo $iframe_url;?>" title="<?php echo $value_f->title; ?>"></a>
<img width="230" height="170" src="<?php echo $img ;?>" class="attachment-featured size-featured wp-post-image" alt="Car" srcset="" sizes="(max-width: 230px) 100vw, 230px">
<?php if (get_option( 'wp_video_show_title_of_featured_videos','0' )=="1") echo '<h2 class="carousel-title"><a href="'.$value_f->video_url.'" title="'.$value_f->title.'">'.(strlen($value_f->title)>30 ? substr(html_entity_decode($value_f->title),0, 30).'...':$value_f->title).'</a></h2>';?>

</li>
<?php
}
?>					

						                				</ul>        
			</div> <!-- carousel -->
		</div> <!-- infinite -->
	</div> <!-- carousel-inside -->
</div> <!-- carousel -->
<?php
}
?>
	<div id="content">
	<a href="http://antennadeals.com/OutdoorAntennas.html"><img src="http://hd-antennas.info/wp-content/uploads/2017/07/Antenna-Deals-Outdoor-Antennas.jpg" alt="AntennaDeals.com - Digital Outdoor Antennas" class="img-fluid" /></a>
		<div id="content-inside">
		
	<?php	
// one article		
if (isset($_GET['id']))	
{
echo '<div class="above_title" style=" max-width: 100%;  height: auto;">'.urldecode($banner_html["Above title banner"]).'</div>
';
?>
<!--	
<div style="position: relative;height: 260px; width: 960px; overflow:hidden;">
<a id="aw0" target="_top" href="https://www.googleadservices.com/pagead/aclk?sa=L&amp;ai=CLs6kMouYV-XyA6_Atgeq4LWQDpXg3ptGrqmNkJ0D1_D0_QgQASDh-N4qYLEFoAGGlKT2A8gBAqkCrwBB0DMUlj7gAgCoAwHIA5kEqgSaAU_QRX7QcY9o2SVib_A8QBOKc8atADPDKw-tkkbTUyBwk5puzC35XMDjphI96cyhhBDg8cg_G3TwlLlwwkCV-AgojPPutW7TImvD9EMab_cEdeGjCl-FDhWk39urIpfEwnZiCfm8EYZhGRLfcwwEDxr0-6cbJcMZLfavVdk4bRE8SKPjb37TalCg0JQe9kTE4O_RwuVxQs-mliDgBAGIBgGgBgKAB-Lr2wmoB4HGG6gHpr4b2AcB&amp;num=1&amp;cid=CAASEuRoSQNKCBTGhnKuiuvdJ524uQ&amp;sig=AOD64_0sWXYE6otq5Aayn7PAdlcURSUmog&amp;client=ca-pub-7115030887738094&amp;nm=2&amp;nx=947&amp;ny=150&amp;mb=2&amp;adurl=http://www.digiturkplay.com/abonelik/spor-paketi" data-original-click-url="https://www.googleadservices.com/pagead/aclk?sa=L&amp;ai=CLs6kMouYV-XyA6_Atgeq4LWQDpXg3ptGrqmNkJ0D1_D0_QgQASDh-N4qYLEFoAGGlKT2A8gBAqkCrwBB0DMUlj7gAgCoAwHIA5kEqgSaAU_QRX7QcY9o2SVib_A8QBOKc8atADPDKw-tkkbTUyBwk5puzC35XMDjphI96cyhhBDg8cg_G3TwlLlwwkCV-AgojPPutW7TImvD9EMab_cEdeGjCl-FDhWk39urIpfEwnZiCfm8EYZhGRLfcwwEDxr0-6cbJcMZLfavVdk4bRE8SKPjb37TalCg0JQe9kTE4O_RwuVxQs-mliDgBAGIBgGgBgKAB-Lr2wmoB4HGG6gHpr4b2AcB&amp;num=1&amp;cid=CAASEuRoSQNKCBTGhnKuiuvdJ524uQ&amp;sig=AOD64_0sWXYE6otq5Aayn7PAdlcURSUmog&amp;client=ca-pub-7115030887738094&amp;adurl=http://www.digiturkplay.com/abonelik/spor-paketi"><img src="https://tpc.googlesyndication.com/simgad/6162105316363014206" border="0" width="970" alt="" class="img_ad" onload=""></a><style>div,ul,li{margin:0;padding:0;}.abgc{height:15px;position:absolute;right:16px;top:0px;text-rendering:geometricPrecision;width:15px;z-index:9020;}.abgb{height:15px;width:15px;}.abgc img{display:block;}.abgc svg{display:block;}.abgs{display:none;height:100%;}.abgl{text-decoration:none;}.abgi{fill-opacity:1.0;fill:#00aecd;stroke:none;}.abgbg{fill-opacity:1.0;fill:#cdcccc;stroke:none;}.abgtxt{fill:black;font-family:'Arial';font-size:100px;overflow:visible;stroke:none;}</style><div id="abgc" class="abgc" dir="ltr"><div id="abgb" class="abgb"><svg width="100%" height="100%"><rect class="abgbg" width="100%" height="100%"></rect><svg class="abgi" x="0px"><path d="M7.5,1.5a6,6,0,1,0,0,12a6,6,0,1,0,0,-12m0,1a5,5,0,1,1,0,10a5,5,0,1,1,0,-10ZM6.625,11l1.75,0l0,-4.5l-1.75,0ZM7.5,3.75a1,1,0,1,0,0,2a1,1,0,1,0,0,-2Z"></path></svg></svg></div><div id="abgs" class="abgs"><a id="abgl" class="abgl" href="https://www.google.com/url?ct=abg&amp;q=https://www.google.com/adsense/support/bin/request.py%3Fcontact%3Dabg_afc%26url%3Dhttp://www.tgrthaber.com.tr/%26gl%3DRS%26hl%3Dtr%26client%3Dca-pub-7115030887738094%26ai0%3DCLs6kMouYV-XyA6_Atgeq4LWQDpXg3ptGrqmNkJ0D1_D0_QgQASDh-N4qYLEFoAGGlKT2A8gBAqkCrwBB0DMUlj7gAgCoAwHIA5kEqgSaAU_QRX7QcY9o2SVib_A8QBOKc8atADPDKw-tkkbTUyBwk5puzC35XMDjphI96cyhhBDg8cg_G3TwlLlwwkCV-AgojPPutW7TImvD9EMab_cEdeGjCl-FDhWk39urIpfEwnZiCfm8EYZhGRLfcwwEDxr0-6cbJcMZLfavVdk4bRE8SKPjb37TalCg0JQe9kTE4O_RwuVxQs-mliDgBAGIBgGgBgKAB-Lr2wmoB4HGG6gHpr4b2AcB&amp;usg=AFQjCNF9SzmqTqQLGxUB1UKOVLAujD5w5w" target="_blank"><svg width="100%" height="100%"><path class="abgbg" d="M0,0L109,0L109,15L4,15s-4,0,-4,-4z"></path><svg class="abgtxt" x="5px" y="11px" width="36px"><text>Google</text></svg><svg class="abgtxt" x="43px" y="11px" width="49px"><text>Reklamları</text></svg><svg class="abgi" x="94px"><path d="M7.5,1.5a6,6,0,1,0,0,12a6,6,0,1,0,0,-12m0,1a5,5,0,1,1,0,10a5,5,0,1,1,0,-10ZM6.625,11l1.75,0l0,-4.5l-1.75,0ZM7.5,3.75a1,1,0,1,0,0,2a1,1,0,1,0,0,-2Z"></path></svg></svg></a></div></div><script>var abgp={elp:document.getElementById('abgcp'),el:document.getElementById('abgc'),ael:document.getElementById('abgs'),iel:document.getElementById('abgb'),hw:15,sw:109,hh:15,sh:15,himg:'https://tpc.googlesyndication.com'+'/pagead/images/abg/icon.png',simg:'https://tpc.googlesyndication.com/pagead/images/abg/tr.png',alt:'Google Reklamları',t:'Google',tw:36,t2:'Reklamları',t2w:49,tbo:0,att:'adsbygoogle',ff:'',halign:'right',fe:false,iba:false,lttp:true,umd:false,uic:false,uit:false,ict:document.getElementById('cbb'),icd:undefined,uaal:true,opi: false};</script><script src="https://tpc.googlesyndication.com/pagead/js/r20160721/r20110914/abg.js"></script><style>.cbc{background-image: url('https://tpc.googlesyndication.com/pagead/images/x_button_blue2.svg');background-position: right top;background-repeat: no-repeat;cursor:pointer;height:15px;right:0;top:0;margin:0;overflow:hidden;padding:0;position:absolute;transform: scaleX(1);width:16px;z-index:9010;}.cbc.cbc-hover {background-image: url('https://tpc.googlesyndication.com/pagead/images/x_button_dark.svg');}.cbc > .cb-x{height: 15px;position:absolute;width: 16px;right:0;top:0;}.cb-x > .cb-x-svg{background-color: lightgray;position:absolute;}.cbc.cbc-hover > .cb-x > .cb-x-svg{background-color: #58585a;}.cb-x > .cb-x-svg > .cb-x-svg-path{fill : #00aecd;}.cbc.cbc-hover > .cb-x > .cb-x-svg > .cb-x-svg-path{fill : white;}.cb-x > .cb-x-svg > .cb-x-svg-s-path{fill : white;}</style><div id="cbc" class="cbc"><div id="cb-x" class="cb-x"></div> </div> <style>.ddmc{background:#ccc;color:#000;padding:0;position:absolute;z-index:9020;max-width:100%;box-shadow:2px 2px 3px #aaaaaa;}.ddmc.left{margin-right:0;left:0px;}.ddmc.right{margin-left:0;right:0px;}.ddmc.top{bottom:20px;}.ddmc.bottom{top:20px;}.ddmc .tip{border-left:4px solid transparent;border-right:4px solid transparent;height:0;position:absolute;width:0;font-size:0;line-height:0;}.ddmc.bottom .tip{border-bottom:4px solid #ccc;top:-4px;}.ddmc.top .tip{border-top:4px solid #ccc;bottom:-4px;}.ddmc.right .tip{right:3px;}.ddmc.left .tip{left:3px;}.ddmc .dropdown-content{display:block;}.dropdown-content{display:none;border-collapse:collapse;}.dropdown-item{font:12px Arial,sans-serif;cursor:pointer;padding:3px 7px;vertical-align:middle;}.dropdown-item-hover, a.dropdown-item.dropdown-item-hover {background:#58585a;color:#fff;}.dropdown-content > table{border-collapse:collapse;border-spacing:0;}.dropdown-content > table > tbody > tr > td{padding:0;}a.dropdown-item {color: inherit;cursor: inherit;display: block;text-decoration: inherit;}</style><div id="ddmc" style="display:none" class="ddmc right bottom"><div class="tip"></div><div class="dropdown-content"><table><tbody><tr><td><div id="pubmute" style="border-bottom:1px solid #999;" class="dropdown-item"><span>Reklam sayfayı kaplıyor</span></div></td></tr><tr><td><div id="admute" class="dropdown-item"><span>Bu reklamı bir daha gösterme</span></div></td></tr></tbody></table></div></div><script>(function(){var h=this,k=function(a,b){var c=a.split("."),d=h;c[0]in d||!d.execScript||d.execScript("var "+c[0]);for(var f;c.length&&(f=c.shift());)c.length||void 0===b?d=d[f]?d[f]:d[f]={}:d[f]=b},aa=function(a,b,c){return a.call.apply(a.bind,arguments)},ca=function(a,b,c){if(!a)throw Error();if(2<arguments.length){var d=Array.prototype.slice.call(arguments,2);return function(){var c=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(c,d);return a.apply(b,c)}}return function(){return a.apply(b,arguments)}},l=function(a,b,c){l=Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?aa:ca;return l.apply(null,arguments)};var n="undefined"!=typeof DOMTokenList,q=function(a,b){if(n){var c=a.classList;0==c.contains(b)&&c.toggle(b)}else if(c=a.className){for(var c=c.split(/\s+/),d=!1,f=0;f<c.length&&!d;++f)d=c[f]==b;d||(c.push(b),a.className=c.join(" "))}else a.className=b},r=function(a,b){if(n){var c=a.classList;1==c.contains(b)&&c.toggle(b)}else if((c=a.className)&&!(0>c.indexOf(b))){for(var c=c.split(/\s+/),d=0;d<c.length;++d)c[d]==b&&c.splice(d--,1);a.className=c.join(" ")}};var t=function(a,b,c){a.addEventListener?a.addEventListener(b,c,!1):a.attachEvent&&a.attachEvent("on"+b,c)};var x=String.prototype.trim?function(a){return a.trim()}:function(a){return a.replace(/^[\s\xa0]+|[\s\xa0]+$/g,"")},y=function(a,b){return a<b?-1:a>b?1:0};var z;a:{var A=h.navigator;if(A){var B=A.userAgent;if(B){z=B;break a}}z=""};var da=-1!=z.indexOf("Opera"),C=-1!=z.indexOf("Trident")||-1!=z.indexOf("MSIE"),ea=-1!=z.indexOf("Edge"),D=-1!=z.indexOf("Gecko")&&!(-1!=z.toLowerCase().indexOf("webkit")&&-1==z.indexOf("Edge"))&&!(-1!=z.indexOf("Trident")||-1!=z.indexOf("MSIE"))&&-1==z.indexOf("Edge"),fa=-1!=z.toLowerCase().indexOf("webkit")&&-1==z.indexOf("Edge"),E=function(){var a=h.document;return a?a.documentMode:void 0},F;a:{var G="",H=function(){var a=z;if(D)return/rv\:([^\);]+)(\)|;)/.exec(a);if(ea)return/Edge\/([\d\.]+)/.exec(a);if(C)return/\b(?:MSIE|rv)[: ]([^\);]+)(\)|;)/.exec(a);if(fa)return/WebKit\/(\S+)/.exec(a);if(da)return/(?:Version)[ \/]?(\S+)/.exec(a)}();H&&(G=H?H[1]:"");if(C){var I=E();if(null!=I&&I>parseFloat(G)){F=String(I);break a}}F=G}var J=F,K={},L=function(a){if(!K[a]){for(var b=0,c=x(String(J)).split("."),d=x(String(a)).split("."),f=Math.max(c.length,d.length),e=0;0==b&&e<f;e++){var m=c[e]||"",g=d[e]||"",u=RegExp("(\\d*)(\\D*)","g"),v=RegExp("(\\d*)(\\D*)","g");do{var p=u.exec(m)||["","",""],w=v.exec(g)||["","",""];if(0==p[0].length&&0==w[0].length)break;b=y(0==p[1].length?0:parseInt(p[1],10),0==w[1].length?0:parseInt(w[1],10))||y(0==p[2].length,0==w[2].length)||y(p[2],w[2])}while(0==b)}K[a]=0<=b}},M=h.document,ga=M&&C?E()||("CSS1Compat"==M.compatMode?parseInt(J,10):5):void 0;var N;if(!(N=!D&&!C)){var O;if(O=C)O=9<=Number(ga);N=O}N||D&&L("1.9.1");C&&L("9");var ha=function(a,b){if(!a||!b)return!1;if(a.contains&&1==b.nodeType)return a==b||a.contains(b);if("undefined"!=typeof a.compareDocumentPosition)return a==b||!!(a.compareDocumentPosition(b)&16);for(;b&&a!=b;)b=b.parentNode;return b==a};var ia=function(a,b,c){var d="mouseenter_custom"==b,f=P(b);return function(e){e||(e=window.event);if(e.type==f){if("mouseenter_custom"==b||"mouseleave_custom"==b){var m;if(m=d?e.relatedTarget||e.fromElement:e.relatedTarget||e.toElement)for(var g=0;g<a.length;g++)if(ha(a[g],m))return}c(e)}}},P=function(a){return"mouseenter_custom"==a?"mouseover":"mouseleave_custom"==a?"mouseout":a};var Q=function(a,b,c,d,f,e,m,g,u,v){this.m=a;this.ca=b;this.K=c;this.aa=d;this.H=f;this.G=e;this.o=null;this.I=!1;this.F=v;this.T=u;this.j=document.getElementById("pubmute"+g);this.i=document.getElementById("admute"+g);this.l=document.getElementById("wta"+g);this.U=parseInt(g,10)||0;this.B();this.m.className=["ddmc",m&1?"left":"right",m&2?"top":"bottom"].join(" ")};Q.prototype.B=function(){R(this.m,"mouseenter_custom",this,this.v);R(this.m,"mouseleave_custom",this,this.L);this.j&&(R(this.j,"mouseenter_custom",this,this.Z),R(this.j,"mouseleave_custom",this,this.A),t(this.j,"click",l(this.ba,this)));this.i&&(R(this.i,"mouseenter_custom",this,this.P),R(this.i,"mouseleave_custom",this,this.u),t(this.i,"click",l(this.$,this)));this.l&&(R(this.l,"mouseenter_custom",this,this.da),R(this.l,"mouseleave_custom",this,this.C),t(this.l,"click",l(this.Y,this)))};Q.prototype.ba=function(){S(this);ka(this,0);var a=this.K;null!=a&&a();T(this,"user_feedback_menu_option","3",!0)};Q.prototype.$=function(){S(this);ka(this,1);var a=this.K;null!=a&&a();T(this,"user_feedback_menu_option","1",!0)};var ka=function(a,b){var c={type:b,close_button_token:a.G,creative_conversion_url:a.H,ablation_config:a.T,undo_callback:a.aa,creative_index:a.U};if(a.F)a.F.fireOnObject("mute_option_selected",c);else{var d;a:{d=["muteSurvey"];for(var f=h,e;e=d.shift();)if(null!=f[e])f=f[e];else{d=null;break a}d=f}d&&d.setupSurveyPage(c)}};Q.prototype.Y=function(){S(this);T(this,"closebutton_whythisad_click","1",!1)};var U=function(a,b){a.m.style.display=b?"":"none"};Q.prototype.L=function(){this.o=h.setTimeout(l(function(){S(this);this.o=null},this),500)};Q.prototype.v=function(){null!=this.o&&(h.clearTimeout(this.o),this.o=null)};var S=function(a){var b=a.ca;null!=b&&b();V(a)&&U(a,!1)};Q.prototype.Z=function(){this.j&&q(this.j,"dropdown-item-hover");this.u();this.C()};Q.prototype.A=function(){this.j&&r(this.j,"dropdown-item-hover")};Q.prototype.P=function(){this.i&&q(this.i,"dropdown-item-hover");this.A();this.C()};Q.prototype.u=function(){this.i&&r(this.i,"dropdown-item-hover")};Q.prototype.da=function(){this.l&&q(this.l,"dropdown-item-hover");this.u();this.A()};Q.prototype.C=function(){this.l&&r(this.l,"dropdown-item-hover")};var V=function(a){return"none"!==a.m.style.display};Q.prototype.toggle=function(){V(this)?V(this)&&U(this,!1):(U(this,!0),this.I||(this.I=!0,T(this,"user_feedback_menu_interaction")))};var T=function(a,b,c,d){a=a.H+"&label="+b+(c?"&label_instance="+c:"")+(d?"&cbt="+a.G:"");b=window;b.google_image_requests||(b.google_image_requests=[]);c=b.document.createElement("img");c.src=a;b.google_image_requests.push(c)},R=function(a,b,c,d){d=ia([a],b,l(d,c));t(a,P(b),l(d,c))};var W=function(a,b,c,d,f,e,m,g,u,v,p,w,ba){this.creativeConversionUrl=f;this.S=e;this.R=document.getElementById("cb-x"+p);f=l(this.w,this);e=l(this.J,this);var la=l(this.M,this);d?(g=g?1:0,u&&(g|=2),d=new Q(d,f,e,la,this.creativeConversionUrl,this.S,g,p,w,ba)):d=null;this.h=d;this.N=document.getElementById("pbc");this.g=a;this.O=b;this.D=c;this.s=ba;"undefined"!=typeof SVGElement&&"undefined"!=typeof document.createElementNS&&v&&(this.g.style.backgroundImage="none",this.R.appendChild(ma(m)));this.B()},X;W.prototype.B=function(){t(this.g,"click",l(this.V,this));t(this.g,"mouseover",l(this.X,this));t(this.g,"mouseout",l(this.W,this))};W.prototype.V=function(){this.h&&(this.h.v(),this.h.toggle())};W.prototype.X=function(){this.h&&this.h.v();null!==this.g&&q(this.g,"cbc-hover")};W.prototype.W=function(){this.h&&V(this.h)?this.h.L():this.w()};var ma=function(a){var b=document.createElementNS("//www.w3.org/2000/svg","svg"),c=document.createElementNS("//www.w3.org/2000/svg","path"),d=document.createElementNS("//www.w3.org/2000/svg","path"),f=1.15/Math.sqrt(2),e=.2*a,f="M"+(e+f+1)+","+e+"L"+(a/2+1)+","+(a/2-f)+"L"+(a-e-f+1)+","+e+"L"+(a-e+1)+","+(e+f)+"L"+(a/2+f+1)+","+a/2+"L"+(a-e+1)+","+(a-e-f)+"L"+(a-e-f+1)+","+(a-e)+"L"+(a/2+1)+","+(a/2+f)+"L"+(e+f+1)+","+(a-e)+"L"+(e+1)+","+(a-e-f)+"L"+(a/2-f+1)+","+a/2+"L"+(e+1)+","+(e+f)+"Z",e="M0,0L1,0L1,"+a+"L0,"+a+"Z";b.setAttribute("class","cb-x-svg");b.setAttribute("width",a+1);b.setAttribute("height",a);b.appendChild(c);b.appendChild(d);c.setAttribute("d",f);c.setAttribute("class","cb-x-svg-path");d.setAttribute("d",e);d.setAttribute("class","cb-x-svg-s-path");return b},Y=function(a){a&&(a.style.display="block")},Z=function(a){a&&(a.style.display="none")};W.prototype.w=function(){null!==this.g&&r(this.g,"cbc-hover")};W.prototype.J=function(){this.w();this.s?this.s.showOnly(0):(Z(this.g),Z(this.D),Z(this.N),Y(this.O))};W.prototype.M=function(){this.s?this.s.resetAll():(Y(this.g),Y(this.D),Y(this.N),Z(this.O))};k("cbb",function(a,b,c,d,f,e,m,g,u,v){t(window,"load",function(){a&&(X=new W(a,document.getElementById("cbtf"),b,c,d,f,15,m,g,u,v,e,window.adSlot))})});k("cbbha",function(){X.J()});k("cbbsa",function(){X.M()});}).call(this);cbb(document.getElementById('cbc'),document.getElementById('google_image_div'),document.getElementById('ddmc'),'https://googleads.g.doubleclick.net/pagead/conversion/?ai\x3dCLs6kMouYV-XyA6_Atgeq4LWQDpXg3ptGrqmNkJ0D1_D0_QgQASDh-N4qYLEFoAGGlKT2A8gBAqkCrwBB0DMUlj7gAgCoAwHIA5kEqgSaAU_QRX7QcY9o2SVib_A8QBOKc8atADPDKw-tkkbTUyBwk5puzC35XMDjphI96cyhhBDg8cg_G3TwlLlwwkCV-AgojPPutW7TImvD9EMab_cEdeGjCl-FDhWk39urIpfEwnZiCfm8EYZhGRLfcwwEDxr0-6cbJcMZLfavVdk4bRE8SKPjb37TalCg0JQe9kTE4O_RwuVxQs-mliDgBAGIBgGgBgKAB-Lr2wmoB4HGG6gHpr4b2AcB\x26sigh\x3dMKp9SJFHIi8','Jx8iyNZqEegIrqmNkJ0DEOON060CGOrB-QgiGXd3dy5kaWdpdHVya3BsYXkuY29tL3Nwb3IyCAgFExicshAUQhdjYS1wdWItNzExNTAzMDg4NzczODA5NEgEWAJwAQ','{\x22key_value\x22:[],\x22googMsgType\x22:\x22sth\x22}',false,false,false,'');</script>
</div>
-->
<?php	
}
?>		
		
			<div id="breadcrumbs">
				<p>You are here: <strong><?php if (isset($_GET['s'])){echo "Search '".$_GET['s']."'";} else {echo ($about=='true' ? 'About':'Home');}		
if (isset($_GET['id']))	
{
	echo ' - > '.html_entity_decode($value->title);
}

if (isset($_GET['category']))	
{
	echo ' - > '.$_GET['category'];
}
?></strong></p>				<script type="text/javascript">
	function doClear(theText) {
		if (theText.value == theText.defaultValue) {
			theText.value = ""
		}
	}
</script>
<?php if (get_option( 'wp_video_post_show_search_box','1' )=="1")
{
?>	
<div id="search">
	<form method="get" id="search-form" action=".">
		<input type="text" name="s" id="s" value="Find Something" onfocus="doClear(this)">
		<input type="submit" id="search-submit" value="Search">
	</form>
</div>
<?php
}
?>		
</div>
		
<div id="main">
<?php	
// one article		
if (isset($_GET['id']))	
{
?>	
<div id="post-121" class="single post-121 post type-post status-publish format-standard has-post-thumbnail hentry category-category category-sample-category category-sample-child-category category-sample-videos category-sub-category category-vimeo-videos">
<h1 itemprop="name" style="padding-top: 20px;padding-bottom: 30px; line-height: 30px;"><?php
echo html_entity_decode($value->title);

?></h1>
<div class="entry">
<?php
echo '<div class="above_title" style="max-width: 100%;  height: auto;">'.urldecode($banner_html["Inline article banner"]).'</div>
';
echo '<div style="clear: both;"><div class="side_title" style="clear: left;float:right; max-width: 100%;  height: auto;">'.urldecode($banner_html["Side article banner"]).'</div>
';
$iframe_text=str_replace($iframe_url_s_origin.'</iframe>','',html_entity_decode($value->article_text));
echo ''.$iframe_text.'';
echo '</div><div class="above_title" style="max-width: 100%;  height: auto;">'.urldecode($banner_html["Below article banner"]).'</div>
<div class="mobile_banner" style="max-width: 100%;  height: auto;">'.urldecode($banner_html["Mobile banner"]).'</div>
<span style="display: none;" itemprop="description">'.substr(strip_tags($iframe_text),0,strposX($iframe_text,' ',50)).'...</span>
';
?>

</div>
</div>

<?php

}
else
{
if ($about=='true')
{
	echo stripslashes(urldecode(get_option( 'wp_video_about_text','This is about page !' ) ));
}	
else	
{	
$i=1;
foreach ($rezultat as $value) 
{
$i++;
$iframe_url_s = get_string_between(html_entity_decode($value->article_text),'<iframe','</iframe>');		
$iframe_url=get_string_between($iframe_url_s,'://www.youtube.com/embed/','"');
$iframe_url=str_replace('://www.youtube.com/embed/','',$iframe_url);
$iframe_url=str_replace('"','',$iframe_url);
$img='https://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg';
$iframe_url_s=str_replace($iframe_url,$iframe_url.'?showinfo=0&rel=0&autohide=1',$iframe_url_s);
?> 

<div id="post-<?php echo $i; ?>" class="multiple post-<?php echo $i; ?> post type-post status-publish format-standard has-post-thumbnail hentry category-category category-sample-category category-sample-child-category category-sample-videos category-sub-category category-vimeo-videos">			
<div class="post-image">
<a class="post-frame-video <?php echo $i; ?> inline" href="<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_iframe_out&url=<?php echo $iframe_url;?>" title="This Is a Sample Video Post"></a>
												
						<img width="230" height="170" src="<?php echo $img ;?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="Car" srcset="" sizes="(max-width: 180px) 100vw, 180px">					</div>
					
					
				
					<h2><a href="<?php echo $value->video_url ;?>" rel="bookmark" title="<?php echo $value->title; ?>"><?php echo (strlen($value->title)>55 ? substr(html_entity_decode($value->title),0, 55).'...':htmlspecialchars_decode($value->title))?></a></h2>
			<!--
							<li>Posted on <?php echo date("F j, Y",$value->date); ?></li>
							-->
				</div> <!-- post -->
				
	<?php
}
?>								
				
								
								
	<?php
if ($pages>1) echo '<br><div style="float: left;" id="paging"><br><p style="font-size: 18px;">', $prevlink, ' Page ', $page, ' of ', $pages, ' pages ', $nextlink, ' </p></div>';
}	
}

?>								
			 
			</div> <!-- main -->
				
			<div id="sidebar">
			<?php
			if (get_option( 'wp_video_post_general_sidebar','%3Ch2%20align%3D%22center%22%3EThis%20Is%20The%20Sidebar%3C%2Fh2%3E%3Cbr%3E%0A%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'img%2F220600.png%22%3E%09%09' )!='')	
{
echo stripslashes(urldecode(get_option( 'wp_video_post_general_sidebar','%3Ch2%20align%3D%22center%22%3EThis%20Is%20The%20Sidebar%3C%2Fh2%3E%3Cbr%3E%0A%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'img%2F220600.png%22%3E%09%09' ) ));
}
else
{
echo urldecode($banner_html["Side article banner"]).'
';
}
			?>
		</div> <!-- sidebar -->		</div> <!-- content-inside -->
	</div> <!-- content -->

	<div id="footer" style="background: <?php echo urldecode(get_option( 'wp_color_picker_video_back','%23333333' )); ?>  top left repeat-x;">
		<div id="footer-inside">
						
							<p><?php echo urldecode(get_option( 'wp_site_desing_text','Site Design by: wpvideosites' )); ?></p>
						<!-- 50 queries. 0.334 seconds. -->
		</div> <!-- footer-inside -->
	</div> <!-- footer -->
	
<!--	<script type="text/javascript">
/* <![CDATA[ */
//var thickboxL10n = {"next":"Next >","prev":"< Prev","image":"Image","of":"of","close":"Close","noiframes":"This feature requires inline frames. You have iframes disabled or your browser does not support them.","loadingAnimation":"loadingAnimation.gif"};
/* ]]> */
</script>

<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ; ?>include/thickbox.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ; ?>include/wp-embed.js"></script>
-->	
		<!--[if IE 6]>
	<script type="text/javascript"> 
		/*Load jQuery if not already loaded*/ if(typeof jQuery == 'undefined'){ document.write("<script type=\"text/javascript\"   src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js\"></"+"script>"); var __noconflict = true; } 
		var IE6UPDATE_OPTIONS = {
			icons_path: "http://static.ie6update.com/hosted/ie6update/images/"
		}
	</script>
	<script type="text/javascript" src="http://static.ie6update.com/hosted/ie6update/ie6update.js"></script>
	<![endif]-->
	</div>
	</div>
	<?php
echo stripslashes(urldecode(get_option( 'wp_footer_script','%3Cscript%20type%3D%22text%2Fjavascript%22%3E%0D%0A%3C%2Fscript%3E' )));
?>
	</body>
</html>


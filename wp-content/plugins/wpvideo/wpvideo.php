<?php 
    /*
    Plugin Name: WpVideoSites
    Plugin URI: http://www.wpvideosites.com
    Description: Wp Video Sites is a WordPress plugin that allows you to create video blogs inside of your WordPress websites.
    Author: Abbas Ravji
    Version: 1.3.80
    Author URI: http://abbasravji.com
    */

function my_title_wpvideo($title)   
{  



    global $post;  
      
    // Check if a certain scenario is met  
    if (strpos($_SERVER['REQUEST_URI'], wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' ))) !== false) 
    {  
$current_page=substr($_SERVER['REQUEST_URI'], (strlen(wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' )))-strlen($_SERVER['REQUEST_URI'])));
	
	if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))=='') $current_page='/'.$current_page;
	if ($current_page==$_SERVER['REQUEST_URI']) { echo '<script>window.location = "'.$_SERVER['REQUEST_URI'].'/";</script>';}
 
	if ($current_page != '/')
	{
  if (substr($current_page,0,2) != '/?')
  {
	  
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video";
	$rezultat= $wpdb->get_results( "SELECT id FROM ".$table_name.' WHERE video_url=\''.substr($current_page,1).'\'');  
    $_GET['id']=$rezultat[0]->id;
	if ($current_page=='/page-about') {$about='true';}
  }
	}

        // Do whatever here with your title...  
        $title = (isset($_GET['id']) ? html_entity_decode($rezultat[0]->title) : (isset($_GET['category']) ? 'Category: '.urldecode($_GET['category']) : urldecode(get_option( 'wp_home_meta_title','' ))).($about=='true'?' - about page':'').(isset($_GET['pg']) ? ' - part '.$_GET['pg']:'')) ;  
      
        // We can also do other things here, like remove the canonical tag, for example...    
        remove_action('wp_head', 'rel_canonical');  
    }  
      
    return $title;  
}  
add_filter('aioseop_title', 'my_title_wpvideo', 1); 
	
function filter_pagetitle_wpvideo($title) {
if (strpos($_SERVER['REQUEST_URI'], wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' ))) !== false)
	{ 
$current_page=substr($_SERVER['REQUEST_URI'], (strlen(wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' )))-strlen($_SERVER['REQUEST_URI'])));
	
	if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))=='') $current_page='/'.$current_page;
	if ($current_page==$_SERVER['REQUEST_URI']) { echo '<script>window.location = "'.$_SERVER['REQUEST_URI'].'/";</script>';}
 
	if ($current_page != '/')
	{
  if (substr($current_page,0,2) != '/?')
  {
	  
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video";
	$rezultat= $wpdb->get_results( "SELECT id FROM ".$table_name.' WHERE video_url=\''.substr($current_page,1).'\'');  
    $_GET['id']=$rezultat[0]->id;
	
  }
	}


remove_action('wp_head', 'rel_canonical');
remove_action( ‘wp_head’, ‘feed_links_extra’, 3 ); // Removes the links to the extra feeds such as category feeds
remove_action( ‘wp_head’, ‘feed_links’, 2 ); // Removes links to the general feeds: Post and Comment Feed
remove_action( ‘wp_head’, ‘rsd_link’); // Removes the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( ‘wp_head’, ‘wlwmanifest_link’); // Removes the link to the Windows Live Writer manifest file.
remove_action( ‘wp_head’, ‘index_rel_link’); // Removes the index link
remove_action( ‘wp_head’, ‘parent_post_rel_link’); // Removes the prev link
remove_action( ‘wp_head’, ‘start_post_rel_link’); // Removes the start link
remove_action( ‘wp_head’, ‘adjacent_posts_rel_link’); // Removes the relational links for the posts adjacent to the current post.
remove_action( ‘wp_head’, ‘wp_generator’); // Removes the WordPress version i.e. –
return (isset($_GET['id']) ? html_entity_decode($rezultat[0]->title) : urldecode(get_option( 'wp_home_meta_title','' )));
} else {return $title ;}

}


add_filter('wp_title', 'filter_pagetitle_wpvideo',10000,1);

add_filter('pre_get_document_title', 'filter_pagetitle_wpvideo',998,1);	
	
	function remove_canonical_wpvideo() {

    // Disable for 'child' page
 if (strpos($_SERVER['REQUEST_URI'], wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' ))) !== false)
 {
        add_filter( 'wpseo_canonical', '__return_false',  10, 1 );
    }
}
add_action('wp', 'remove_canonical_wpvideo');



	
	
function wpvideos_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}
	add_action( 'admin_enqueue_scripts', 'wpvideos_add_color_picker' );
	
function enqueue_date_picker(){
                wp_enqueue_script(
			'field-date-js', 
			'Field_Date.js', 
			array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
			time(),
			true
		);	
//wp_enqueue_style( 'jquery.ui.theme', plugin_dir_url( __FILE__ ) . '/css/datepicker.css' , array( 'jquery-ui-core', 'jquery-ui-datepicker' ), $this->version, 'all' );

		wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
  wp_enqueue_style( 'jquery-ui' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_date_picker' );


	
	
function wp_video_add_meta_boxes_page()
{
	
    add_meta_box( 
       'wp_video_custom_meta_box', // this is HTML id
       'Add video', 
       'wp_video_custom_meta_box', // the callback function
       'page',
	   'side',
	   'high'
    );


	
}

add_action( "add_meta_boxes_page", "wp_video_add_meta_boxes_page" );

function video_on_admin_init() {
			
			  add_meta_box( 
       'wp_video_custom_meta_box', // this is HTML id
       'Add video', 
       'wp_video_custom_meta_box', // the callback function
       'post',
	   'side',
	   'high'
    );
			
		
		}
		
		
		
	
add_action( 'admin_init', 'video_on_admin_init' );

function wp_video_custom_meta_box( $post )
{
	echo '<a href="admin.php?page=WpVideo&add=true&backlink='.$post->ID.'">Add videos with backlink to this page</a><br><br><a href="'.site_url().'/?feed=video-feed&id='.$post->ID.'">RSS feed of videos for this post</a><br><br><a href="'.site_url().'/?feed=video-sitemap&id='.$post->ID.'">Video sitemap of videos for this post</a>';
	
}





function roots_add_rewrites_wpvideo($content) {
	global $wp_rewrite;
	$roots_new_non_wp_rules = array(
	
		'misa-test(.*)'      => 'wp-video$1'
		
	);
	$wp_rewrite->non_wp_rules += $roots_new_non_wp_rules;
}

//add_action('generate_rewrite_rules', 'roots_add_rewrites_wpvideo');	

function wp_video_install() {
	
	$my_post = array(
  'post_title'    => 'WpVideoSites Plugin',
  'post_name' => 'wp-video',
  'post_content'  => 'Pageholder for WpVideoSites.',
  'post_author'   => 1
);
 
// Insert the post into the database
$wpvs_id=wp_insert_post( $my_post );
update_option( 'wp_video_post_id', $wpvs_id );	
	

	
	
	
	
	global $wpdb;

    $table_name = $wpdb->prefix . "wp_video"; 
	
	$charset_collate = $wpdb->get_charset_collate();
	
	
  //time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  keyword varchar(255) DEFAULT '' NOT NULL,
  video_url varchar(1024) DEFAULT '' NOT NULL,
  title varchar(1024) DEFAULT '' NOT NULL,
  meta_description varchar(1024) DEFAULT '' NOT NULL,
  article_text mediumtext DEFAULT '' NOT NULL,
  category varchar(512) DEFAULT '' NOT NULL,
  featured varchar(55) DEFAULT '' NOT NULL,
  date varchar(55) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

}

function wp_video_banners_install() {
	global $wpdb;

    $table_name = $wpdb->prefix . "wp_video_banners"; 
	
	$charset_collate = $wpdb->get_charset_collate();
	
	
  //time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  type varchar(1024) DEFAULT '' NOT NULL,
  category varchar(1024) DEFAULT '' NOT NULL,
  position varchar(1024) DEFAULT '' NOT NULL,
  html_text mediumtext DEFAULT '' NOT NULL,
  date varchar(55) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

$total=$wpdb->get_var("SELECT COUNT(*) FROM ".$table_name.";");
if ($total==0)
{
	
	$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'global',
		'category' => 'General',	
		'position' => 'Header banner',
		'html_text' => '%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'theme%2Fimg%2FHeaderBanner.jpg%22%3E%09%09'
	)
	);
	
	$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'global',
		'category' => 'General',	
		'position' => 'Video Left Banner',
		'html_text' => '%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'theme%2Fimg%2FVideo-Left.png%22%3E%09%09'
	)
	);
	$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'global',
		'category' => 'General',	
		'position' => 'Video Right Banner',
		'html_text' => '%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'theme%2Fimg%2FVideo-right.png%22%3E%09%09'
	)
	);
	$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'global',
		'category' => 'General',	
		'position' => 'Above title banner',
		'html_text' => '%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'theme%2Fimg%2FAbove-title-banner.png%22%3E%09%09'
	)
	);
	$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'global',
		'category' => 'General',	
		'position' => 'Below article banner',
		'html_text' => '%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'theme%2Fimg%2FbelowArticle.png%22%3E%09%09'
	)
	);
	$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'global',
		'category' => 'General',	
		'position' => 'Inline article banner',
		'html_text' => '%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'theme%2Fimg%2FInlineArticle.png%22%3E%09%09'
	)
	);
}	


}



register_activation_hook( __FILE__, 'wp_video_install' );

register_activation_hook( __FILE__, 'wp_video_banners_install' );

register_deactivation_hook( __FILE__, 'wp_video__uninstall' );	
function wp_video__uninstall() 
{
	//remove_all_actions( 'do_feed_video-sitemap' );
//global $wpdb;
//$table_name = $wpdb->prefix . "wp_video"; 
//$wpdb->query("DROP TABLE IF EXISTS $table_name");
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
	
	
function wpvideo455_admin() {
	if (get_option( 'werwerwpfg678_4567_temp','0' ) != '1' )
{

echo '
<table class="form-table">
<tbody><tr>
<td><td><iframe id="search_keyword"  style="width: 700px; height: 500px;" frameborder="0" src="../wp-admin/admin-ajax.php?action=wpvideo_authorise" ></iframe></td>
</tr></tbody>
</table>';
die();
}
if (get_option( 'werwerwpfg678_4567_temp_dev','0' ) == '1' ) $develop=true;
 include('video.php');
}

function wpseo245732455_wp_video_settings() 
{
include('wpvideo_settings.php');
}
function oscimp_admin_video245732455actions() {
    add_menu_page("WpVideoSites", "WpVideoSites", "manage_options", "WpVideo", "wpvideo455_admin",'dashicons-video-alt2' );
	
	add_submenu_page( 'WpVideo', 'Settings', 'Settings','manage_options','WpVideo-settings' ,'wpseo245732455_wp_video_settings');
	
    //add_submenu_page( 'WpAnalyst', 'History', 'History','manage_options','WpAnalyst-History' ,'wpseovideo245732455_history_main_admin');
}
add_action('admin_menu', 'oscimp_admin_video245732455actions');


function my_page_template_redirect()
{
    if (strpos($_SERVER['REQUEST_URI'], wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' ))) !== false)
    {
        remove_filter('template_redirect','redirect_canonical'); 
       
    }
}
//add_action( 'template_redirect', 'my_page_template_redirect' );
add_action( 'wp', 'my_page_template_redirect' );


function portfolio_page_template( $template ) {
 if (strpos($_SERVER['REQUEST_URI'], wp_make_link_relative(site_url('/')).urldecode(get_option( 'wp_video_relative_url','wp-video' ))) !== false)
	{
	 status_header( 200 );
	 //header("Cache-Control: max-age=86400");
		$new_template = plugin_dir_path( __FILE__ ).'theme/video-template.php';
		
		if ( '' != $new_template ) 
		{
			return $new_template ;
		}
	}

	return $template;
}

add_filter( 'template_include', 'portfolio_page_template', 99 );
//==============


//=========
add_action( 'wp_ajax_youtube_wpvideo', 'youtube_wpvideo' );

function youtube_wpvideo() 
{	
include('youtube.php');
die();
}

add_action( 'wp_ajax_youtube_wpvideo_mode', 'youtube_wpvideo_mode' );

function youtube_wpvideo_mode() 
{	
include('youtube_video_mode.php');
die();
}

//if (has_action('wp_ajax_keyword_research_video')) 
add_action( 'wp_ajax_vkeyword_research_video', 'vkeyword_research_video' );

//if (!function_exists('keyword_research_video'))
//{
function vkeyword_research_video() 
{	
include('keyword_research.php');
die();
}
//}
add_action( 'wp_ajax_varticle_builder', 'varticle_builder' );

function varticle_builder() 
{	
include('article_builder.php');
die();
}

add_action( 'wp_ajax_vbest_spinner', 'vbest_spinner' );

function vbest_spinner() 
{	
include('bestspinner.php');
die();
}

add_action( 'wp_ajax_vspin_rewriter', 'vspin_rewriter' );

function vspin_rewriter() 
{	
include('spin_rewriter.php');
die();
}

add_action( 'wp_ajax_wpvideo_authorise', 'wpvideo_authorise' );

function wpvideo_authorise() 
{	
include('licence.php');
die();
}

add_action( 'wp_ajax_wpvideo_up', 'wpvideo_up' );

function wpvideo_up() 
{	
include('up.php');
die();
}


add_action( 'wp_ajax_pinterest_image', 'pinterest_image' );
add_action( 'wp_ajax_nopriv_pinterest_image', 'pinterest_image' );
function pinterest_image() 
{	
header('Content-type: image/jpeg');
$ch = curl_init();
	$timeout = 20;
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, 'https://i.ytimg.com/vi/'.$_GET['yt_id'].'/hqdefault.jpg');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec($ch);
	curl_close($ch);
	echo $data;
}



add_action( 'wp_ajax_tumblr_connect', 'tumblr_connect' );

function tumblr_connect() 
{	
// Start a session.  This is necessary to hold on to  a few keys the callback script will also need
session_start();

// Include the TumblrOAuth library
require_once('tumblroauth/tumblroauth.php');
update_option( 'wp_video_tumblr_consumer', $_GET['username'] );
update_option( 'wp_video_tumblr_secret', $_GET['pass'] );
// Define the needed keys
$consumer_key = get_option( 'wp_video_tumblr_consumer','' );
$consumer_secret = get_option( 'wp_video_tumblr_secret','' );

// The callback URL is the script that gets called after the user authenticates with tumblr
// In this example, it would be the included callback.php
#$callback_url = "http://techslides.com/api/tumblr/callback.php";
$callback_url = site_url('/')."wp-admin/admin-ajax.php?action=tumblr_callback";

// Let's begin.  First we need a Request Token.  The request token is required to send the user
// to Tumblr's login page.

// Create a new instance of the TumblrOAuth library.  For this step, all we need to give the library is our
// Consumer Key and Consumer Secret
$tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret);

// Ask Tumblr for a Request Token.  Specify the Callback URL here too (although this should be optional)
$request_token = $tum_oauth->getRequestToken($callback_url);

// Store the request token and Request Token Secret as out callback.php script will need this
$_SESSION['request_token'] = $token = $request_token['oauth_token'];
$_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];

// Check the HTTP Code.  It should be a 200 (OK), if it's anything else then something didn't work.
switch ($tum_oauth->http_code) {
  case 200:
    // Ask Tumblr to give us a special address to their login page
    $url = $tum_oauth->getAuthorizeURL($token);
	
	// Redirect the user to the login URL given to us by Tumblr
    //header('Location: ' . $url);
	echo $url;
	// That's it for our side.  The user is sent to a Tumblr Login page and
	// asked to authroize our app.  After that, Tumblr sends the user back to
	// our Callback URL (callback.php) along with some information we need to get
	// an access token.
	
    break;
default:
    // Give an error message
    echo 'Could not connect to Tumblr. Check key or try again later.';
}
die();
}

add_action( 'wp_ajax_tumblr_logout', 'tumblr_logout' );

function tumblr_logout() 
{
update_option( 'wp_video_tumblr_oauth_token', '' );
update_option( 'wp_video_tumblr_oauth_token_secret', '' );
}



add_action( 'wp_ajax_tumblr_callback', 'tumblr_callback' );
add_action( 'wp_ajax_nopriv_tumblr_callback', 'tumblr_callback' );
function tumblr_callback() 
{
session_start();
require_once('tumblroauth/tumblroauth.php');

// Define the needed keys
$consumer_key = get_option( 'wp_video_tumblr_consumer','' );
$consumer_secret = get_option( 'wp_video_tumblr_secret','' );

// Once the user approves your app at Tumblr, they are sent back to this script.
// This script is passed two parameters in the URL, oauth_token (our Request Token)
// and oauth_verifier (Key that we need to get Access Token).
// We'll also need out Request Token Secret, which we stored in a session.

// Create instance of TumblrOAuth.
// It'll need our Consumer Key and Secret as well as our Request Token and Secret
$tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $_SESSION['request_token'], $_SESSION['request_token_secret']);

// Ok, let's get an Access Token. We'll need to pass along our oauth_verifier which was given to us in the URL. 
$access_token = $tum_oauth->getAccessToken($_REQUEST['oauth_verifier']);

// We're done with the Request Token and Secret so let's remove those.
unset($_SESSION['request_token']);
unset($_SESSION['request_token_secret']);

// Make sure nothing went wrong.
if (200 == $tum_oauth->http_code) {
  // good to go
} else {
  die('Unable to authenticate');
}

echo "oauth_token: ".$access_token['oauth_token']."<br>oauth_token_secret: ".$access_token['oauth_token_secret']."<br><br>"; //print the access token and secret for later use
update_option( 'wp_video_tumblr_oauth_token', $access_token['oauth_token'] );
update_option( 'wp_video_tumblr_oauth_token_secret', $access_token['oauth_token_secret'] );
//echo '<script type="text/javascript"> window.opener.location.reload(false);window.close();</script>';
echo '<script type="text/javascript"> window.opener.document.getElementById("tumblr_div_out").innerHTML=\'Loged in: '.$access_token['oauth_token'].' <input onclick="this.disabled = true;tumblr_logout();return false;" class="button" type="button" value="Logout">\';window.close();</script>';
die();
}


//=======================================================================

add_action( 'wp_ajax_youtube_iframe_out', 'youtube_iframe_out' );
add_action( 'wp_ajax_nopriv_youtube_iframe_out', 'youtube_iframe_out' );

function youtube_iframe_out() 
{	
echo '<iframe title="YouTube video player" class="youtube-player" type="text/html" width="604" height="340" src="https://www.youtube.com/embed/'.$_GET['url'].'?showinfo=0&amp;rel=0&amp;autohide=1" frameborder="0" allowfullscreen=""></iframe>';
die();
}

add_action( 'wp_ajax_wpvs_syndicate', 'wpvs_syndicate' );


function wpvs_syndicate() 
{	
include('syndicate.php');
die();
}



add_action('init', 'customRSSvideo');
function customRSSvideo()
{
add_feed('video-feed', 'customRSSFuncvideo');
}
function customRSSFuncvideo()
{
include('rss_video.php');
die();			
}		

	

add_action('init', 'customSiteMapvideo');
function customSiteMapvideo()
{
//global $wp_rewrite;
//remove_all_actions( 'do_feed_video-sitemap' );
add_feed('video-sitemap', 'customSiteMapFuncvideo');
//print_r($wp_rewrite->feeds);
}
function customSiteMapFuncvideo()
{
include('sitemap_video.php');
die();			
}

?>
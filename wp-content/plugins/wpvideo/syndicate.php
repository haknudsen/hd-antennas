<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}	
function tweet($message,$image) {

// add the codebird library
require_once('twitter/codebird.php');

// note: consumerKey, consumerSecret, accessToken, and accessTokenSecret all come from your twitter app at https://apps.twitter.com/
\Codebird\Codebird::setConsumerKey(get_option( 'wp_video_twitter_consumer','' ), get_option( 'wp_video_twitter_secret','' ));
$cb = \Codebird\Codebird::getInstance();
$cb->setToken(get_option( 'wp_video_twitter_token','' ), get_option( 'wp_video_twitter_token_secret','' ));
if (false)
{	
//build an array of images to send to twitter
$reply = $cb->media_upload(array(
    'media' => $image
));
//upload the file to your twitter account
$mediaID = $reply->media_id_string;
}
//build the data needed to send to twitter, including the tweet and the image id
$params = array(
    'status' => $message,
    //'media_ids' => $mediaID,
);
//post the tweet with codebird
$reply = $cb->statuses_update($params);
return $reply;
}



if (isset($_GET['id']))
{	
global $wpdb;
$table_name = $wpdb->prefix . "wp_video";

	
$rezultat= $wpdb->get_results( "SELECT id,keyword,video_url,title,article_text,category,featured,date FROM ".$table_name.' where id="'.$_GET['id'].'";'  );  	
$iframe_url_s = get_string_between(html_entity_decode($rezultat[0]->article_text),'<iframe','</iframe>');
$iframe_url=get_string_between($iframe_url_s,'://www.youtube.com/embed/','"');	
$iframe_url=str_replace('://www.youtube.com/embed/','',$iframe_url);
$iframe_url=str_replace('"','',$iframe_url);
$description=str_replace($iframe_url_s.'</iframe>','',html_entity_decode($rezultat[0]->article_text));
$description=( get_option( 'wp_video_soc_post_max_c','0' )==0 ? $description : (strlen($description)>get_option( 'wp_video_soc_post_max_c','0' ) ? substr($description,0, get_option( 'wp_video_soc_post_max_c','300' )).'...</div>':$description));


//-------------------------------------------------------	
if ($_GET['tumblr']=='true')
{	
require_once('tumblroauth/tumblroauth.php');

// Define the needed keys
$consumer_key = get_option( 'wp_video_tumblr_consumer','' );
$consumer_secret = get_option( 'wp_video_tumblr_secret','' );
$oauth_token = get_option( 'wp_video_tumblr_oauth_token','' );
$oauth_token_secret = get_option( 'wp_video_tumblr_oauth_token_secret','' );
$base_hostname = get_option( 'wp_video_tumblr_target_blog','' );

//posting URI - http://www.tumblr.com/docs/en/api/v2#posting
$post_URI = 'http://api.tumblr.com/v2/blog/'.$base_hostname.'/post';

$tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);

// Make an API call with the TumblrOAuth instance. For text Post, pass parameters of type, title, and body

//$parameters = array();
//$parameters['type'] = "text";
//$parameters['title'] = "title text";
//$parameters['body'] = "body text";

$TagList=explode(",",htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_social_tags','' ) ))));
shuffle($TagList);
$tag_up_limit=rand( get_option( 'wp_video_tags_random_min','1'), get_option( 'wp_video_tags_random_max','3') );
$tags='';
for($i=0;$i<min(count($TagList),$tag_up_limit); $i++)
{
	$tags=$tags.','.trim($TagList[$i]);
}
$link=site_url().'/';
if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))!='') $link=site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/';
 $arrMessage = array(
			  'tags' => $tags,
              'type' => 'photo', 
              'caption' => '<h1>'.$rezultat[0]->title.'<h1>'.$description,
			  'link' => $link.$rezultat[0]->video_url,
              'source' =>'https://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg',
              'format' => 'html'
              );


$post = $tum_oauth->post($post_URI, $arrMessage);

//var_dump($tum_oauth);
echo "<br>";
//var_dump($post);

// Check for an error.
if (201 == $tum_oauth->http_code) {
  echo 'Tumblr - ';
  echo $post->meta->msg;
  echo ': <a target="_blank" href="https://'.urldecode(get_option( 'wp_video_tumblr_target_blog','')).'.tumblr.com/post/'.$post->response->id.'/">https://'.urldecode(get_option( 'wp_video_tumblr_target_blog','')).'.tumblr.com/post/'.$post->response->id.'</a></br>';
  
} else {
  //die('error');
  echo 'Tumblr Error - ';
  var_dump($post);
}
}
//---------------------------------------------------------------------------------------------------
if ($_GET['twitter']=='true')
{
$TagList=explode(",",htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_social_tags_twitter','' ) ))));
shuffle($TagList);
$tag_up_limit=rand( get_option( 'wp_video_tags_random_min_twitter','1'), get_option( 'wp_video_tags_random_max_twitter','3') );
$tags='';
for($i=0;$i<min(count($TagList),$tag_up_limit); $i++)
{
	$tags=$tags.' #'.trim($TagList[$i]);
}

$link=site_url().'/';
if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))!='') $link=site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/';

$post_twit=tweet($tags." ".$link.$rezultat[0]->video_url,'https://i.ytimg.com/vi/'.$iframe_url.'/hqdefault.jpg');
if ($post_twit->httpstatus==200)
{
echo 'Tweet created: <a target="_blank" href="https://twitter.com/'.$post_twit->user->screen_name.'/status/'.$post_twit->id.'">https://twitter.com/'.$post_twit->user->screen_name.'/status/'.$post_twit->id.'</a><br>';
}
else
{
	var_dump($post_twit);
}
}
//---------------------------------------------------------------------------------------------------	
if ($_GET['pinterest']=='true')
{
$TagList=explode(",",htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_social_tags_pinterest','' ) ))));
shuffle($TagList);
$tag_up_limit=rand( get_option( 'wp_video_tags_random_min_pinterest','1'), get_option( 'wp_video_tags_random_max_pinterest','3') );
$tags='';
for($i=0;$i<min(count($TagList),$tag_up_limit); $i++)
{
	$tags=$tags.' #'.trim($TagList[$i]);
}

spl_autoload_register(function($class) {

    // project-specific namespace prefix
    $prefix = 'DirkGroenen\\Pinterest\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/Pinterest/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
$pinterest = new DirkGroenen\Pinterest\Pinterest($client_id, $client_secret);
$pinterest->auth->setOAuthToken(get_option( 'wp_video_pinterest_oauthtoken',''));

$link=site_url().'/';
if (urldecode(get_option( 'wp_video_relative_url','wp-video' ))!='') $link=site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' )).'/';

$post_pinterest= $pinterest->pins->create(array(
    "note"          => $rezultat[0]->title.' '.$tags,
    "image_url"     => site_url().'/wp-admin/admin-ajax.php?action=pinterest_image&yt_id='.$iframe_url,
	"link"     => $link.$rezultat[0]->video_url,
    "board"         => urldecode(get_option( 'wp_video_pinterest_target_board',''))
));

echo 'Pinterest created: <a target="_blank" href="'.$post_pinterest->url.'">'.$post_pinterest->url.'</a><br>';


}
exit();	
}


?>
<html>
<head>
<title>
Syndicate
</title>
</head>
<body>
<div id="s_out"></div>
<div id="timer"></div> 
<div id="main">
<h3>Select social network:</h3>
<?php if (get_option( 'werwerwpfg678_4567_temp_dev','0' ) == '1' ) $develop=true;  ?>
<p><input id="wp_video_tumblr_post_t" <?php if ($develop != true) echo checked  ?> type="checkbox" value="1" name="wp_video_tumblr_post_t"> <label for="wp_video_tumblr_post_t">Tumblr</label></p>
<?php
if ($develop == true)
{
?>
<p><input id="wp_video_twitter_post_t" type="checkbox" value="1" name="wp_video_twitter_post_t"> <label for="wp_video_twitter_post_t">Twitter</label></p>
<p><input id="wp_video_pinterest_post_t" type="checkbox" value="1" name="wp_video_pinterest_post_t"> <label for="wp_video_pinterest_post_t">Pinterest</label></p>
<?php
}
?>
<p><input onclick="document.getElementById('timer').innerHTML='<p><b>Please wait '+i_t.toString()+' seconds.</b></p>';intervalID=setInterval(function(){ work_interval(); }, 1000);document.getElementById('main').style.display='none';return false;"  class="button" type="button" value="Syndicate"></p>
</div>
<script  type="text/javascript">
var intervalID=null;
var ajax = {};
ajax.x = function () {
    if (typeof XMLHttpRequest !== 'undefined') {
        return new XMLHttpRequest();
    }
    var versions = [
        "MSXML2.XmlHttp.6.0",
        "MSXML2.XmlHttp.5.0",
        "MSXML2.XmlHttp.4.0",
        "MSXML2.XmlHttp.3.0",
        "MSXML2.XmlHttp.2.0",
        "Microsoft.XmlHttp"
    ];

    var xhr;
    for (var i = 0; i < versions.length; i++) {
        try {
            xhr = new ActiveXObject(versions[i]);
            break;
        } catch (e) {
        }
    }
    return xhr;
};

ajax.send = function (url, callback, method, data, async) {
    if (async === undefined) {
        async = true;
    }
    var x = ajax.x();
    x.open(method, url, async);
    x.onreadystatechange = function () {
        if (x.readyState == 4) {
            callback(x.responseText)
        }
    };
    if (method == 'POST') {
        x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    }
    x.send(data)
};

ajax.get = function (url, data, callback, async) {
    var query = [];
    for (var key in data) {
        query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
    }
    ajax.send(url + (query.length ? '?' + query.join('&') : ''), callback, 'GET', null, async)
};

ajax.post = function (url, data, callback, async) {
    var query = [];
    for (var key in data) {
        query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
    }
    ajax.send(url, callback, 'POST', query.join('&'), async)
};






function syndicate_posting(id) 
{
	
ajax.get("<?php echo site_url();?>/wp-admin/admin-ajax.php?action=wpvs_syndicate&id="+id+"&tumblr="+document.getElementById('wp_video_tumblr_post_t').checked<?php if ($develop == true){?>+"&twitter="+document.getElementById('wp_video_twitter_post_t').checked+"&pinterest="+document.getElementById('wp_video_pinterest_post_t').checked<?php } ?>, {}, function(response){ document.getElementById('s_out').innerHTML=document.getElementById('s_out').innerHTML+response;},false);

}

function work_interval()
{
	i_t=i_t-1;
    document.getElementById('timer').innerHTML='<p><b>Please wait '+i_t.toString()+' seconds.</b></p>';
	
	if (i_t==0)  {
		i_t=<?php echo get_option( 'wp_video_post_social_post_delay','7' ); ?>;
		
			
			for (var i = i_c; i < checkboxes.length; i++)
			{
        if(checkboxes[i].checked)
			{
			i_c=i+1;
				syndicate_posting(checkboxes[i].value.toString());
				break;
			}
			
			}
			
		if ( i_c==0 || i==checkboxes.length) {if (window.opener != null) {window.opener.document.getElementById("synd_output").innerHTML=document.getElementById('s_out').innerHTML;window.close();} else {clearInterval(intervalID);document.getElementById('timer').innerHTML='<p><b>Finished.</b></p>';}}
		
		
		
		}
		
	
}


			
var checkboxes = window.opener.document.getElementsByName('post[]');

var i_c=0;

var i_t=<?php echo get_option( 'wp_video_post_social_post_delay','7' ); ?>;





</script>
</body>
</html>
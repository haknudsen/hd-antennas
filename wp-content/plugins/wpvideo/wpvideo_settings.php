<style>
 /* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}


</style>

<script type="text/javascript">
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}



function Check_AB() 
{

jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=varticle_builder&category=&keyword=&username="+encodeURIComponent(document.getElementById('wp_video_post_ab_username').value)+"&pass="+encodeURIComponent(document.getElementById('wp_video_post_ab_password').value),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
             //alert(response);
			if (response.indexOf('Invalid username or password') > -1) 
			{alert('Invalid username or password.');}
			else
			{alert('Login OK.');}
			
		}
});

}


function tumblr_log_in() 
{

jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=tumblr_connect&username="+encodeURIComponent(document.getElementById('wp_video_tumblr_consumer').value)+"&pass="+encodeURIComponent(document.getElementById('wp_video_tumblr_secret').value),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
             //alert(response);
			 var winHeight=520;
			 var winWidth=450;
			 var winTop = (screen.height / 2) - (winHeight / 2);
        var winLeft = (screen.width / 2) - (winWidth / 2);
        if (response.substring(0, 4)=='http') 
		{
		window.open(response, 'sharer', 'scrollbars=yes, top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
		}
			else
		{
			alert(response)	;
		}
		}
});

}


function tumblr_logout()
{

jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=tumblr_logout",             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
             //alert(response);
			 //window.location.reload(false);	
		document.getElementById("tumblr_div_out").innerHTML='<input onclick="this.disabled = true;tumblr_log_in();return false;" class="button" type="button" value="Log In">';

		}
});

}


function BestSpinner_test(page)
{
	
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=vbest_spinner&username="+encodeURIComponent(document.getElementById('wp_video_post_bs_username').value)+"&pass="+encodeURIComponent(document.getElementById('wp_video_post_bs_password').value),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			//alert(response);
			if (response.indexOf('Invalid username or password.') > -1) 
			{alert('Invalid username or password.');}
			else
			{alert('Account OK.');}
		
            
        }

    });
}

function spin_rewriter_test(page)
{


jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=vspin_rewriter&username="+encodeURIComponent(document.getElementById('wp_video_post_sr_username').value)+"&pass="+encodeURIComponent(document.getElementById('wp_video_post_sr_password').value),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			//alert(response);
			if (response.indexOf('Authentication failed') > -1) 
			{alert('Invalid username or password.');}
			else
			{alert('Account OK.');}
		
            
        }

    });
}

function AddBanner()
{
var c_text=	encodeURIComponent(document.getElementById("banner_category").value)+'+|+'+document.getElementById("banner_position").value+'+|+'+encodeURIComponent(document.getElementById("site_banner_html").value);
var x = document.getElementById("banners");
var option = document.createElement("option");
//option.setAttribute('onclick','select_child(this);');
option.text = c_text;
x.add(option);	
var x = document.getElementById("banners");
var temp_banner='';
for (i = 0; i < x.length; i++) {
	   if  (temp_banner=='')
	   {  
        temp_banner = x.options[i].text;
	   }
	   else
	   {
		   temp_banner = temp_banner + "\n" + x.options[i].text;
	   }
    }	
document.getElementById("banners_out").value=encodeURIComponent(temp_banner);
	
}
function EditBanner()
{

var x = document.getElementById("banners");
var temp_banner='';
for (i = 0; i < x.length; i++) {
	   if  (temp_banner=='')
	   {  
        temp_banner = x.options[i].text;
	   }
	   else
	   {
		   temp_banner = temp_banner + "\n" + x.options[i].text;
	   }
    }	
document.getElementById("banners_out").value=encodeURIComponent(temp_banner);
	
}
function delBanner()
{
	if (confirm('Remove banner? ')==false) return false;
	var x = document.getElementById("banners");
    x.removeChild(x[x.selectedIndex]);
var x = document.getElementById("banners");
var temp_banner='';
for (i = 0; i < x.length; i++) {
	   if  (temp_banner=='')
	   {  
        temp_banner = x.options[i].text;
	   }
	   else
	   {
		   temp_banner = temp_banner + "\n" + x.options[i].text;
	   }
    }	
document.getElementById("banners_out").value=encodeURIComponent(temp_banner);
}

function banner_html_template(selector)
{
	return true;
	switch (selector.toString())
	{ 
	case '0': 
	document.getElementById('site_banner_html').value='';
	break; 
	case '1': 
	document.getElementById('site_banner_html').value='<div style="height: 360px; width: 160px; overflow:hidden; float:left;position: relative;">\n\n\n</div>';
	break; 
	case '2': 
	document.getElementById('site_banner_html').value='<div style="height: 360px; width: 160px; overflow:hidden; float:right;position: relative;">\n\n\n</div>';
	break; 
	case '3': 
	document.getElementById('site_banner_html').value='<div style="position: relative;height: 260px; width: 960px; overflow:hidden;">\n\n\n</div>';
	break;
	case '4': 
	document.getElementById('site_banner_html').value='<div style="position: relative;height: 260px; width: 960px; overflow:hidden;">\n\n\n</div>';
	break;
	case '5': 
	document.getElementById('site_banner_html').value='<div style="position: relative;height: 260px; width: 960px; overflow:hidden;">\n\n\n</div>';
	break;
	}
}

function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}

jQuery(document).ready(function() {

	
	
window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
//jQuery('#upload_image').val(imgurl);
//alert(document.getElementById('pininterest_link').value.replace('_link_to_image',imgurl));
//document.getElementById('pininterest_link_a').href=document.getElementById('pininterest_link').value.replace('_link_to_image',imgurl);

tb_remove();

if (document.getElementById('media_switch').value=='2')
{
document.getElementById('wp_video_favicon').value=imgurl;	
document.getElementById('media_switch').value=='0';
}
else if (document.getElementById('media_switch').value=='1')
{
document.getElementById('wp_logo_header').value=imgurl;	
document.getElementById('media_switch').value=='0';
}	
else
{	

setTimeout(function() {
	
	document.getElementById('add_baner_button').click();
	document.getElementById('site_banner_html').value=document.getElementById('site_banner_html').value.replace('!from_media_library!','<img src="'+imgurl+'">');
	//window.top.location.href=document.getElementById('add_baner_button').value.replace('_link_to_image',imgurl);
},500);
}
}

});


(function( jQuery ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    jQuery(function() {
        jQuery('.new-color-field').wpColorPicker();
    });
     
})( jQuery );



jQuery(document).ready(function(){
	jQuery('#wp_clear_frontpage').datepicker({ dateFormat : 'yy-mm-dd',
	 onSelect: function(datetext){

        var d = new Date(); // for now

        var h = d.getHours();
        h = (h < 10) ? ("0" + h) : h ;

        var m = d.getMinutes();
        m = (m < 10) ? ("0" + m) : m ;

        var s = d.getSeconds();
        s = (s < 10) ? ("0" + s) : s ;

        datetext = datetext + " " + h + ":" + m + ":" + s;

        jQuery('#wp_clear_frontpage').val(datetext);
    }
	
	
	});	
});
</script>
<?php
if (get_option( 'werwerwpfg678_4567_temp_dev','0' ) == '1' ) $develop=true;
if (isset($_POST["proxies"]))
{

update_option( 'wp_video_post_proxies', urlencode($_POST['proxies']) );
update_option( 'wp_video_about_text', urlencode($_POST['wp_video_about_text']) );
update_option( 'wp_video_menu_text', urlencode($_POST['wp_video_menu_text']) );
update_option( 'wp_video_post_use_proxies', $_POST['use_proxies'] );

update_option( 'wp_home_meta_title', $_POST['wp_home_meta_title'] );
update_option( 'wp_home_meta_description', $_POST['wp_home_meta_description'] );
update_option( 'wp_video_post_ab_superspun', $_POST['wp_video_post_ab_superspun'] );
update_option( 'wp_video_post_ab_wordcount', $_POST['wp_video_post_ab_wordcount'] );


update_option( 'wp_video_relative_url', urlencode($_POST['wp_video_relative_url'] ));


update_option( 'wp_header_script', urlencode($_POST['wp_header_script'] ));
update_option( 'wp_footer_script', urlencode($_POST['wp_footer_script'] ));


$wpvs_id=get_option( 'wp_video_post_id','0' );
if ($wpvs_id!='0')
{	
$my_post = array(

  'ID' => intval($wpvs_id),
  'post_name' => $_POST['wp_video_relative_url']
  
);
 
// Insert the post into the database
wp_update_post( $my_post );
}

update_option( 'wp_clear_frontpage', urlencode($_POST['wp_clear_frontpage'] ));
update_option( 'wp_video_favicon', urlencode($_POST['wp_video_favicon'] ));
update_option( 'wp_video_post_spin_selector', $_POST['spin_selector'] );


update_option( 'wp_video_post_ab_username', $_POST['wp_video_post_ab_username'] );
update_option( 'wp_video_post_ab_password', $_POST['wp_video_post_ab_password'] );
update_option( 'wp_video_post_bs_username', $_POST['wp_video_post_bs_username'] );
update_option( 'wp_video_post_bs_password', $_POST['wp_video_post_bs_password'] );
update_option( 'wp_video_post_sr_username', $_POST['wp_video_post_sr_username'] );
update_option( 'wp_video_post_sr_password', $_POST['wp_video_post_sr_password'] );

update_option( 'wp_video_post_social_post_delay', $_POST['wp_video_post_social_post_delay'] );
update_option( 'wp_video_soc_post_max_c', $_POST['wp_video_soc_post_max_c'] );
update_option( 'wp_video_tumblr_consumer', $_POST['wp_video_tumblr_consumer'] );
update_option( 'wp_video_tumblr_secret', $_POST['wp_video_tumblr_secret'] );
update_option( 'wp_video_tumblr_target_blog', urlencode($_POST['wp_video_tumblr_target_blog'] ));
update_option( 'wp_video_post_social_tags', urlencode($_POST['wp_video_post_social_tags']) );
update_option( 'wp_video_tags_random_min', $_POST['wp_video_tags_random_min']) ;
update_option( 'wp_video_tags_random_max', $_POST['wp_video_tags_random_max']) ;

update_option( 'wp_video_twitter_consumer', trim($_POST['wp_video_twitter_consumer']) );
update_option( 'wp_video_twitter_secret', trim($_POST['wp_video_twitter_secret'] ));
update_option( 'wp_video_twitter_token', trim($_POST['wp_video_twitter_token'] ));
update_option( 'wp_video_twitter_token_secret', trim($_POST['wp_video_twitter_token_secret'] ));
update_option( 'wp_video_post_social_tags_twitter', urlencode($_POST['wp_video_post_social_tags_twitter']) );
update_option( 'wp_video_tags_random_min_twitter', $_POST['wp_video_tags_random_min_twitter']) ;
update_option( 'wp_video_tags_random_max_twitter', $_POST['wp_video_tags_random_max_twitter']) ;





update_option( 'wp_video_pinterest_oauthtoken', $_POST['wp_video_pinterest_oauthtoken'] );
update_option( 'wp_video_pinterest_target_board', urlencode($_POST['wp_video_pinterest_target_board'] ));
update_option( 'wp_video_post_social_tags_pinterest', urlencode($_POST['wp_video_post_social_tags_pinterest']) );
update_option( 'wp_video_tags_random_min_pinterest', $_POST['wp_video_tags_random_min_pinterest']) ;
update_option( 'wp_video_tags_random_max_pinterest', $_POST['wp_video_tags_random_max_pinterest']) ;

update_option( 'wp_video_post_general_sidebar', $_POST['wp_video_post_general_sidebar'] );
update_option( 'wp_video_auto_play', $_POST['video_autoplay'] );
update_option( 'wp_video_yt_description', $_POST['wp_video_yt_description'] );
update_option( 'wp_video_youtube_max_words', $_POST['wp_video_youtube_max_words'] );
update_option( 'wp_video_show_end_text', $_POST['wp_video_show_end_text'] );
update_option( 'wp_video_youtube_end_text', urlencode($_POST['wp_video_youtube_end_text'] ));
update_option( 'wp_color_picker_header', urlencode($_POST['wp_color_picker_header'] ));
update_option( 'wp_logo_header', urlencode($_POST['wp_logo_header'] ));
update_option( 'wp_link_header', urlencode($_POST['wp_link_header'] ));
update_option( 'wp_alt_header', urlencode($_POST['wp_alt_header'] ));
update_option( 'wp_color_picker_menu', urlencode($_POST['wp_color_picker_menu'] ));
update_option( 'wp_color_picker_video_back', urlencode($_POST['wp_color_picker_video_back'] ));
update_option( 'wp_video_post_show_search_box', $_POST['wp_video_post_show_search_box'] );
update_option( 'wp_site_desing_text', urlencode($_POST['wp_site_desing_text'] ));

update_option( 'wp_video_subtitle_language', $_POST['wp_video_subtitle_language'] );
update_option( 'wp_video_yt_description_strip_links', $_POST['wp_video_yt_description_strip_links'] );
update_option( 'wp_video_show_title_of_featured_videos', $_POST['wp_video_show_title_of_featured_videos'] );
update_option( 'wp_video_post_rss_main_feed_climit', $_POST['wp_video_post_rss_main_feed_climit'] );
update_option( 'wp_video_post_max_show_posts', $_POST['wp_video_post_max_show_posts'] );
update_option( 'wp_video_rows_at_home_page', $_POST['wp_video_rows_at_home_page'] );


$temp_banner=urldecode($_POST["banners_out"]);
$temp_banner_a=explode("\n",$temp_banner);


global $wpdb;
$table_name = $wpdb->prefix . "wp_video_banners"; 
$wpdb->delete( $table_name, array( 'type' => 'global' ) );
foreach($temp_banner_a as $value)
{	
$value_a=explode("+|+",$value);



$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'global',
		'category' => $value_a[0],	
		'position' => $value_a[1],
		'html_text' => $value_a[2]
	)
	);
}

}
?>
<h1>WpVideoSites settings</h1>


<div class="tab">
  <button id="tab_first_tab" class="tablinks" onclick="openCity(event, 'Blog')">Blog Settings</button>
  <button class="tablinks" onclick="openCity(event, 'Feed')">Feed Settings</button>
  <button class="tablinks" onclick="openCity(event, 'API')">API & Proxy Details</button>
  <button class="tablinks" onclick="openCity(event, 'Video')">Video Theme Settings</button>
  <button class="tablinks" onclick="openCity(event, 'Subtitles')">Subtitles & Descriptions</button>
  <button class="tablinks" onclick="openCity(event, 'Banners')">Banners & Sidebar</button>
  <button class="tablinks" onclick="openCity(event, 'Syndicate')">Social syndicaton</button>
   <button class="tablinks" onclick="openCity(event, 'About')">About Page</button>
</div>



<form novalidate="novalidate" action="<?php  echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">

<div id="Blog" class="tabcontent">
<table class="form-table">
<tbody>

<tr>
<th scope="row">
<label for="sites">Home relative url:</label>
</th>
<td>
<input name="wp_video_relative_url" id="wp_video_relative_url" type="text" value="<?php echo urldecode(get_option( 'wp_video_relative_url','wp-video' )); ?>"> <a target="_blank" href="<?php echo site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' ));?>"><?php echo site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' ));?></a>
</td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_favicon">Favicon:</label>
</th>
<td>
<input name="wp_video_favicon" id="wp_video_favicon" type="text" value="<?php echo urldecode(get_option( 'wp_video_favicon',urlencode(site_url()).'/wp-content%2Fplugins%2Fwpvideo%2Ftheme%2Finclude%2Fvideoicon.png' )); ?>"><button onclick="document.getElementById('media_switch').value='2';tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');return false;" type="button" id="insert-media-button" class="button insert-media add_media" ><span class="wp-media-buttons-icon"></span> Add Media</button>

</td>
</tr>

<tr>
<th scope="row">
<label for="sites">Home title:</label>
</th>
<td>
<input name="wp_home_meta_title" id="wp_home_meta_title" type="text" value="<?php echo urldecode(get_option( 'wp_home_meta_title','' )); ?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="sites">Home meta description:</label>
</th>
<td>
<textarea style="height:150px ; width:300px;" name="wp_home_meta_description" id="wp_home_meta_description"><?php echo urldecode(get_option( 'wp_home_meta_description','' )); ?></textarea> 
</td>
</tr>

<tr>
<th scope="row">
<label for="sites">Video autoplay:</label>
</th>
<td>
<input id="video_autoplay" type="checkbox" value="1" name="video_autoplay" <?php if (get_option( 'wp_video_auto_play','0' )=="1") echo "checked";  ?>>
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_clear_frontpage">Clear frontpage priority date:</label>
</th>
<td>
<input class="wp_clear_frontpage" name="wp_clear_frontpage" id="wp_clear_frontpage" type="text" value="<?php echo urldecode(get_option( 'wp_clear_frontpage',date('Y-m-d H:i:s'))); ?>">
</td>
</tr>


<tr>
<th scope="row">
<label for="sites">Footer Text:</label>
</th>
<td>
<input name="wp_site_desing_text" id="wp_site_desing_text" type="text" value="<?php echo urldecode(get_option( 'wp_site_desing_text','Site Design by: wpvideosites' )); ?>">
</td>
</tr>



<tr>
<th scope="row">
<label for="wp_video_menu_text">Menu (name||link):</label>
</th>
<td>
<textarea style="height:300px ; width:300px;" id="wp_video_menu_text" name="wp_video_menu_text"><?php echo htmlentities(stripslashes(urldecode(get_option( 'wp_video_menu_text','About||page-about' ) )));?></textarea></td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_show_title_of_featured_videos">Show title of featured videos:</label>
</th>
<td>
<input id="wp_video_show_title_of_featured_videos" type="checkbox" value="1" name="wp_video_show_title_of_featured_videos" <?php if (get_option( 'wp_video_show_title_of_featured_videos','0' )=="1") echo "checked";  ?>>
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_header_script">Header script:</label>
</th>
<td>
<textarea style="height:150px ; width:300px;" name="wp_header_script" id="wp_header_script"><?php echo stripslashes(urldecode(get_option( 'wp_header_script','%3Cscript%20type%3D%22text%2Fjavascript%22%3E%0D%0A%3C%2Fscript%3E' ))); ?></textarea> 
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_footer_script">Footer script:</label>
</th>
<td>
<textarea style="height:150px ; width:300px;" name="wp_footer_script" id="wp_footer_script"><?php echo stripslashes(urldecode(get_option( 'wp_footer_script','%3Cscript%20type%3D%22text%2Fjavascript%22%3E%0D%0A%3C%2Fscript%3E' ))); ?></textarea> 
</td>
</tr>

</table>
</div>
<div id="Feed" class="tabcontent">
<table class="form-table">

<tr>
<th scope="row">
<label for="sites">RSS main feed link:</label>
</th>
<td>
<a target="_blank" href="<?php echo site_url();?>?feed=video-feed"><?php echo site_url();?>?feed=video-feed</a>
</td>
</tr>
<tr>
<th scope="row">
<label for="sites">Video sitemap:</label>
</th>
<td>
<a target="_blank" href="<?php echo site_url();?>?feed=video-sitemap"><?php echo site_url();?>?feed=video-sitemap</a>
</td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_post_rss_main_feed_climit">RSS main feed character limit per post (0 - unlimited):</label>
</th>
<td>
<input value="<?php echo get_option( 'wp_video_post_rss_main_feed_climit','0' ); ?>" id="wp_video_post_rss_main_feed_climit" name="wp_video_post_rss_main_feed_climit" type="text">
</td>
</tr>
<tr>
<th scope="row">
<label for="sites">Max show posts in RSS feed:</label>
</th>
<td>
<input value="<?php echo get_option( 'wp_video_post_max_show_posts','30' ); ?>" id="wp_video_post_max_show_posts" name="wp_video_post_max_show_posts" type="text">
</td>
</tr>

</table>
</div>
<div id="API" class="tabcontent">
<table class="form-table">

<tr>
<th scope="row">
<label for="sites">Article builder API:</label>
<p><a href="http://abbasravji.com/articlebuilder" target="_blank">Get Article Builder Here</a></p>
</th>
<td>
<table border="0">
<tr><td style="padding: 0px">Username:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_post_ab_username','' ) ;?>" id="wp_video_post_ab_username" name="wp_video_post_ab_username" type="text"></td></tr>
<tr><td style="padding: 0px">Password:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_post_ab_password','' ) ;?>" id="wp_video_post_ab_password" name="wp_video_post_ab_password" type="password"></td></tr>
<tr><td style="padding: 0px"></td><td style="padding: 0px"><input onclick="this.disabled = true;this.value='Please wait ...';Check_AB();this.value='Test account';this.disabled = false;return false;" class="button" type="button" value="Test account"></td></tr>
<tr><td style="padding: 0px">Wordcount:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_post_ab_wordcount','500' ) ;?>" id="wp_video_post_ab_wordcount" name="wp_video_post_ab_wordcount" type="text">(min = 300, max = 1000)</td></tr>
<tr><td style="padding: 0px">Superspun:</td><td style="padding: 0px">
<select id="wp_video_post_ab_superspun" name="wp_video_post_ab_superspun">
<option <?php if (get_option( 'wp_video_post_ab_superspun','1' )=='1') echo 'selected';?> value="1">Super Spun content</option>
<option <?php if (get_option( 'wp_video_post_ab_superspun','1' )=='2') echo 'selected';?> value="2">Expanded Super Spun Content</option>
<option <?php if (get_option( 'wp_video_post_ab_superspun','1' )=='0') echo 'selected';?> value="0">unspun content</option>

</select>
</td></tr>

</table>
</td>
</tr>


<tr>

<th scope="row">
<input id="spin_selector_0" <?php if (get_option( 'wp_video_post_spin_selector','0' )=="0") echo "checked";  ?> value="0" type="radio" name="spin_selector"><label for="spin_selector_0">Spin Rewriter API :</label>
<p><a href="http://abbasravji.com/spinrewriter" target="_blank">Get Spin rewriter Here</a></p>
</th>
<td>
<table border="0">
<tr><td style="padding: 0px">Email:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_post_sr_username','' ); ?>" id="wp_video_post_sr_username" name="wp_video_post_sr_username" type="text"></td></tr>
<tr><td style="padding: 0px">API key:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_post_sr_password','' ); ?>" id="wp_video_post_sr_password" name="wp_video_post_sr_password" type="password"></td></tr>
<tr><td style="padding: 0px"></td><td style="padding: 0px"><input onclick="this.disabled = true;this.value='Please wait ...';spin_rewriter_test();this.value='Test account';this.disabled = false;return false;" class="button" type="button" value="Test account"></td></tr>

</table>
</td>
</tr>

<tr>
<th scope="row">
<input id="spin_selector_1" <?php  if (get_option( 'wp_video_post_spin_selector','0' )=="1") echo "checked";  ?> value="1" type="radio" name="spin_selector"><label for="spin_selector_1">Best Spinner API :</label>
<p><a href="http://abbasravji.com/thebestspinner" target="_blank">Get The Best Spinner Here</a></p>
</th>
<td>
<table border="0">
<tr><td style="padding: 0px">Username:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_post_bs_username','' ); ?>" id="wp_video_post_bs_username" name="wp_video_post_bs_username" type="text"></td></tr>
<tr><td style="padding: 0px">Password:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_post_bs_password','' ); ?>" id="wp_video_post_bs_password" name="wp_video_post_bs_password" type="password"></td></tr>
<tr><td style="padding: 0px"></td><td style="padding: 0px"><input onclick="this.disabled = true;this.value='Please wait ...';BestSpinner_test();this.value='Test account';this.disabled = false;return false;" class="button" type="button" value="Test account"></td></tr>
</table>
</td>
</tr>
<tr>
<th scope="row">
<input <?php  if (get_option( 'wp_video_post_use_proxies','0' )=="1") echo "checked";  ?> id="use_proxies" name="use_proxies" type="checkbox" value="1"><label for="use_proxies">Use proxies:</label>
<p><a href="http://abbasravji.com/elite-proxies" target="_blank">Get Private Proxies Here</a></p>
</th>
<td>
<textarea style="height:300px ; width:300px;" id="proxies" name="proxies"><?php echo htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_proxies','' ) )));?></textarea>
</td>
</tr>

</table>
</div>
<div id="Video" class="tabcontent">
<table class="form-table">


<tr>
<th scope="row">
<label for="wp_video_rows_at_home_page">Rows at home page:</label>
</th>
<td>
<input value="<?php echo get_option( 'wp_video_rows_at_home_page','3' ); ?>" id="wp_video_rows_at_home_page" name="wp_video_rows_at_home_page" type="text">
</td>
</tr>
<tr>
<th scope="row">
<label for="sites">Header logo:</label>
</th>
<td>
<input name="wp_logo_header" id="wp_logo_header" type="text" value="<?php echo urldecode(get_option( 'wp_logo_header',urlencode(site_url()).'/wp-content%2Fplugins%2Fwpvideo%2Ftheme%2Finclude%2Fgallery_video.png' )); ?>"><button onclick="document.getElementById('media_switch').value='1';tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');return false;" type="button" id="insert-media-button" class="button insert-media add_media" ><span class="wp-media-buttons-icon"></span> Add Media</button>
<input type="hidden" id="media_switch" value="0">
</td>
</tr>

<tr>
<th scope="row">
<label for="sites">Header link:</label>
</th>
<td>
<input name="wp_link_header" id="wp_link_header" type="text" value="<?php echo urldecode(get_option( 'wp_link_header',urlencode(site_url()).'/index.php%2F'.get_option( 'wp_video_relative_url','wp-video' ).'%2F' )); ?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_alt_header">Header alt:</label>
</th>
<td>
<input name="wp_alt_header" id="wp_alt_header" type="text" value="<?php echo urldecode(get_option( 'wp_alt_header','')); ?>">
</td>
</tr>


<tr>
<th scope="row">
<label for="sites">Header color:</label>
</th>
<td>
<input name="wp_color_picker_header" id="wp_color_picker_header" type="text" class="new-color-field" value="<?php echo urldecode(get_option( 'wp_color_picker_header','%231f43d3' )); ?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="sites">Menu color:</label>
</th>
<td>
<input name="wp_color_picker_menu" id="wp_color_picker_menu" type="text" class="new-color-field" value="<?php echo urldecode(get_option( 'wp_color_picker_menu','%23ed9136' )); ?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="sites">Video background color:</label>
</th>
<td>
<input name="wp_color_picker_video_back" id="wp_color_picker_video_back" type="text" class="new-color-field" value="<?php echo urldecode(get_option( 'wp_color_picker_video_back','%23333333' )); ?>">
</td>
</tr>


<tr>
<th scope="row">
<label for="wp_video_post_show_search_box">Show search box:</label>
</th>
<td>
<input id="wp_video_post_show_search_box" type="checkbox" value="1" name="wp_video_post_show_search_box" <?php if (get_option( 'wp_video_post_show_search_box','1' )=="1") echo "checked";  ?>>
</td>
</tr>

</table>
</div>
<div id="Subtitles" class="tabcontent">
<table class="form-table">
<tr>
<th scope="row">
<label for="wp_video_yt_description">YouTube subtitle forced language:</label>

</th>
<td>

<select id="wp_video_subtitle_language" name="wp_video_subtitle_language" style="width: 89px;"><option value="---" disabled="">---------------</option><option value="af">Afrikaans</option><option value="sq">Albanian</option><option value="am">Amharic</option><option value="ar">Arabic</option><option value="hy">Armenian</option><option value="az">Azerbaijani</option><option value="bn">Bangla</option><option value="eu">Basque</option><option value="be">Belarusian</option><option value="bs">Bosnian</option><option value="bg">Bulgarian</option><option value="my">Burmese</option><option value="ca">Catalan</option><option value="ceb">Cebuano</option><option value="zh-CN">Chinese (Simplified)</option><option value="zh-TW">Chinese (Traditional)</option><option value="co">Corsican</option><option value="hr">Croatian</option><option value="cs">Czech</option><option value="da">Danish</option><option value="nl">Dutch</option><option value="en">English</option><option value="eo">Esperanto</option><option value="et">Estonian</option><option value="tl">Filipino</option><option value="fi">Finnish</option><option value="fr">French</option><option value="gl">Galician</option><option value="ka">Georgian</option><option value="de">German</option><option value="el">Greek</option><option value="gu">Gujarati</option><option value="ht">Haitian Creole</option><option value="ha">Hausa</option><option value="haw">Hawaiian</option><option value="iw">Hebrew</option><option value="hi">Hindi</option><option value="hmn">Hmong</option><option value="hu">Hungarian</option><option value="is">Icelandic</option><option value="ig">Igbo</option><option value="id">Indonesian</option><option value="ga">Irish</option><option value="it">Italian</option><option value="ja">Japanese</option><option value="jv">Javanese</option><option value="kn">Kannada</option><option value="kk">Kazakh</option><option value="km">Khmer</option><option value="ko">Korean</option><option value="ku">Kurdish</option><option value="ky">Kyrgyz</option><option value="lo">Lao</option><option value="la">Latin</option><option value="lv">Latvian</option><option value="lt">Lithuanian</option><option value="lb">Luxembourgish</option><option value="mk">Macedonian</option><option value="mg">Malagasy</option><option value="ms">Malay</option><option value="ml">Malayalam</option><option value="mt">Maltese</option><option value="mi">Maori</option><option value="mr">Marathi</option><option value="mn">Mongolian</option><option value="ne">Nepali</option><option value="no">Norwegian</option><option value="ny">Nyanja</option><option value="ps">Pashto</option><option value="fa">Persian</option><option value="pl">Polish</option><option value="pt">Portuguese</option><option value="pa">Punjabi</option><option value="ro">Romanian</option><option value="ru">Russian</option><option value="sm">Samoan</option><option value="gd">Scottish Gaelic</option><option value="sr">Serbian</option><option value="sn">Shona</option><option value="sd">Sindhi</option><option value="si">Sinhala</option><option value="sk">Slovak</option><option value="sl">Slovenian</option><option value="so">Somali</option><option value="st">Southern Sotho</option><option value="es">Spanish</option><option value="su">Sundanese</option><option value="sw">Swahili</option><option value="sv">Swedish</option><option value="tg">Tajik</option><option value="ta">Tamil</option><option value="te">Telugu</option><option value="th">Thai</option><option value="tr">Turkish</option><option value="uk">Ukrainian</option><option value="ur">Urdu</option><option value="uz">Uzbek</option><option value="vi">Vietnamese</option><option value="cy">Welsh</option><option value="fy">Western Frisian</option><option value="xh">Xhosa</option><option value="yi">Yiddish</option><option value="yo">Yoruba</option><option value="zu">Zulu</option></select>
</td>
</tr>
<?php
if ($develop==true)
{
?>
<tr>
<th scope="row">
<label for="wp_video_yt_description_strip_links">YouTube description strip links:</label>
</th>
<td>
<input id="wp_video_yt_description_strip_links" type="checkbox" value="1" name="wp_video_yt_description_strip_links" <?php if (get_option( 'wp_video_yt_description_strip_links','0' )=="1") echo "checked";  ?>>
</td>
</tr>
<?php
}
?>

<tr>
<th scope="row">
<label for="wp_video_yt_description">YouTube description/subtitle border html:</label>

</th>
<td>
<textarea style="height:300px ; width:300px;" id="wp_video_yt_description" name="wp_video_yt_description"><?php echo htmlentities(stripslashes(urldecode(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ) )));?></textarea>
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_youtube_max_words">YouTube subtitle max number of words(0 - unlimited):</label>
</th>
<td>
<input value="<?php echo get_option( 'wp_video_youtube_max_words','0' ); ?>" id="wp_video_youtube_max_words" name="wp_video_youtube_max_words" type="text">
</td>
</tr>
<?php
if (true==true)
{
?>
<tr>
<th scope="row">
<label for="wp_video_show_end_text">Show subtitle/description end text:</label>
</th>
<td>
<input id="wp_video_show_end_text" type="checkbox" value="1" name="wp_video_show_end_text" <?php if (get_option( 'wp_video_show_end_text','1' )=="1") echo "checked";  ?>>
</td>
</tr>


<tr>
<th scope="row">
<label for="wp_video_youtube_end_text">YouTube subtitle/description end text:</label>
</th>
<td>
<input value="<?php echo urldecode(get_option( 'wp_video_youtube_end_text','See%20more%20here%3A' )); ?>" id="wp_video_youtube_end_text" name="wp_video_youtube_end_text" type="text">
</td>
</tr>
<?php
}
?>
</table>
</div>
<div id="Banners" class="tabcontent">
<table class="form-table">


<tr>
<th scope="row">
<label for="wp_video_post_general_sidebar">General sidebar:</label>

</th>
<td>
<textarea style="height:300px ; width:300px;" id="wp_video_post_general_sidebar" name="wp_video_post_general_sidebar"><?php echo htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_general_sidebar','%3Ch2%20align%3D%22center%22%3EThis%20Is%20The%20Sidebar%3C%2Fh2%3E%3Cbr%3E%0A%3Cimg%20src%3D%22'.urlencode(plugin_dir_url(__FILE__)).'theme%2Fimg%2F220600.png%22%3E%09%09' ) )));?></textarea>
</td>
</tr>

<tr>
<th scope="row">
<label for="use_proxies">General banners:</label>
</th>
<td>
<a style="display: none;" id="click_temp" onclick="insertAtCursor(document.getElementById('site_banner_html'),'!from_media_library!'); document.getElementById('TB_closeWindowButton').click();setTimeout(function () {tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true')},500);return false;" href="http://pinterest.com/pin/create/button/?url=http://147.91.204.66/wordpress/index.php/2015/12/18/iopiup/&amp;media=_link_to_image&amp;description=%28Post%29+iopiup+%26%238211%3B+test" target="_blank" title="Pin it"><img src="http://147.91.204.66/wordpress/wp-content/plugins/wpanalyst/img/sharer/Pinterest.png"></a>

<div style="display:none;" id="banners_add_div">
<table class="form-table">
<tbody>
<tr>
</tr>
<tr>
<th scope="row">
<label for="banner_category">Category:</label>
</th>
<td>
<select id="banner_category">
<option>
General
</option>
<?php
global $wpdb;
$table_name = $wpdb->prefix . "wp_video";
$rezultat_category= $wpdb->get_results( "SELECT DISTINCT(category) FROM ".$table_name." ORDER BY category DESC " );  
foreach ($rezultat_category as $value_category) 
{
echo '<option>'.$value_category->category.'</option>';
}
?>
</select>
</td>
</tr>
<tr>
<th scope="row">
<label for="banner_position">Position:</label>
</th>
<td>
<select id="banner_position" onchange="banner_html_template(this.selectedIndex);">
<option>
Header banner
</option>
<option>
Video Left Banner
</option>
<option>
Video Right Banner
</option>
<option >
Above title banner
</option>
<option>
Inline article banner
</option>
<option>
Side article banner
</option>
<option>
Below article banner
</option>
<option>
Mobile banner
</option>
</select>
</td>
</tr>
<tr>
<th scope="row">
<label for="site_banner_html">HTML:</label>
</th>
<td>
<textarea name="site_banner_html" style="height:250px ;width: 400px;" id="site_banner_html"></textarea>
<p>
<input type="button" class="button"  onclick="document.getElementById('click_temp').click();" value="Add from media library"></input>
</p>
</td>
</tr>
</tbody></table>
<input type="button" id="site_import_button" class="button" onclick="AddBanner();document.getElementById('TB_closeWindowButton').click();" value="Add banner">
<input type="button" class="button" onclick="document.getElementById('site_keyword_import_div').style='display:none;';document.getElementById('TB_closeWindowButton').click();" value="Cancel">
<br><br>
</div>
<div style="display:none;" id="banners_edit_div">

<table class="form-table">
<tbody>
<tr>
<th scope="row">
<label for="site_banner_html_edit">HTML:</label>
</th>
<td>
<textarea name="site_banner_html_edit" style="height:250px ;width: 400px;" id="site_banner_html_edit"></textarea>
</td>
</tr>
</tbody></table>
<input type="button" id="site_edit_button" class="button" onclick="var x = document.getElementById('banners');x.childNodes[x.selectedIndex+1].innerHTML=x.childNodes[x.selectedIndex+1].innerHTML.split('+|+')[0]+'+|+'+x.childNodes[x.selectedIndex+1].innerHTML.split('+|+')[1]+'+|+'+encodeURIComponent(document.getElementById('site_banner_html_edit').value);EditBanner();document.getElementById('TB_closeWindowButton').click();" value="Save">
<input type="button" class="button" onclick="document.getElementById('TB_closeWindowButton').click();" value="Cancel">
<br><br>
</div>
<?php add_thickbox(); ?>
<div class="postbox" style="display:inline-block;padding:5px;width:50%;">

<table style="width:100% !important;">
<tr><td>
<?php
global $wpdb;
$table_name = $wpdb->prefix . "wp_video_banners";
$rezultat_banners= $wpdb->get_results( "SELECT id,type,category,position,html_text FROM ".$table_name."  WHERE type='global' ORDER BY id " );
$temp_banner="";
$temp_banner_option="";
foreach ($rezultat_banners as $value) 
{
	if ($temp_banner=="") 
	{
		$temp_banner = $value->category.'+|+'.$value->position.'+|+'.$value->html_text; 
		$temp_banner_option="<option>".$value->category.'+|+'.$value->position.'+|+'.$value->html_text."</option>";
	}
	else 
	{	
     $temp_banner = $temp_banner."\n".$value->category.'+|+'.$value->position.'+|+'.$value->html_text;
	 $temp_banner_option=$temp_banner_option."<option>".$value->category.'+|+'.$value->position.'+|+'.$value->html_text."</option>";
	}
}
?>
<input name="banners_out" id="banners_out" type="hidden" value="<?php echo urlencode($temp_banner); ?>">
<select name="banners" id="banners" size="4" name="decision2" style="height:100px; width:100% !important;">
<?php echo $temp_banner_option; ?>
</select>
</td>
</table>
<br>
<a id="add_baner_button" onclick=";" href="#TB_inline&?width=750&height=500&inlineId=banners_add_div" class="thickbox"><input onclick="document.getElementById('site_banner_html').value=document.getElementById('site_banner_html').value.replace('!from_media_library!','');" class="button" type="button"  value="Add Banner"></a>
<a onclick="var x = document.getElementById('banners');document.getElementById('site_banner_html_edit').value=decodeURIComponent(x.childNodes[x.selectedIndex+1].innerHTML.split('+|+')[2]);" href="#TB_inline&?width=750&height=500&inlineId=banners_edit_div" class="thickbox"><input class="button" type="button"  value="Edit Banner"></a>
<input class="button" type="button" onclick="delBanner();return false;" value="Del Banner">
</div>
</div>	</td>
</tr>

</tbody>
</table>
</div>
<div id="Syndicate" class="tabcontent">
<table class="form-table">
<tr>
<th scope="row">
<label for="wp_video_post_social_post_delay">Posting delay</label>
</th>
<td>
<input style="width:50px" id="wp_video_post_social_post_delay" name="wp_video_post_social_post_delay" type="text" value="<?php echo get_option( 'wp_video_post_social_post_delay','7' );?>"> seconds
</td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_soc_post_max_c">Posting max charecters (0 - unlimited)</label>
</th>
<td>
<input style="width:50px" id="wp_video_soc_post_max_c" name="wp_video_soc_post_max_c" type="text" value="<?php echo get_option( 'wp_video_soc_post_max_c','0' );?>"> 
</td>
</tr>

<tr>
<td colspan="2"><hr></td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_tumblr_consumer"><u>Tumblr API :</u></label>
<p><a href="https://www.tumblr.com/oauth/apps" target="_blank">Get Tumblr API data Here</a></p>
</th>
<td>
<table border="0">
<tr><td style="padding: 0px">OAuth Consumer Key:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_tumblr_consumer','' ); ?>" id="wp_video_tumblr_consumer" name="wp_video_tumblr_consumer" type="text"></td></tr>
<tr><td style="padding: 0px">Secret Key:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_tumblr_secret','' ); ?>" id="wp_video_tumblr_secret" name="wp_video_tumblr_secret" type="password"></td></tr>
<tr><td style="padding: 0px"></td><td id="tumblr_div_out" style="padding: 0px">
<?php
if (get_option( 'wp_video_tumblr_oauth_token','' )=='')
{
?>
<input onclick="this.disabled = true;this.value='Please wait ...';tumblr_log_in();this.value='Log In';this.disabled = false;return false;" class="button" type="button" value="Log In">
<?php
}
else
{
	echo 'Loged in: '.get_option( 'wp_video_tumblr_oauth_token','').' ';
?>
<input onclick="this.disabled = true;this.value='Please wait ...';tumblr_logout();this.value='Logout';this.disabled = false;return false;" class="button" type="button" value="Logout">
<?php
}
?>
</td></tr>
</table>
</td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_tumblr_target_blog">Tumblr target blog:</label>
</th>
<td>
<input name="wp_video_tumblr_target_blog" id="wp_video_tumblr_target_blog" type="text" value="<?php echo urldecode(get_option( 'wp_video_tumblr_target_blog','')); ?>">
</td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_post_social_tags">Tags (comma separated)</label>
</th>
<td>
<input style="width:300px" id="wp_video_post_social_tags" name="wp_video_post_social_tags" type="text" value="<?php echo htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_social_tags','' ) )));?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_tags_random_min">Tags random min-max:</label>
</th>
<td>
<input style="width:40px;" name="wp_video_tags_random_min" id="wp_video_tags_random_min" type="text" value="<?php echo get_option( 'wp_video_tags_random_min','1'); ?>">-<input style="width:40px;" name="wp_video_tags_random_max" id="wp_video_tags_random_max" type="text" value="<?php echo get_option( 'wp_video_tags_random_max','3'); ?>">
</td>
</tr>
<?php
if ($develop==true)
{
?>
<tr>
<td colspan="2"><hr></td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_tumblr_consumer"><u>Twitter API :</u></label>
<p><a href="https://apps.twitter.com/app/new" target="_blank">Get Twitter API data Here</a></p>
<p><a href="https://cards-dev.twitter.com/validator" target="_blank">Validate for twitter card</a></p>
</th>
<td>
<table border="0">
<tr><td style="padding: 0px">Consumer Key:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_twitter_consumer','' ); ?>" id="wp_video_twitter_consumer" name="wp_video_twitter_consumer" type="text"></td></tr>
<tr><td style="padding: 0px">Consumer Secret:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_twitter_secret','' ); ?>" id="wp_video_twitter_secret" name="wp_video_twitter_secret" type="password"></td></tr>
<tr><td style="padding: 0px">Access Token:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_twitter_token','' ); ?>" id="wp_video_twitter_token" name="wp_video_twitter_token" type="text"></td></tr>
<tr><td style="padding: 0px">Access Token Secret:</td><td style="padding: 0px"><input value="<?php echo get_option( 'wp_video_twitter_token_secret','' ); ?>" id="wp_video_twitter_token_secret" name="wp_video_twitter_token_secret" type="password"></td></tr>

</table>
</td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_post_social_tags_twitter">Tags (comma separated)</label>
</th>
<td>
<input style="width:300px" id="wp_video_post_social_tags_twitter" name="wp_video_post_social_tags_twitter" type="text" value="<?php echo htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_social_tags_twitter','' ) )));?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_tags_random_min_twitter">Tags random min-max:</label>
</th>
<td>
<input style="width:40px;" name="wp_video_tags_random_min_twitter" id="wp_video_tags_random_min_twitter" type="text" value="<?php echo get_option( 'wp_video_tags_random_min_twitter','1'); ?>">-<input style="width:40px;" name="wp_video_tags_random_max_twitter" id="wp_video_tags_random_max_twitter" type="text" value="<?php echo get_option( 'wp_video_tags_random_max_twitter','3'); ?>">
</td>
</tr>
<tr>
<td colspan="2"><hr></td>
</tr>
<tr>
<th scope="row">
<label for="wp_video_tumblr_consumer"><u>Pinterest API:</u></label>
<p><a href="https://developers.pinterest.com/tools/access_token/" target="_blank">Get Pinterest API data Here</a></p>
</th>
<td>
<table border="0">
<tr><td style="padding: 0px">Access token:</td><td style="padding: 0px">
<input name="wp_video_pinterest_oauthtoken" id="wp_video_pinterest_oauthtoken" type="text" value="<?php echo get_option( 'wp_video_pinterest_oauthtoken',''); ?>">
</td></tr>
</table>
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_tumblr_target_blog">Pinterest target board:</label>
</th>
<td>
<input name="wp_video_pinterest_target_board" id="wp_video_pinterest_target_board" type="text" value="<?php echo urldecode(get_option( 'wp_video_pinterest_target_board','')); ?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_post_social_tags_pinterest">Tags (comma separated)</label>
</th>
<td>
<input style="width:300px" id="wp_video_post_social_tags_pinterest" name="wp_video_post_social_tags_pinterest" type="text" value="<?php echo htmlentities(stripslashes(urldecode(get_option( 'wp_video_post_social_tags_pinterest','' ) )));?>">
</td>
</tr>

<tr>
<th scope="row">
<label for="wp_video_tags_random_min_pinterest">Tags random min-max:</label>
</th>
<td>
<input style="width:40px;" name="wp_video_tags_random_min_pinterest" id="wp_video_tags_random_min_pinterest" type="text" value="<?php echo get_option( 'wp_video_tags_random_min_pinterest','1'); ?>">-<input style="width:40px;" name="wp_video_tags_random_max_pinterest" id="wp_video_tags_random_max_pinterest" type="text" value="<?php echo get_option( 'wp_video_tags_random_max_pinterest','3'); ?>">
</td>
</tr>
<?php
}
?>
</table>
</div>
<div id="About" class="tabcontent">
<table class="form-table">

<tr>
<th scope="row">
<label for="wp_video_about_text">About text:</label>
</th>
<td>

<?php

if (class_exists('_WP_Editors'))
{	
$reflector = new ReflectionClass('_WP_Editors');
if (strpos($reflector->getFileName(),'wp-includes/class-wp-editor.php') or strpos($reflector->getFileName(),'wp-includes\class-wp-editor.php')) 
{	
$settings = array('textarea_name'=>'wp_video_about_text','textarea_rows' => 15);
add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );

}
else
{
$settings = array(
    'textarea_name'=>'wp_video_about_text',
    'wpautop' => true,
    'media_buttons' => true,
    'textarea_rows' => 15,
    'tabindex' => '',
    'tabfocus_elements' => ':prev,:next', 
    'editor_css' => '', 
    'editor_class' => '',
    'teeny' => false,
    'dfw' => false,
    'tinymce' => false, // <-----
    'quicktags' => true
);
}
}
else
{
	$settings = array('textarea_name'=>'wp_video_about_text','textarea_rows' => 15);
}
	wp_editor( htmlspecialchars_decode(htmlentities(stripslashes(urldecode(get_option( 'wp_video_about_text','This is about page !' ) )))), "wp_video_about_text_editor", $settings  );

?>

</td>
</tr>

</table>
</div>

<p class="submit">
<input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit">
</p>
</form>
<script>
document.getElementById('tab_first_tab').click();

jQuery(document).ready(function() {
    jQuery('select[name^="wp_video_subtitle_language"] option[value="<?=get_option( 'wp_video_subtitle_language','en' ); ?>"]').attr("selected","selected");
});

</script>
<?php
if ((get_option( 'werwerwpfg678_4567_temp_dev','0' ) == '0') and (get_option( 'werwerwpfg678_4567_temp','0' ) == '1') ) {echo '<table class="form-table">
<tbody><tr>
<th scope="row"><label>Upgrade to Enterprise licence:</label></th>
<td><td><iframe id="search_keyword"  style="width: 700px; height: 500px;" frameborder="0" src="../wp-admin/admin-ajax.php?action=wpvideo_authorise" ></iframe></td>
</tr></tbody>
</table>';}
?>


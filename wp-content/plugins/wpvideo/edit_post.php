<?php

if (isset($_POST['child_post_title']))
{

	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video";
$time_string=$_POST["mm_child"].":".$_POST["jj_child"].":".$_POST["aa_child"].":".$_POST["hh_child"].":".$_POST["mn_child"];
$time_array=explode(':',$time_string);
	$wpdb->update(
		$table_name, 
		array( 
          	'category' => $_POST['child_post_category'],
			'video_url' => stringURLSafe($_POST['child_post_link']),
			'title' => stripslashes(htmlspecialchars($_POST['child_post_title'])),
			'meta_description' => stripslashes(htmlspecialchars($_POST['child_post_meta-description'])),
			'article_text' => str_replace('page&p=','page&amp;p=',stripslashes(html_entity_decode(urldecode($_POST['child1_ready'])))),
            'date' => strtotime($time_array[1].'-'.$time_array[0].'-'.$time_array[2].' '.$time_array[3].':'.$time_array[4])	
		),
		array( 'id' => $_GET['id'])
	);
	

//----------------------------------------------

$table_name = $wpdb->prefix . "wp_video_banners"; 
$wpdb->delete( $table_name, array( 'type' => 'individual','category' => $_GET['id'] ) );
	
$temp_banner=urldecode($_POST['banners_out']);
$temp_banner_a=explode("\n",$temp_banner);


$table_name = $wpdb->prefix . "wp_video_banners"; 
foreach($temp_banner_a as $value)
{	
if ($value!='') 
{	
$value_a=explode("+|+",$value);



$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'individual',
		'category' => $_GET['id'],	
		'position' => $value_a[0],
		'html_text' => $value_a[1]
	)
	);
}
}	
	//================	
	
	
}	



global $wpdb;
	$table_name = $wpdb->prefix . "wp_video";
	$rezultat= $wpdb->get_results( "SELECT id,keyword,video_url,title,meta_description,article_text,category,featured,date FROM ".$table_name." WHERE id = ".$_GET['id'] );
	
?>
<script  type="text/javascript">
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
setTimeout(function() {
	
	document.getElementById('add_baner_button').click();
	document.getElementById('site_banner_html').value=document.getElementById('site_banner_html').value.replace('!from_media_library!','<img src="'+imgurl+'">');
	//window.top.location.href=document.getElementById('add_baner_button').value.replace('_link_to_image',imgurl);
},500);
}

});
</script>
<div>
<h2>Edit post:</h2>
<br><br>
<b>Category:</b><br>
<input type="text" autocomplete="off" spellcheck="true" value="<?php echo htmlspecialchars_decode($rezultat[0]->category); ?>" size="30" id="child_post_category" name="child_post_category"> 
<select onchange="document.getElementById('child_post_category').value=this.value;">
 <!--
 if (document.getElementById('child_post_category').value=='new_category_name_input') {c_text=prompt('Please enter new category name', ''); if (c_text!=null && c_text!='') document.getElementById('child_post_category').value=c_text ;return false;} 
 
 <option value="new_category_name_input">Add new category</option>
 -->
 <option value=""></option>
 <?php
 global $wpdb;
$table_name = $wpdb->prefix . "wp_video";
$rezultat_category= $wpdb->get_results( "SELECT DISTINCT(category) FROM ".$table_name." ORDER BY category DESC" );  
foreach ($rezultat_category as $value_category) 
{
echo '<option value="'.$value_category->category.'">'.$value_category->category.'</option>';
}
 ?>
</select><br><br>
<b>Title:</b><br>
<input type="text" autocomplete="off" spellcheck="true" value="<?php echo htmlspecialchars_decode($rezultat[0]->title); ?>" size="30" id="child_post_title" name="child_post_title"><br><br>
<b>Meta-description:</b><br>
<input type="text" autocomplete="off" spellcheck="true" value="<?php echo htmlspecialchars_decode($rezultat[0]->meta_description); ?>" size="30" id="child_post_meta-description" name="child_post_meta-description"><br><br>

<b>Link:</b><br>
<?php echo site_url().'/'.urldecode(get_option( 'wp_video_relative_url','wp-video' ));?>/<input type="text" autocomplete="off" spellcheck="true" value="<?php echo $rezultat[0]->video_url; ?>" size="30" id="child_post_link" name="child_post_link"><br><br>

<?php

if (class_exists('_WP_Editors'))
{
	
$reflector = new ReflectionClass('_WP_Editors');
if (strpos($reflector->getFileName(),'wp-includes/class-wp-editor.php') or strpos($reflector->getFileName(),'wp-includes\class-wp-editor.php')) 
{	
$settings = array('textarea_name'=>'child1');
add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );

}
else
{
$settings = array(
    'textarea_name'=>'child1',
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
	$settings = array('textarea_name'=>'child1');
}
	wp_editor( htmlspecialchars_decode($rezultat[0]->article_text), "child1_editor", $settings  );
	
	//wp_register_script( 'tinymin.js', 'http://147.91.204.66/wordpress/wp-includes/js/tinymce/tinymce.min.js?ver=4205-20150910');
	//wp_register_script( 'compat.js', 'http://147.91.204.66/wordpress/wp-includes/js/tinymce/plugins/compat3x/plugin.min.js?ver=4205-20150910');
	//wp_register_script( 'tiny.js', plugin_dir_url(__FILE__).'js/tiny.js');
	
    //wp_enqueue_script( 'tinymin.js' );
	//wp_enqueue_script( 'compat.js' );
	//wp_enqueue_script( 'tiny.js' );

?>
<div id="child1_div" style="display: none;"><input id="child1_ready" name="child1_ready" type="hidden"></div>
	<div id="youtube_import_div" style="display:none;" >
				<div class="input_align_txt left width_100">Search Youtube:</div>
				<input id="youtube_search_type"  type="hidden" value="0" >
	            <input onkeyup="if (event.keyCode == 13) document.getElementById('youtube_search_button').click();" value="" type="text" id="youtube_search_string" >
	            <input id="youtube_search_button" class="button" onclick="this.disabled = true;this.value='Please wait ...';setTimeout(function () {ImportYouTube();document.getElementById('youtube_search_button').value='Search';document.getElementById('youtube_search_button').disabled = false;},500);return false;"  type="button" value="Search" >
				<?php
if ($develop==true)
{
?>
				<div class="input_align_txt left width_100">Youtube chanel or playlist url (optional):</div>			
				<input value="" type="text" id="youtube_url" style="width:400px;" >
		<?php
}
?><div class="input_align_txt left width_100">	
Add ID's of youtube videos to add:</div>
<textarea id="youtube_text_import" style="height:250px ;"  name="youtube_text_import"></textarea><br>
<input onclick="ImportYouTubeText();document.getElementById('TB_closeWindowButton').click();" value="Add" class="button" type="button">
	
				<div id="youtube_output">
				</div>
				
	</div>
	


<div style="padding:8px;">
<!--<a onclick="document.getElementById('youtube_search_type').value='0';document.getElementById('youtube_output').innerHTML='';" href="#TB_inline&?width=750&height=800&inlineId=youtube_import_div" class="thickbox"><input class="button" type="button" value="Add YouTube video"></a>
<a onclick="document.getElementById('flickr_search_type').value='0';document.getElementById('flickr_output').innerHTML='';" href="#TB_inline?&width=750&height=800&inlineId=flickr_import_div" class="thickbox"><input class="button" type="button" value="Add Flickr image"></a>
-->
<div style="display:none;"> <input class="button" type="button" onclick="jInsertEditorText_youtube('link');return false;" value="Add YouTube shortcode">
  <span> Keyword: </span><strong><span id="child_main_keyword"></span></strong></div>
<div style="float:right; text-align: right;"><span><strong>Publish: </strong></span><label><span class="screen-reader-text">Month</span><select id="mm_child" name="mm_child">
			<option value="01" data-text="Jan" <?php echo (date("n",$rezultat[0]->date)==1?'selected':'');?> >01-Jan</option>
			<option value="02" data-text="Feb" <?php echo (date("n",$rezultat[0]->date)==2?'selected':'');?> >02-Feb</option>
			<option value="03" data-text="Mar" <?php echo (date("n",$rezultat[0]->date)==3?'selected':'');?> >03-Mar</option>
			<option value="04" data-text="Apr" <?php echo (date("n",$rezultat[0]->date)==4?'selected':'');?> >04-Apr</option>
			<option value="05" data-text="May" <?php echo (date("n",$rezultat[0]->date)==5?'selected':'');?> >05-May</option>
			<option value="06" data-text="Jun" <?php echo (date("n",$rezultat[0]->date)==6?'selected':'');?> >06-Jun</option>
			<option value="07" data-text="Jul" <?php echo (date("n",$rezultat[0]->date)==7?'selected':'');?> >07-Jul</option>
			<option value="08" data-text="Aug" <?php echo (date("n",$rezultat[0]->date)==8?'selected':'');?> >08-Aug</option>
			<option value="09" data-text="Sep" <?php echo (date("n",$rezultat[0]->date)==9?'selected':'');?> >09-Sep</option>
			<option value="10" data-text="Oct" <?php echo (date("n",$rezultat[0]->date)==10?'selected':'');?> >10-Oct</option>
			<option value="11" data-text="Nov" <?php echo (date("n",$rezultat[0]->date)==11?'selected':'');?> >11-Nov</option>
			<option value="12" data-text="Dec" <?php echo (date("n",$rezultat[0]->date)==12?'selected':'');?> >12-Dec</option>
</select></label> <label><span class="screen-reader-text">Day</span><input id="jj_child" name="jj_child" value="<?php echo date("d",$rezultat[0]->date); ?>" size="2" maxlength="2" autocomplete="off" type="text"></label>, <label><span class="screen-reader-text">Year</span><input id="aa_child" name="aa_child" value="<?php echo date("Y",$rezultat[0]->date); ?>" size="4" maxlength="4" autocomplete="off" type="text"></label> @ <label><span class="screen-reader-text">Hour</span><input id="hh_child" name="hh_child" value="<?php echo date("H",$rezultat[0]->date); ?>" size="2" maxlength="2" autocomplete="off" type="text"></label>:<label><span class="screen-reader-text">Minute</span><input id="mn_child" name="mn_child" value="<?php echo date("i",$rezultat[0]->date); ?>" size="2" maxlength="2" autocomplete="off" type="text"></label></div></div>
<a style="display: none;" id="click_temp" onclick="insertAtCursor(document.getElementById('site_banner_html'),'!from_media_library!'); document.getElementById('TB_closeWindowButton').click();setTimeout(function () {tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true')},500);return false;" href="http://pinterest.com/pin/create/button/?url=http://147.91.204.66/wordpress/index.php/2015/12/18/iopiup/&amp;media=_link_to_image&amp;description=%28Post%29+iopiup+%26%238211%3B+test" target="_blank" title="Pin it"><img src="http://147.91.204.66/wordpress/wp-content/plugins/wpanalyst/img/sharer/Pinterest.png"></a>
<div style="display:none;" id="banners_add_div">

<table class="form-table">
<tbody>
<tr>
</tr><tr>
<th scope="row">
<label for="banner_position" >Position:</label>
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
<option>
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
<input type="button" class="button" onclick="document.getElementById('TB_closeWindowButton').click();" value="Cancel">
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
<input type="button" id="site_edit_button" class="button" onclick="var x = document.getElementById('banners');x.childNodes[x.selectedIndex+1].innerHTML=x.childNodes[x.selectedIndex+1].innerHTML.split('+|+')[0]+'+|+'+encodeURIComponent(document.getElementById('site_banner_html_edit').value);EditBanner();document.getElementById('TB_closeWindowButton').click();" value="Save">
<input type="button" class="button" onclick="document.getElementById('TB_closeWindowButton').click();" value="Cancel">
<br><br>
</div>

<br>
<div class="postbox" style="display:inline-block;padding:5px;width:50%;">
<h4 class="hndle" style="margin: 5px 0 !important;"><span>Banners:</span></h4>
<table style="width:100% !important;">
<tr><td>

<?php
global $wpdb;
$table_name = $wpdb->prefix . "wp_video_banners";
$rezultat_banners= $wpdb->get_results( "SELECT id,type,category,position,html_text FROM ".$table_name." WHERE category = ".$_GET['id']." and type = 'individual';" );
$temp_banner="";
$temp_banner_option="";
foreach ($rezultat_banners as $value) 
{
	if ($temp_banner=="") 
	{
		$temp_banner = $value->position.'+|+'.$value->html_text; 
		$temp_banner_option="<option>".$value->position.'+|+'.$value->html_text."</option>";
	}
	else 
	{	
     $temp_banner = $temp_banner."\n".$value->position.'+|+'.$value->html_text;
	 $temp_banner_option=$temp_banner_option."<option>".$value->position.'+|+'.$value->html_text."</option>";
	}
}
?>
<input id="banners_out" name="banners_out" type="hidden" value="<?php echo urlencode($temp_banner); ?>">
<select id="banners" size="4" name="decision2" style="height:100px; width:100% !important;">
<?php echo $temp_banner_option; ?>
</select>
</td>
</table>
<br>
<a id="add_baner_button" onclick=";" href="#TB_inline&?width=750&height=800&inlineId=banners_add_div" class="thickbox"><input onclick="document.getElementById('site_banner_html').value=document.getElementById('site_banner_html').value.replace('!from_media_library!','');" class="button" type="button"  value="Add Banner"></a>
<a onclick="var x = document.getElementById('banners');document.getElementById('site_banner_html_edit').value=decodeURIComponent(x.childNodes[x.selectedIndex+1].innerHTML.split('+|+')[1]);" href="#TB_inline&?width=750&height=800&inlineId=banners_edit_div" class="thickbox"><input class="button" type="button"  value="Edit Banner"></a>
<input class="button" type="button" onclick="delBanner();return false;" value="Del Banner">
</div>
<div style="text-align: left;"><br><br><input onclick="document.getElementById('child1_div').innerHTML='<input value='+encodeURIComponent(editor_content())+' name=child1_ready >';"  class="button button-primary button-large" type="submit" value="Submit"></div>
</div>	

<script  type="text/javascript">

function editor_content()
{
var cur_page_text='';
if (typeof(tinyMCE) != "undefined" && typeof(tinymce) != "undefined") 
			{
if (tinymce.activeEditor != undefined)
{
if ( typeof tinymce.activeEditor.getContent == 'function') 
{

if (tinyMCE.activeEditor.isHidden()) {cur_page_text=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();} else {cur_page_text=tinymce.activeEditor.getContent();}
}
else
{
	cur_page_text=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();
}
}	
else
{
	cur_page_text=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();
}
}
else
{
	cur_page_text=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();
}
return cur_page_text;
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


function AddBanner()
{
var c_text=	document.getElementById("banner_position").value+'+|+'+encodeURIComponent(document.getElementById("site_banner_html").value);
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

function squash(arr){
    var tmp = [];
	var tmp2 = [];
    for(var i = 0; i < arr.length; i++){

        if(tmp2.indexOf(arr[i].toLowerCase().trim()) == -1){
        if (arr[i].trim()!='') {tmp.push(arr[i].trim());tmp2.push(arr[i].toLowerCase().trim());}
        }
    }
    return tmp;
}
    
	function keyword_search(page)
{
	

jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=keyword_research&engine="+document.getElementById("engine").value+"&search="+encodeURIComponent(document.getElementById('keyword_search_string').value),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){
        	
		var import_array=squash(document.getElementById("keyword_import").value.split("\n"));
		document.getElementById('keyword_import').value='';	
					
		var responce_array=squash(response.split("%0A"));
		
		for (i = 0; i < responce_array.length; i++) 
        { 
	     import_array.push(responce_array[i]);
		}
		import_array=squash(import_array);
		for (i = 0; i < import_array.length; i++) 
        {
			if (import_array[i].trim()!='') document.getElementById('keyword_import').value=document.getElementById('keyword_import').value+import_array[i]+'\n';
		}
            //$("#responsecontainer").html(response);
			//alert(response);
			//document.getElementById('keyword_import').value=document.getElementById('keyword_import').value+decodeURIComponent(response);
			//document.getElementById('keyword_output').innerHTML=response;
			
            
        }

    });
}
	
	function ImportYouTube(page)
{
	

jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo&type_of_search="+document.getElementById('youtube_search_type').value+"&search="+encodeURIComponent(document.getElementById('youtube_search_string').value)<?php if ($develop==true){?> +"&youtube_url="+encodeURIComponent(document.getElementById('youtube_url').value) <?php } ?>,             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			//alert(response);
			document.getElementById('youtube_output').innerHTML=response;
			
            
        }

    });
}
function ImportFlickr(page)
{
	

jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=flickr&type_of_search="+document.getElementById('flickr_search_type').value+"&search="+encodeURIComponent(document.getElementById('flickr_search_string').value),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			//alert(response);
			document.getElementById('flickr_output').innerHTML=response;
			
            
        }

    });
}
</script>	
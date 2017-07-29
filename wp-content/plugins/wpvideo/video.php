<form onsubmit="setFormSubmitting();if (check_video()=='no') {return false;} if (check_changes_in_vp()=='no') {return false;}" id="main_form" action="admin.php?page=WpVideo<?php if (isset($_GET['id'])) echo '&edit=true&id='.$_GET['id'];?>" method="post">
<?php


//ini_set('display_errors', 1);
 //   ini_set('display_startup_errors', 1);
  //  error_reporting(E_ALL);
if (!function_exists('encodeURIComponent')) 
{
function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}	

function stringURLSafe($string)
	{
		//remove any '-' from the string they will be used as concatonater
		$str = str_replace('-', ' ', $string);

		// remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);

		// lowercase and trim
		$str = trim(strtolower($str));
		if ($str=='untitled') $str='1-untitled';
		return $str;
	}	


//---------------------------------------------------------------------------------------------------
wp_enqueue_script('thickbox');

if (isset($_POST['post']))
{
global $wpdb;
$table_name = $wpdb->prefix . "wp_video";

	foreach ($_POST['post'] as $value) 
	{
		if ($_POST['new_category_name']!='')
{
	$wpdb->update(
		$table_name, 
		array( 
          	'category' => $_POST['new_category_name']
		),
		array( 'id' => $value)
	);
}
else if ($_POST['video_site_set_at_frontpage']=='set')
{
	$wpdb->update(
		$table_name, 
		array( 
          	'featured' => '1'
		),
		array( 'id' => $value)
	);
}	
else
{
		$wpdb->delete( $table_name, array( 'id' => $value ) );
}
	}
}


if (isset($_GET['add']))
{
?>

<h2>WpVideoSites:</h2>
<script  type="text/javascript">
function check_video() 
{
	
<?php
if ($develop!=true)
{
?>
var x = document.getElementById("child_selector");
if 	(x.children.length>14) {alert('Only 15 post alowed same time in this version. No limits for Enterprise.');return true;};
<?php
}
?>	

var table = document.getElementById("child_content");


var b=0;	
for(var i=0;i<table.rows.length;i++) 
{
	if (table.rows[i].cells[1].childNodes[0].value.indexOf('www.youtube.com%2Fembed%2F') < 1) {b=1;document.getElementById("child_selector").children[i].style.color='red';} else {document.getElementById("child_selector").children[i].style.color='black';};
}	
if (b==1) {alert('Posts marked in red do not contain a video, please edit them first.');return 'no';}
	return 'yes';
}
var cur_page_text='';
function check_changes_in_vp() 
{


//-----------------------------------------------------------------------------------
//alert(confirm('There is unsaved shanges in this post , continue will be lost all changes.'));
var cur_page_text_e='';
if (typeof(tinyMCE) != "undefined" && typeof(tinymce) != "undefined") 
			{
if (tinymce.activeEditor != undefined)
{
if (typeof tinymce.activeEditor.getContent == 'function') 
{

if (tinyMCE.activeEditor.isHidden()) {cur_page_text_e=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();} else {cur_page_text_e=tinymce.activeEditor.getContent();}
}
else
{
	cur_page_text_e=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();
}
}	
else
{
	cur_page_text_e=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();
}
}
else
{
	cur_page_text_e=jQuery( '#wp-child1_editor-editor-container' ).find( 'textarea' ).val();
}
var has_changes_vp=false;
var x = document.getElementById("child_selector");
var table = document.getElementById("child_content");

selectedChild=x.selectedIndex; 
if (selectedChild==-1) return 'yes';
if (encodeURIComponent(document.getElementById("child_post_title").value) != table.rows[x.selectedIndex].cells[0].childNodes[0].value) has_changes_vp=true;
if (encodeURIComponent(document.getElementById("child_post_meta_description").value) != table.rows[x.selectedIndex].cells[5].childNodes[0].value) has_changes_vp=true;

var child_post_time=table.rows[x.selectedIndex].cells[3].childNodes[0].value.split(':');
if (document.getElementById("mm_child").value != child_post_time[0]) has_changes_vp=true;
if (document.getElementById("jj_child").value != child_post_time[1]) has_changes_vp=true;
if (document.getElementById("aa_child").value != child_post_time[2]) has_changes_vp=true;
if (document.getElementById("hh_child").value != child_post_time[3]) has_changes_vp=true;
if (document.getElementById("mn_child").value != child_post_time[4]) has_changes_vp=true;

if (cur_page_text_e != cur_page_text) has_changes_vp=true;

if (has_changes_vp == true) {if (confirm('There is unapplied changes in this post , continue anyway?') == false) {return 'no';} else {return 'yes';}}
	return 'yes';


}
//-----------------
function add_links_stack() {
checkboxes = document.getElementsByName('box[]');
var table = document.getElementById("child_content");

var xsi = document.getElementById("child_selector");
var vi=0;
if (xsi.selectedIndex != -1) { vi=xsi.selectedIndex;}

	
for(var i=0;i<checkboxes.length;i++) {
if (checkboxes[i].checked)
{

var x = document.getElementById("youtube_shortcode");
var option = document.createElement("option");
option.text = checkboxes[i].value;
x.add(option);	
document.getElementById("youtube_list").value=document.getElementById("youtube_list").value+','+checkboxes[i].value;


if (table.rows.length>vi)
{
if (document.getElementById("add_youtube_details").checked)
{
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo&description="+encodeURIComponent('https://www.youtube.com/watch?v='+checkboxes[i].value.split('/')[4]),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			//alert(response);
			if (document.getElementById("youtube_details_position_1").checked) table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response)+'</div>'+table.rows[vi].cells[1].childNodes[0].value+'">';
            if (document.getElementById("youtube_details_position_2").checked) table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response+'</div>')+'">';
            if (document.getElementById("youtube_details_position_3").checked) 
            {

    expr= "%3C%2Fp%3E";
	 expr1= "3Cbr%20%2F%3E%3Cbr%20%2F%3E";
	 expr2= "%3Cbr%3E%3Cbr%3E";
	if (table.rows[vi].cells[1].childNodes[0].value.indexOf(expr)>0)
	{		
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value.replace('%3C%2Fp%3E','%3C%2Fp%3E'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response+'</div>'))+'">';
	}
 else if (table.rows[vi].cells[1].childNodes[0].value.indexOf(expr1)>0)
	{		
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value.replace('%3Cbr%20%2F%3E%3Cbr%20%2F%3E','%3Cbr%20%2F%3E%3Cbr%20%2F%3E'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response+'</div>'))+'">';
	}	
 else if (table.rows[vi].cells[1].childNodes[0].value.indexOf(expr2)>0)
	{		
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value.replace('%3Cbr%3E%3Cbr%3E','%3Cbr%3E%3Cbr%3E'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response+'</div>'))+'">';
	}	
 
 else
 {
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response+'</div>')+'">';
   }	 
			}
			//document.getElementById('youtube_list').value=response;
		
            
        }

    });
}

if (document.getElementById("add_youtube_subtitles").checked)
{
no_subtitle=-1;	
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo&subtitle="+encodeURIComponent('https://www.youtube.com/watch?v='+checkboxes[i].value.split('/')[4]),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){ 
             if (response.trim()=='') {no_subtitle=1;}		
            //$("#responsecontainer").html(response);
			//alert(response);
			if (document.getElementById("youtube_details_position_1").checked) table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response)+'</div>'+table.rows[vi].cells[1].childNodes[0].value+'">';
            if (document.getElementById("youtube_details_position_2").checked) table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response)+'</div>'+'">';
            if (document.getElementById("youtube_details_position_3").checked)
 {

    expr= "%3C%2Fp%3E";
	expr1= "%3Cbr%20%2F%3E%3Cbr%20%2F%3E";
	expr2= "%3Cbr%3E%3Cbr%3E";
	if (table.rows[vi].cells[1].childNodes[0].value.indexOf(expr)>0)
	{		
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value.replace('%3C%2Fp%3E','%3C%2Fp%3E'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response)+'</div>')+'">';}	
 else if (table.rows[vi].cells[1].childNodes[0].value.indexOf(expr1)>0)
	{		
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value.replace('%3Cbr%20%2F%3E%3Cbr%20%2F%3E','%3Cbr%20%2F%3E%3Cbr%20%2F%3E'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response)+'</div>')+'">';}	
  else if (table.rows[vi].cells[1].childNodes[0].value.indexOf(expr2)>0)
	{		
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value.replace('%3Cbr%3E%3Cbr%3E','%3Cbr%3E%3Cbr%3E'+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response)+'</div>')+'">';}	
 
 else
 {
	table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+table.rows[vi].cells[1].childNodes[0].value+'<?php echo encodeURIComponent(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+encodeURIComponent(response)+'</div>'+'">';
   }	 
			}
			//document.getElementById('youtube_list').value=response;
		
            
        }

    });
if (no_subtitle==1) {continue;}		
}
	
if (table.rows[vi].cells[0].childNodes[0].value == '') table.rows[vi].cells[0].innerHTML='<input name="chld_array_title[]" value="'+encodeURIComponent(checkboxes[i].getAttribute('data-title'))+'">';	

table.rows[vi].cells[1].innerHTML='<input name="chld_array_content[]" value="'+encodeURIComponent('<p><iframe title="YouTube video player" class="youtube-player" type="text/html" width="604" height="340" src="'+checkboxes[i].value+'" frameborder="0" allowFullScreen></iframe></p>')+table.rows[vi].cells[1].childNodes[0].value+'">';	

}
vi++;
}



}
document.getElementById('TB_closeWindowButton').click();
var xsi = document.getElementById("child_selector");
if (xsi.selectedIndex != -1) { xsi.children[xsi.selectedIndex].click();}

return false;
}
function clear_links_stack() {

var x = document.getElementById("youtube_shortcode");
x.innerHTML = '';
document.getElementById("youtube_list").value='';
//x.removeChild(x[x.selectedIndex]);	

}
//-------------------------------

//-----------------
function add_links_stack_flickr() {
checkboxes = document.getElementsByName('box_f[]');
for(var i=0;i<checkboxes.length;i++) {
if (checkboxes[i].checked)
{
var x = document.getElementById("flickr_shortcode");
var option = document.createElement("option");
option.text = checkboxes[i].value;
x.add(option);
document.getElementById("flickr_list").value=document.getElementById("flickr_list").value+','+checkboxes[i].value;


}
}
document.getElementById('TB_closeWindowButton').click();
return false;
}
function clear_links_stack_flickr() {

var x = document.getElementById("flickr_shortcode");
x.innerHTML = '';
document.getElementById("flickr_list").value='';
//x.removeChild(x[x.selectedIndex]);	

}
//-------------------------------


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



	function isBrowserIE_new() {
				return navigator.appName=="Microsoft Internet Explorer";
			}
	function jInsertEditorText_post(link) {
			//alert(tinymce.activeEditor.selection.getContent());
			//var text=tinymce.activeEditor.selection.getContent();
			//tinymce.activeEditor.selection.setContent('<a href="<?php echo get_post_permalink();?>">'+text+'</a>');
			insertAtCursor(document.getElementById('child1_editor'),'<a href="<?php echo get_post_permalink();?>">'+document.getElementById('child_main_keyword').innerHTML+'</a>');
			if (typeof(tinyMCE) != "undefined") 
			{
			
			tinyMCE.get('child1_editor').focus();
			window.send_to_editor('<a href="<?php echo get_post_permalink();?>">'+document.getElementById('child_main_keyword').innerHTML+'</a>');
			}
			}
			
			function jInsertEditorText_youtube(link) {
			//alert(tinymce.activeEditor.selection.getContent());
			//var text=tinymce.activeEditor.selection.getContent();
			//tinymce.activeEditor.selection.setContent('<a href="<?php echo get_post_permalink();?>">'+text+'</a>');
			insertAtCursor(document.getElementById('child1_editor'),'<p>###YouTube###<p>');
			if (typeof(tinyMCE) != "undefined") 
			{
			
			tinyMCE.get('child1_editor').focus();
			window.send_to_editor('<p>###YouTube###<p>');
			}
			}
			
			function jInsertEditorText_flickr(link) {
			//alert(tinymce.activeEditor.selection.getContent());
			//var text=tinymce.activeEditor.selection.getContent();
			//tinymce.activeEditor.selection.setContent('<a href="<?php echo get_post_permalink();?>">'+text+'</a>');
			insertAtCursor(document.getElementById('child1_editor'),'<p>###Flickr###<p>');
			if (typeof(tinyMCE) != "undefined") 
			{
			
			tinyMCE.get('child1_editor').focus();
			window.send_to_editor('<p>###Flickr###<p>');
			}
			}
			
		
		function BestSpinner_title(page)
{
document.getElementById('title_for_spined').value="Please wait ...";	

jQuery.ajax({    //create an ajax request to load_page.php
        type: "POST",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=<?php echo (get_option( 'wp_video_post_spin_selector','0' )=="0" ? 'vspin_rewriter':'vbest_spinner');?>",             
        dataType: "html",   //expect html to be returned  
		data: "string="+encodeURIComponent(page),
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			//alert(response);
			
			document.getElementById('title_for_spined').value=response;
		
            
        }

    });
}
	function BestSpinner_text(page)
{
document.getElementById('spintext').value="Please wait ...";	

jQuery.ajax({    //create an ajax request to load_page.php
        type: "POST",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=<?php echo (get_option( 'wp_video_post_spin_selector','0' )=="0" ? 'vspin_rewriter':'vbest_spinner');?>",             
        dataType: "html",   //expect html to be returned  
		data: "string="+encodeURIComponent(page),
        async: false,		
        success: function(response){   
			//alert(response);
			document.getElementById('spintext').value=response;
		}

    });
}	
			
			
		function FlickrInsertPicture(link) {
			//alert(tinymce.activeEditor.selection.getContent());
			//var text=tinymce.activeEditor.selection.getContent();
			//tinymce.activeEditor.selection.setContent('<a href="<?php echo get_post_permalink();?>">'+text+'</a>');
			insertAtCursor(document.getElementById('child1_editor'),'<img src="'+link+'" </img>');
			try {
			if (typeof(tinyMCE) != "undefined") tinyMCE.get('child1_editor').focus();
			window.send_to_editor('<img src="'+link+'" </img>');
			}
			catch(err) {}
			document.getElementById('TB_closeWindowButton').click();
				//tinyMCEPreInit.execInstanceCommand('content', 'mceInsertContent',false,text);
				//tinymce.execCommand('mceInsertContent', 0, "<b>misa</b>");
				//tinymce.activeEditor.setContent(tinyMCE.activeEditor.getContent() + 'text inserted! ');

            //document.getElementById('TB_closeWindowButton').click();
			//return false;
			}
		function YouTubeInsertVideo(link) {
			//alert(tinymce.activeEditor.selection.getContent());
			//var text=tinymce.activeEditor.selection.getContent();
			//tinymce.activeEditor.selection.setContent('<a href="<?php echo get_post_permalink()?>">'+text+'</a>');
			insertAtCursor(document.getElementById('child1_editor'),'<iframe title="YouTube video player" class="youtube-player" type="text/html" width="604" height="340" src="https://www.youtube.com/embed/'+link+'" frameborder="0" allowFullScreen></iframe>');
			try {
			tinyMCE.get('child1_editor').focus();
			window.send_to_editor('<iframe title="YouTube video player" class="youtube-player" type="text/html" width="604" height="340" src="https://www.youtube.com/embed/'+link+'" frameborder="0" allowFullScreen></iframe>');
			}
			catch(err) {}
			document.getElementById('TB_closeWindowButton').click();
			//tinyMCEPreInit.execInstanceCommand('content', 'mceInsertContent',false,text);
				//tinymce.execCommand('mceInsertContent', 0, "<b>misa</b>");
				//tinymce.activeEditor.setContent(tinyMCE.activeEditor.getContent() + 'text inserted! ');

            //document.getElementById('TB_closeWindowButton').click();
			//return false;
			}
			
var selectedChild=-1;
var no_subtitle=-1;

		
function ImportChild()
{

<?php
if ($develop!=true)
{
?>
var x = document.getElementById("child_selector");	
if 	(x.children.length>14) {alert('Only 15 post alowed same time in this version. No limits for Enterprise.');return true;};
<?php
}
?>


video_mode_response_array=undefined;
var import_array=document.getElementById("keyword_import").value.split("\n");
<?php 
if (!isset($_GET['backlink']))
{	
?>
import_array=[];
for (i = 1; i <= document.getElementById("number_of_posts").value; i++) 
{
	import_array.push("Post "+(document.getElementById("child_selector").children.length+i).toString());
}
<?php 
}	
?>




for (i = 0; i < import_array.length; i++) 
{ 
if (import_array[i].toString().trim()=='') continue;
<?php //if ($develop!=true) echo 'if (x.length>9) {alert("More than 10 child post is alowed only at Enterprise version."); break;}' ?>

document.getElementById("import_button").value='Please wait ('+(i+1).toString()+' of '+import_array.length.toString()+')';		


var article_title='';
var article_content='';


//==================

if (document.getElementById("generate_content").value=='05')
{

if (document.getElementById('use_ab_for_youtube').checked)
{
	
	var abcategory=document.getElementById('article_builder_category2');
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=varticle_builder&category="+encodeURIComponent(abcategory.options[abcategory.selectedIndex].text)+"&keyword="+encodeURIComponent(import_array[i].toString()),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			 
			var response_array=response.split('<p>');
			article_title=response_array[0].replace("</p>","");
			article_content=response.replace(article_title+'<p>','');
	
			article_content=response_array[1];
			
			if (document.getElementById("shortcodes_video_or_image").checked)
			{
				var imgsel=0;
				var vidsel=0;
				if (Math.floor((Math.random() * 10) + 1)>5) {imgsel=1;} else {vidsel=1;}
				
			}
			if (document.getElementById("shortcodes_video_and_image").checked)
			{
				var imgsel=1;
				var vidsel=1;
				
			}
			
			for (iv = 2; iv < response_array.length; iv++) 
				{
	var spliter_string='<p>';
	//if (iv==2 && (document.getElementById("shortcodes_video").checked || vidsel==1)) spliter_string='<p>###YouTube###<p>';
	//if (iv==3 && (document.getElementById("shortcodes_image").checked || imgsel==1)) spliter_string='<p>###Flickr###<p>';
	//if (iv==4 && document.getElementById("shortcodes").checked) spliter_string='<p>###Link###<p>';
	article_content=article_content+ spliter_string+response_array[iv];
	}

            //alert(response);
        }

    });
	
	
	
}






if (typeof(video_mode_response_array)=='undefined' || document.getElementById('youtube_url_bulk').value=='')
{
	
	jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo_mode&search="+encodeURIComponent(import_array[i].toString())+"&youtube_url="+encodeURIComponent(document.getElementById('youtube_url_bulk').value)<?php if ($develop==true){?> +"&captions="+document.getElementById('add_youtube_subtitles_vm').checked <?php } ?>,             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){  
        //alert(response)	;
            //$("#responsecontainer").html(response);
			video_mode_response_array=response.split('|+|+');
		}
	
});
}
//alert(i);
article_title=video_mode_response_array[i*2+1];

if (document.getElementById('use_ab_for_youtube').checked!=true) article_content=spin(document.getElementById("spintext").value);
if (document.getElementById("spin_shortcodes_video_or_image").checked)
			{
				var imgsel=0;
				var vidsel=0;
				if (Math.floor((Math.random() * 10) + 1)>5) {imgsel=1;} else {vidsel=1;}
				
			}
			if (document.getElementById("spin_shortcodes_video_and_image").checked)
			{
				var imgsel=1;
				var vidsel=1;
			}
			
			
	if ((article_content.indexOf('###YouTube###') == -1) && (document.getElementById("spin_shortcodes_video").checked || vidsel==1)) article_content='<p>###YouTube###<p>'+article_content;
	if ((article_content.indexOf('###Flickr###') == -1) && (document.getElementById("spin_shortcodes_image").checked || imgsel==1)) article_content=article_content+'<p>###Flickr###<p>';
	if ((article_content.indexOf('###Link###') == -1) && document.getElementById("spin_shortcodes").checked) article_content=article_content+'<p>###Link###<p>';

//youtube_details


if (document.getElementById("add_youtube_details_vm").checked)
{
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo&description="+encodeURIComponent('https://www.youtube.com/watch?v='+video_mode_response_array[i*2].split('/')[4]),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			//alert(response);
			
			if (document.getElementById("youtube_details_position_1_vm").checked) article_content='<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>'+article_content;
            if (document.getElementById("youtube_details_position_2_vm").checked) article_content=article_content+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>';
            if (document.getElementById("youtube_details_position_3_vm").checked) 
			{

    expr= "</p>";
	expr1= "<br /><br />";
	expr2= "<br><br>";
	if (article_content.indexOf(expr)>0)
	{		
		article_content=article_content.replace('</p>','</p>'+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>');
	}
else if (article_content.indexOf(expr1)>0)
	{		
		article_content=article_content.replace('<br /><br />','<br /><br />'+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>');
	}
else if (article_content.indexOf(expr2)>0)
	{		
		article_content=article_content.replace('<br><br>','<br><br>'+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>');
	}	
 else
 {
	 article_content=article_content+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>';
 }	 
			}
			//document.getElementById('youtube_list').value=response;
		
            
        }

    });
}
if (document.getElementById("add_youtube_subtitles_vm").checked)
{
do {	
no_subtitle=-1;	
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo&subtitle="+encodeURIComponent('https://www.youtube.com/watch?v='+video_mode_response_array[i*2].split('/')[4]),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){ 
           	if (response.trim()=='') {no_subtitle=1; video_mode_response_array.splice(i*2,2);return;}	
			article_title=video_mode_response_array[i*2+1];
            //$("#responsecontainer").html(response);
			//alert(response);
			if (document.getElementById("youtube_details_position_1_vm").checked) article_content='<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>'+article_content;
            if (document.getElementById("youtube_details_position_2_vm").checked) article_content=article_content+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>';
            if (document.getElementById("youtube_details_position_3_vm").checked) 
             {

    expr= "</p>";
	expr1= "<br /><br />";
	expr2= "<br><br>";
	//alert(article_content);
	if (article_content.indexOf(expr)>0)
	{		
		article_content=article_content.replace('</p>','</p>'+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>');
	}	
	else if (article_content.indexOf(expr1)>0)
	{		
		article_content=article_content.replace('<br /><br />','<br /><br />'+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>');
	}
	else if (article_content.indexOf(expr2)>0)
	{		
		article_content=article_content.replace('<br><br>','<br><br>'+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>');
	}
 else
 {
	 article_content=article_content+'<?php echo json_encode(stripslashes(get_option( 'wp_video_yt_description','<div id="YouTubeDescription" style="background-color: #FFFFCC; color: #000000;">' ))) ;?>'+response+'</div>';
 }	 
			}
			//document.getElementById('youtube_list').value=response;
		
            
        }

    });
  document.getElementById('import_button').value=video_mode_response_array.length.toString();	
}
 
	while (no_subtitle==1 && video_mode_response_array.length>i*2);
	if (video_mode_response_array.length<=i*2+1) break;
}		

//

article_content='<p><iframe title="YouTube video player" class="youtube-player" type="text/html" width="604" height="340" src="'+video_mode_response_array[i*2]+'" frameborder="0" allowFullScreen></iframe></p><p>'+video_mode_response_array[i*2+1]+'</p>'+article_content;

}


//==================
if (document.getElementById("generate_content").value=='04')
{
	var abcategory=document.getElementById('article_builder_category');
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=varticle_builder&category="+encodeURIComponent(abcategory.options[abcategory.selectedIndex].text)+"&keyword="+encodeURIComponent(import_array[i].toString()),             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			 
			var response_array=response.split('<p>');
			article_title=response_array[0].replace("</p>","");
			article_content=response.replace(article_title+'<p>','');
	
			article_content=response_array[1];
			
			if (document.getElementById("shortcodes_video_or_image").checked)
			{
				var imgsel=0;
				var vidsel=0;
				if (Math.floor((Math.random() * 10) + 1)>5) {imgsel=1;} else {vidsel=1;}
				
			}
			if (document.getElementById("shortcodes_video_and_image").checked)
			{
				var imgsel=1;
				var vidsel=1;
				
			}
			
			for (iv = 2; iv < response_array.length; iv++) 
				{
	var spliter_string='<p>';
	//if (iv==2 && (document.getElementById("shortcodes_video").checked || vidsel==1)) spliter_string='<p>###YouTube###<p>';
	//if (iv==3 && (document.getElementById("shortcodes_image").checked || imgsel==1)) spliter_string='<p>###Flickr###<p>';
	//if (iv==4 && document.getElementById("shortcodes").checked) spliter_string='<p>###Link###<p>';
	article_content=article_content+ spliter_string+response_array[iv];
	}

            //alert(response);
        }

    });
}

//==================
if (document.getElementById("generate_content").value=='02')
{

			article_title=spin(document.getElementById("title_for_spined").value);
			article_content=spin(document.getElementById("spintext").value);
			
			if (document.getElementById("spin_shortcodes_video_or_image").checked)
			{
				var imgsel=0;
				var vidsel=0;
				if (Math.floor((Math.random() * 10) + 1)>5) {imgsel=1;} else {vidsel=1;}
				
			}
			if (document.getElementById("spin_shortcodes_video_and_image").checked)
			{
				var imgsel=1;
				var vidsel=1;
			}
			
			
	if ((article_content.indexOf('###YouTube###') == -1) && (document.getElementById("spin_shortcodes_video").checked || vidsel==1)) article_content='<p>###YouTube###<p>'+article_content;
	if ((article_content.indexOf('###Flickr###') == -1) && (document.getElementById("spin_shortcodes_image").checked || imgsel==1)) article_content=article_content+'<p>###Flickr###<p>';
	if ((article_content.indexOf('###Link###') == -1) && document.getElementById("spin_shortcodes").checked) article_content=article_content+'<p>###Link###<p>';
	
	
	

}
//----------------------------------------------------
//==================
if (document.getElementById("generate_content").value=='03')
{

			article_title=spin(document.getElementById("title_for_spined").value);
			article_content=spin(document.getElementById("spintext").value);

			if (document.getElementById("spin_shortcodes_video_or_image").checked)
			{
				var imgsel=0;
				var vidsel=0;
				if (Math.floor((Math.random() * 10) + 1)>5) {imgsel=1;} else {vidsel=1;}
				
			}
			
			if (document.getElementById("spin_shortcodes_video_and_image").checked)
			{
				var imgsel=1;
				var vidsel=1;
			}
			
			
	if ((article_content.indexOf('###YouTube###') == -1) && (document.getElementById("spin_shortcodes_video").checked || vidsel==1)) article_content='<p>###YouTube###<p>'+article_content;
	if ((article_content.indexOf('###Flickr###') == -1) && (document.getElementById("spin_shortcodes_image").checked || imgsel==1)) article_content=article_content+'<p>###Flickr###<p>';
	if ((article_content.indexOf('###Link###') == -1) && document.getElementById("spin_shortcodes").checked) article_content=article_content+'<p>###Link###<p>';
				
		//alert(article_content.indexOf('###Link###'));	

}
//----------------------------------------------------
<?php if (isset($_GET['backlink']))
{ 
?>
var anchor_text=spin(document.getElementById("anchortext").value).replace('<!anchor>','<a data-href="'+document.getElementById('backlink_value').innerHTML+'" href="'+document.getElementById('backlink_value_href').innerHTML+'">'+import_array[i].toString()+'</a>');
article_content=article_content+'<p>'+ anchor_text+'</p>';
<?php 
} 
?>

var time_for_publish='<?php echo date("m").':'.date("d").':'.date("Y").':'.date("H").':'.date("i"); ?>';
if (document.getElementById("scheduled1").checked)
{
var d = new Date(GetTimeForSchredule(i));

var yyyy = d.getFullYear().toString();
var mm = (d.getMonth()+1).toString(); // getMonth() is zero-based
var dd  = d.getDate().toString();
var hh = d.getHours().toString();
var minmin = d.getMinutes().toString();

time_for_publish=(mm[1]?mm:"0"+mm[0])+ ':'+ (dd[1]?dd:"0"+dd[0])+ ':'+yyyy + ':'+ (hh[1]?hh:"0"+hh[0])+ ':'+ (minmin[1]?minmin:"0"+minmin[0]);	
} 

var x = document.getElementById("child_selector");
var table = document.getElementById("child_content")
var option = document.createElement("option");
option.setAttribute('onclick','select_child(this);');
option.text = import_array[i].toString();
x.add(option);
var row=table.insertRow(-1);
var cell1=row.insertCell(0);
cell1.innerHTML='<input name="chld_array_title[]" value="'+encodeURIComponent(article_title)+'">';
var cell2=row.insertCell(1);
cell2.innerHTML='<input name="chld_array_content[]" value="'+encodeURIComponent(article_content)+'">';
var cell3=row.insertCell(2);
cell3.innerHTML='<input name="chld_array_keyword[]" value="'+import_array[i].toString()+'">';
var cell4=row.insertCell(3);
cell4.innerHTML='<input name="chld_array_time[]" value="'+time_for_publish+'">';
var cell5=row.insertCell(4);
cell5.innerHTML='<input name="chld_array_banners[]" value="">';
var cell6=row.insertCell(5);
cell6.innerHTML='<input name="chld_array_meta_description[]" value="">';

}
}

function GetTimeForSchredule(i_from_import)
{
	var shredule_times=document.getElementById("shredule_times").value;
	var shredule_days=document.getElementById("shredule_days").value;
    var now = new Date; // now

now.setHours(0);   // set hours to 0
now.setMinutes(0); // set minutes to 0
now.setSeconds(0); // set seconds to 0




    return (now.valueOf()+(Math.floor(parseInt(i_from_import/shredule_times))*parseInt(shredule_days))*86400000)+Math.floor((Math.random() * 86400000) + 1);
    
	
}			
			
function addChild()
{

<?php
if ($develop!=true)
{
?>	
var x = document.getElementById("child_selector");
if 	(x.children.length>14) {alert('Only 15 post alowed same time in this version. No limits for Enterprise.');return true;};
<?php
}
?>		
var c_text=prompt("Please enter new post name", "Post");
if 	(c_text==null) return;
var x = document.getElementById("child_selector");
var option = document.createElement("option");
option.setAttribute('onclick','select_child(this);');
option.text = c_text;
x.add(option);

var table = document.getElementById("child_content");
var row=table.insertRow(-1);
var cell1=row.insertCell(0);
cell1.innerHTML='<input name="chld_array_title[]" value="">';
var cell2=row.insertCell(1);
cell2.innerHTML='<input name="chld_array_content[]" value="<?php if (isset($_GET['backlink'])) {?>'+encodeURIComponent('<a data-href="'+document.getElementById('backlink_value').innerHTML+'" href="'+document.getElementById('backlink_value_href').innerHTML+'">'+c_text+'</a>')+'<?php } ?>">';
var cell3=row.insertCell(2);
cell3.innerHTML='<input name="chld_array_keyword[]" value="<?php if (isset($_GET['backlink'])) {?>'+c_text+'<?php } ?>">';
var cell4=row.insertCell(3);
cell4.innerHTML='<input name="chld_array_time[]" value="<?php echo date("m").':'.date("d").':'.date("Y").':'.date("H").':'.date("i"); ?>">';



var cell5=row.insertCell(4);
cell5.innerHTML='<input name="chld_array_banners[]" value="">';
var cell6=row.insertCell(5);
cell6.innerHTML='<input name="chld_array_meta_description[]" value="">';

var xsi = document.getElementById("child_selector");
xsi.selectedIndex=xsi.children.length-1;
temp_for_all=true;
xsi.children[xsi.children.length-1].click();

}
function delChild()
{
	var x = document.getElementById("child_selector");
	var table = document.getElementById("child_content");
    table.rows[x.selectedIndex].remove();
    x.removeChild(x[x.selectedIndex]);

}

function select_child(obj)
{
if (temp_for_all==false) return true;
document.getElementById("child_main_keyword").innerHTML=obj.innerHTML;
var x = document.getElementById("child_selector");
var table = document.getElementById("child_content");

selectedChild=x.selectedIndex; 


document.getElementById("child_post_title").value=decodeURIComponent(table.rows[x.selectedIndex].cells[0].childNodes[0].value);
document.getElementById("child_post_meta_description").value=decodeURIComponent(table.rows[x.selectedIndex].cells[5].childNodes[0].value);

var child_post_time=table.rows[x.selectedIndex].cells[3].childNodes[0].value.split(':');
document.getElementById("mm_child").value=child_post_time[0];
document.getElementById("jj_child").value=child_post_time[1];
document.getElementById("aa_child").value=child_post_time[2];
document.getElementById("hh_child").value=child_post_time[3];
document.getElementById("mn_child").value=child_post_time[4];

document.getElementById('child1_editor').value=decodeURIComponent(table.rows[x.selectedIndex].cells[1].childNodes[0].value);
tinyMCE.get('child1_editor').focus();
tinymce.activeEditor.setContent(decodeURIComponent(table.rows[x.selectedIndex].cells[1].childNodes[0].value));
cur_page_text='';
if (typeof(tinyMCE) != "undefined" && typeof(tinymce) != "undefined") 
			{
if (tinymce.activeEditor != undefined)
{
if (typeof tinymce.activeEditor.getContent == 'function') 
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

var temp_banner=decodeURIComponent(table.rows[x.selectedIndex].cells[4].childNodes[0].value);
var temp_banner_arr=temp_banner.split("\n");
var x = document.getElementById("banners");
x.innerHTML = '';
for (i = 0; i < temp_banner_arr.length; i++) 
{
var option = document.createElement("option");
//option.setAttribute('onclick','select_child(this);');
option.text = temp_banner_arr[i];
x.add(option);
}

}
function save_child()
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
	
var cur_page_text='';
if (typeof(tinyMCE) != "undefined" && typeof(tinymce) != "undefined") 
			{
if (tinymce.activeEditor != undefined)
{
if (typeof tinymce.activeEditor.getContent == 'function') 
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
var table = document.getElementById("child_content");
if (selectedChild!=-1) 
{
	
	table.rows[selectedChild].cells[0].innerHTML='<input name="chld_array_title[]" value="'+encodeURIComponent(document.getElementById("child_post_title").value)+'">';table.rows[selectedChild].cells[1].innerHTML='<input name="chld_array_content[]" value="'+encodeURIComponent(cur_page_text)+'">';table.rows[selectedChild].cells[3].innerHTML='<input name="chld_array_time[]" value="'+document.getElementById("mm_child").value+":"+document.getElementById("jj_child").value+":"+document.getElementById("aa_child").value+":"+document.getElementById("hh_child").value+":"+document.getElementById("mn_child").value+'">';table.rows[selectedChild].cells[4].innerHTML='<input name="chld_array_banners[]" value="'+encodeURIComponent(temp_banner)+'">';table.rows[selectedChild].cells[5].innerHTML='<input name="chld_array_meta_description[]" value="'+encodeURIComponent(document.getElementById("child_post_meta_description").value)+'">';
	var xsi = document.getElementById("child_selector");
xsi.children[selectedChild].click();
}



}

String.prototype.replaceAt=function(index, character) {
    return this.substr(0, index) + character + this.substr(index+character.length);
}



function spin(spintax) {
    spintax = spintax.replace(/£/g,"L");
	spintax=spintax.replace(/(?:\r\n|\r|\n)/g, '<br />');
    var points = [];
    for (var i = 0; i < spintax.length; i++) {
        var ch = spintax[i];
        if (ch == "{")
            points.push(i);
        else if (ch == "}") {
            var subSpintax = spintax.substring(points[points.length - 1], i + 1).split("|");
            var position = Math.floor(Math.random() * subSpintax.length);
            for (var j = 0; j < subSpintax.length; j++) {
                if (j != position)
                    for (var l = 0; l < subSpintax[j].length; l++)
                        subSpintax[j] = subSpintax[j].replaceAt(l, "£");
            }
            subSpintax = subSpintax.join("{");
            spintax = spintax.replaceAt(points[points.length - 1], subSpintax);
            points.splice(points.length - 1, 1);
        }
    }
	
	
    return spintax.replace(/£|\{|\}/g,"");
}

	function Check_Licence(page)
{
	
if (document.getElementById("l_out").innerHTML.length>50) return true;
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url()?>/wp-admin/admin-ajax.php?action=check_licence&version=<?php $plugin_data = get_plugin_data( __FILE__ );
echo $plugin_data['Version']; ?>",             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){                    
            //$("#responsecontainer").html(response);
			alert(response);
			
			//document.getElementById('title_for_spined').value=response;
			
			jQuery( "#l_out" ).html(response);
		
            
        }

    });
}

function ImportYouTubeText()
{
if (document.getElementById('youtube_url').value!='')
{
jQuery.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo_mode&search=&youtube_url="+encodeURIComponent(document.getElementById('youtube_url').value) +"&captions="+document.getElementById('add_youtube_subtitles').checked ,             
        dataType: "html",   //expect html to be returned  
        async: false,		
        success: function(response){
             //alert(response);			
            //$("#responsecontainer").html(response);
			video_mode_response_array=response.split('|+|+');
		}
	
});
document.getElementById("youtube_text_import").value=document.getElementById("youtube_text_import").value+"\n";
for (i = 0; i < video_mode_response_array.length; i=i+2) 
{
if (video_mode_response_array[i].toString()!='') document.getElementById('youtube_output').innerHTML=document.getElementById('youtube_output').innerHTML+'<input data-title="'+video_mode_response_array[i+1]+'" style="display:none;" name="box[]" type="checkbox" checked="checked" value="https://www.youtube.com/embed/'+ video_mode_response_array[i].split('/')[4]+'">';		
}	
}
var import_array=document.getElementById("youtube_text_import").value.split("\n");
//var x = document.getElementById("youtube_shortcode");

for (i = 0; i < import_array.length; i++) 
{
if (import_array[i].toString()!='') document.getElementById('youtube_output').innerHTML=document.getElementById('youtube_output').innerHTML+'<input style="display:none;" name="box[]" type="checkbox" checked="checked" value="https://www.youtube.com/embed/'+ import_array[i].toString()+'">';	

}

add_links_stack();

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

var formSubmitting = false;
var temp_for_all=false;
var setFormSubmitting = function() { formSubmitting = true; };
window.onload = function() {
    window.addEventListener("beforeunload", function (e) {
        if (formSubmitting || document.getElementById("child_selector").children.length==0) {
            return undefined;
        }

        var confirmationMessage = 'It looks like you have been editing something. '
                                + 'If you leave before saving, your changes will be lost.';

        (e || window.event).returnValue = confirmationMessage; //Gecko + IE
        return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
    });
};
</script>

<!--style="background-color:#f1f1f1;"-->
<div>
<?php add_thickbox(); ?>
<div id="wpserpfuel_update"></div>
<div id="keyword_import_div" style="display:none;" >
<table class="form-table">
<tbody>

<?php 
if (!isset($_GET['backlink']))
{	
?>
<tr>
<th scope="row">
<label for="sites">Number of posts:</label>
<div>
<input style="width:40px" value="2" type="text" id="number_of_posts" >
</div>
</th>
<?php 
}	
?>

<tr <?php if (!isset($_GET['backlink'])){echo 'style="display: none;"';	}?> >
<th scope="row">
<label for="sites">Keywords:</label>
<div >
	            <input onkeyup="if (event.keyCode == 13) document.getElementById('keyword_search_button').click();" value="" type="text" id="keyword_search_string" >
<?php
if ($develop==true)
{
?>
<select id="engine" name="engine">
<option selected value="all">--all--</option>
<option value="google">Google</option>
<option value="yahoo">Yahoo</option>
<option value="bing">Bing</option> 
</select> 
<?php
}
else
{
?>
<select style="display: none;" id="engine" name="engine">
<option selected value="google">Google</option>
</select> 
<?php
}
?>
	            <input id="keyword_search_button" class="button" onclick="this.value='Please wait ...';jQuery(this).prop('disabled', true);setTimeout(function () {keyword_search();document.getElementById('keyword_search_button').value='Search';document.getElementById('keyword_search_button').disabled = false;},500);return false;"  type="button" value="Search" >
				<div id="keyword_output">
				</div>
				
	</div>
</th>
<td>
<textarea id="keyword_import" style="height:250px ;"  name="textarea_keywords"></textarea>



</td>
</tr>

<tr>
<th scope="row">
<label for="sites">Auto-generate content:</label>
<select id="generate_content" name="generate_content" onchange="switch (this.value){
	  case '02': document.getElementById('title_for_spined_row').style='display:auto;';document.getElementById('youtube_row').style='display:none;';document.getElementById('youtube_row2').style='display:none;';document.getElementById('spin_template').style='display:auto;';document.getElementById('article_builder_template').style='display:none;';document.getElementById('title_for_spined').value='';document.getElementById('title_for_spined').value='';document.getElementById('spintext').value=''; break;
      case '03': document.getElementById('title_for_spined_row').style='display:auto;';document.getElementById('youtube_row').style='display:none;';document.getElementById('spin_template').style='display:auto;';document.getElementById('article_builder_template').style='display:none;';document.getElementById('spintext').value=jQuery('<textarea />').html(jQuery( '#wp-content-editor-container' ).find( 'textarea' ).val()).text();document.getElementById('title_for_spined').value=document.getElementById('title').value;break;
	  case '04': document.getElementById('spin_template').style='display:none;';document.getElementById('article_builder_template').style='display:auto;'; break;
	  case '05': document.getElementById('spin_now_button').style='display:auto;';document.getElementById('content_for_spined_row').style='display:auto;';document.getElementById('youtube_row2').style='display:auto;';document.getElementById('youtube_row').style='display:auto;';document.getElementById('title_for_spined_row').style='display:none;';document.getElementById('spin_template').style='display:auto;';document.getElementById('article_builder_template').style='display:none;';break;
   }
">
<option value="01" >None</option>
<option  value="02" >Spin</option>
<!--<option  value="03" >Spin parent article</option>-->
<option  value="04" >Article builder</option>
<?php
if ($develop==true)
{
?>
<option value="05" >Video mode</option>
<?php
}
?>
</select>
</th>
<td style="height: 100px;">
<table id="spin_template" class="form-table" style="display:none">
<tr style="display:none;" id="youtube_row"><td>Youtube chanel<br> or playlist url<br> (optional):</td><td><input style="width:300px;" id="youtube_url_bulk" type="text"></td></tr>
<tr id="youtube_row2"><td></td><td style="padding-left: 50px;">
<input id="add_youtube_details_vm" name="add_youtube" type="radio"><label for="add_youtube_details_vm"><b>Add Details</b></label><input id="add_youtube_subtitles_vm" name="add_youtube" type="radio"><label for="add_youtube_subtitles_vm"><b>Add Subtitles</b></label><input checked id="add_none_vm" name="add_youtube" type="radio"><label for="add_none_vm"><b>None</b></label><br><br>
<input name="youtube_details_position_vm" id="youtube_details_position_1_vm" type="radio">Above article<br><br>
<input name="youtube_details_position_vm" id="youtube_details_position_2_vm" type="radio">Below article<br><br>
<input name="youtube_details_position_vm" id="youtube_details_position_3_vm" type="radio">After paragraph<br><br>
<br><input id="use_ab_for_youtube" type="checkbox">Use Article builder <br><br>Categories:<select id="article_builder_category2"><option> acid reflux</option><option> acne</option><option> acupuncture</option><option> affiliate marketing</option><option> aging</option><option> allergies</option><option> anxiety</option><option> arthritis</option><option> article marketing</option><option> arts and crafts</option><option> asthma</option><option> auto repair</option><option> back pain</option><option> baseball</option><option> basketball</option><option> beauty</option><option> blogging</option><option> camping</option><option> cancer</option><option> car shopping</option><option> carpet cleaning</option><option> cats</option><option> cell phones</option><option> cellulite</option><option> chiropractic care</option><option> coffee</option><option> college</option><option> cooking</option><option> cosmetic surgery</option><option> coupons</option><option> credit cards</option><option> credit repair</option><option> debt consolidation</option><option> dental care</option><option> depression</option><option> desktop computers</option><option> diabetes</option><option> dog training</option><option> dogs</option><option> eczema</option><option> email marketing</option><option> employment</option><option> eye care</option><option> facebook marketing</option><option> fashion</option><option> fishing</option><option> fitness</option><option> football</option><option> forex</option><option> furniture</option><option> gardening</option><option> gold</option><option> golf</option><option> green energy</option><option> hair care</option><option> hair loss</option><option> hemorrhoids</option><option> hobbies</option><option> home business</option><option> home improvement</option><option> home mortgages</option><option> home security</option><option> homeschooling</option><option> hotels</option><option> HVAC</option><option> insomnia</option><option> insurance - auto</option><option> insurance - general</option><option> insurance - health</option><option> insurance - home owner's</option><option> insurance - life</option><option> interior design</option><option> internet marketing</option><option> investing</option><option> ipad</option><option> iphone</option><option> jewelry</option><option> juicing</option><option> landscaping</option><option> laptops</option><option> lawyers</option><option> lead generation</option><option> leadership</option><option> learn guitar</option><option> locksmiths</option><option> make money online</option><option> massage</option><option> memory</option><option> mobile marketing</option><option> multi-level marketing</option><option> muscle building</option><option> music downloads</option><option> network marketing</option><option> nutrition</option><option> online shopping</option><option> organic gardening</option><option> panic attacks</option><option> parenting</option><option> payday loans</option><option> personal bankruptcy</option><option> personal development</option><option> personal finance</option><option> personal injury</option><option> pest control</option><option> photography</option><option> plumbing</option><option> pregnancy</option><option> public speaking</option><option> quit smoking</option><option> real estate - buying</option><option> real estate - commercial</option><option> real estate - selling</option><option> real estate investing</option><option> reputation management</option><option> retirement</option><option> roofing</option><option> search engine optimization</option><option> shoes</option><option> skin care</option><option> sleep apnea</option><option> snoring</option><option> soccer</option><option> social media marketing</option><option> solar energy</option><option> stock market</option><option> stress</option><option> student loans</option><option> teeth whitening</option><option> time management</option><option> tinnitus</option><option> toys</option><option> travel</option><option> video games</option><option> video marketing</option><option> vitamins and minerals</option><option> web design</option><option> web hosting</option><option> weddings</option><option> weight loss</option><option> wine</option><option> woodworking</option><option> wordpress</option><option> yeast infection</option></select><br><br>
</td>

<td style="display:none;"><span>Put shortcodes:</span><br>
<input id="spin_shortcodes" type="checkbox"><label for="spin_shortcodes">Parent link</label><br>
<input id="spin_shortcodes_none" checked type="radio" name="spin_shortcodes_opt"><label for="spin_shortcodes_none">None</label><br>
<input id="spin_shortcodes_image" type="radio" name="spin_shortcodes_opt"><label for="spin_shortcodes_video">Image</label><br>
<input id="spin_shortcodes_video" type="radio" name="spin_shortcodes_opt"><label for="spin_shortcodes_image">Video</label><br>
<input id="spin_shortcodes_video_or_image" type="radio" name="spin_shortcodes_opt"><label for="spin_shortcodes_video_or_image">Image or Video</label><br>
<input id="spin_shortcodes_video_and_image" type="radio" name="spin_shortcodes_opt"><label for="spin_shortcodes_video_and_image">Both</label><br></td></tr>
<tr id="title_for_spined_row"><td>Title:</td><td><input id="title_for_spined" type="text"></td></tr>
<tr id="content_for_spined_row"><td>Content:<br><input id="spin_now_button" onclick="BestSpinner_text(document.getElementById('spintext').value);BestSpinner_title(document.getElementById('title_for_spined').value); return false;" class="button" type="button" value="Spin now" ></td><td><textarea id="spintext" style="height:200px ;width: 300px;"  name="spintext"></textarea></td></tr>
</table>
<table id="article_builder_template" class="form-table" style="display:none">
<tr><td>Categories:</td><td><select id="article_builder_category"><option> acid reflux</option><option> acne</option><option> acupuncture</option><option> affiliate marketing</option><option> aging</option><option> allergies</option><option> anxiety</option><option> arthritis</option><option> article marketing</option><option> arts and crafts</option><option> asthma</option><option> auto repair</option><option> back pain</option><option> baseball</option><option> basketball</option><option> beauty</option><option> blogging</option><option> camping</option><option> cancer</option><option> car shopping</option><option> carpet cleaning</option><option> cats</option><option> cell phones</option><option> cellulite</option><option> chiropractic care</option><option> coffee</option><option> college</option><option> cooking</option><option> cosmetic surgery</option><option> coupons</option><option> credit cards</option><option> credit repair</option><option> debt consolidation</option><option> dental care</option><option> depression</option><option> desktop computers</option><option> diabetes</option><option> dog training</option><option> dogs</option><option> eczema</option><option> email marketing</option><option> employment</option><option> eye care</option><option> facebook marketing</option><option> fashion</option><option> fishing</option><option> fitness</option><option> football</option><option> forex</option><option> furniture</option><option> gardening</option><option> gold</option><option> golf</option><option> green energy</option><option> hair care</option><option> hair loss</option><option> hemorrhoids</option><option> hobbies</option><option> home business</option><option> home improvement</option><option> home mortgages</option><option> home security</option><option> homeschooling</option><option> hotels</option><option> HVAC</option><option> insomnia</option><option> insurance - auto</option><option> insurance - general</option><option> insurance - health</option><option> insurance - home owner's</option><option> insurance - life</option><option> interior design</option><option> internet marketing</option><option> investing</option><option> ipad</option><option> iphone</option><option> jewelry</option><option> juicing</option><option> landscaping</option><option> laptops</option><option> lawyers</option><option> lead generation</option><option> leadership</option><option> learn guitar</option><option> locksmiths</option><option> make money online</option><option> massage</option><option> memory</option><option> mobile marketing</option><option> multi-level marketing</option><option> muscle building</option><option> music downloads</option><option> network marketing</option><option> nutrition</option><option> online shopping</option><option> organic gardening</option><option> panic attacks</option><option> parenting</option><option> payday loans</option><option> personal bankruptcy</option><option> personal development</option><option> personal finance</option><option> personal injury</option><option> pest control</option><option> photography</option><option> plumbing</option><option> pregnancy</option><option> public speaking</option><option> quit smoking</option><option> real estate - buying</option><option> real estate - commercial</option><option> real estate - selling</option><option> real estate investing</option><option> reputation management</option><option> retirement</option><option> roofing</option><option> search engine optimization</option><option> shoes</option><option> skin care</option><option> sleep apnea</option><option> snoring</option><option> soccer</option><option> social media marketing</option><option> solar energy</option><option> stock market</option><option> stress</option><option> student loans</option><option> teeth whitening</option><option> time management</option><option> tinnitus</option><option> toys</option><option> travel</option><option> video games</option><option> video marketing</option><option> vitamins and minerals</option><option> web design</option><option> web hosting</option><option> weddings</option><option> weight loss</option><option> wine</option><option> woodworking</option><option> wordpress</option><option> yeast infection</option></select></td></tr>
<tr style="display:none;" id="article_builder_shortcodes"><td></td><td>
<span>Put shortcodes:</span><br>
<input id="shortcodes" type="checkbox"><label for="shortcodes">Parent link</label><br>
<input id="shortcodes_none"  type="radio" name="shortcodes_opt"><label for="shortcodes_none">None</label><br>
<input id="shortcodes_image" type="radio" name="shortcodes_opt"><label for="shortcodes_video">Image</label><br>
<input id="shortcodes_video" checked type="radio" name="shortcodes_opt"><label for="shortcodes_image">Video</label><br>
<input id="shortcodes_video_or_image" type="radio" name="shortcodes_opt"><label for="shortcodes_video_or_image">Image or Video</label><br>
<input id="shortcodes_video_and_image" type="radio" name="shortcodes_opt"><label for="shortcodes_video_and_image">Both</label><br>
</td></tr>
<tr><td></td><td><br><br><br></td></tr>
</table>
</td>
</tr>
<tr <?php if (!isset($_GET['backlink'])){echo 'style="display: none;"';	}?> >
<th scope="row">
<label for="sites">Backlink HTML:</label>
</th>
<td>
<textarea id="anchortext" style="height:200px ;width: 300px;"  name="anchortext"><!anchor></textarea>
</td>
</tr>
<tr>
<th scope="row">
<label for="sites">Scheduled publishing:</label>
</th>
<td>

<input type="radio" id="scheduled0" name="scheduled" value="0" checked >Publish now<br>
<input type="radio" id="scheduled1" name="scheduled" value="1">Post <input style="width:20px;" id="shredule_times" name="shredule_times" value="1" type="text"> each <input style="width:20px;" id="shredule_days" name="shredule_days" value="1" type="text"> day(s)<br>
</td>
</tr>

</tbody>
</table>
<input id="import_button" class="button" type="button" onclick="this.disabled = true;this.value='Please wait ...';setTimeout(function () {ImportChild();document.getElementById('child_main').style='display:auto;';document.getElementById('keyword_import_div').style='display:none;';document.getElementById('import_button').value='Import posts';document.getElementById('import_button').disabled = false;document.getElementById('TB_closeWindowButton').click();},500)" value="Import posts">

<input class="button" type="button" onclick="document.getElementById('child_main').style='display:auto;';document.getElementById('keyword_import_div').style='display:none;';document.getElementById('TB_closeWindowButton').click();" value="Cancel">

<br><br>

</div>
<div id="child_main" >
<table style="display:none;" id="child_content">
<?php
$i=0;
do
			{
				
			if 	(get_post_meta($post->ID , 'child_title'.$i, TRUE)!='') 
			{ 
		echo '<tr>';
		echo '<td><input value="'.encodeURIComponent(html_entity_decode(get_post_meta($post->ID, 'child_title'.$i,TRUE))).'" name="chld_array_title[]"></td>';
		echo '<td><input value="'.encodeURIComponent(html_entity_decode(get_post_meta($post->ID, 'child_content'.$i,TRUE))).'" name="chld_array_content[]"></td>';
		echo '<td><input value="'.get_post_meta($post->ID, 'child_keyword'.$i,TRUE).'" name="chld_array_keyword[]"></td>';
		echo '<td><input value="'.date('m:d:Y:H:i',get_post_meta($post->ID, 'child_time'.$i,TRUE)).'" name="chld_array_time[]"></td>';
		echo '</tr>';
	
		
		} else {break;}
			$i++;
			} while (true);
?>
</table>
<div class="postbox" style="display:inline-block;width:450px;padding:5px;">
<h4 class="hndle" style="margin: 5px 0 !important;"><span>List of posts:</span></h4>
<table>
<tr><td>
<select onmousedown="temp_for_all=false;if (check_changes_in_vp()=='no') {temp_for_all=false;return false;} else {temp_for_all=true;setTimeout(function (a) {a.children[a.selectedIndex].click();},100,this);}" id="child_selector" size="4" name="decision2" style="height:100px;width:420px !important;">
<?php
$i=0;
do
			{
				
			if 	(get_post_meta($post->ID , 'child_title'.$i, TRUE)!='') 
			{ 
		
		echo '<option onclick="select_child(this);">'.get_post_meta($post->ID, 'child_keyword'.$i,TRUE).'</option>';
	
		} else {break;}
			$i++;
			} while (true);
?>
</select>
</td></tr>
</table>
<br>
<!--<input class="button" type="button" onclick="document.getElementById('child_main').style='display:none;';document.getElementById('keyword_import_div').style='display:auto;';;return false;" value="Import Keyword">-->
<input class="button" type="button" onclick="addChild();return false;" value="New post">
<input class="button" type="button" onclick="delChild();return false;" value="Delete post">
<a onclick="document.getElementById('import_but_main').disabled = true;document.getElementById('import_but_main').value='Please wait ...';setTimeout(function () {document.getElementById('import_but_main').disabled = false;document.getElementById('import_but_main').value='Import posts';},1)" href="#TB_inline&?width=700&height=580&inlineId=keyword_import_div" class="thickbox"><input id="import_but_main" class="button"  type="button" onclick="return false;"  value="Import posts"></a>
<a class="thickbox" href="#TB_inline&?width=750&inlineId=youtube_import_div&width=753&height=477" onclick="document.getElementById('youtube_search_type').value='1';document.getElementById('youtube_output').innerHTML='';">
<input class="button" type="button" value="Add YouTube to posts">
</a>
</div>
<div class="postbox" style="display:none;padding:5px;width:400px;">
<h4 class="hndle" style="margin: 5px 0 !important;"><span>YouTube:</span></h4>
<table>
<tr><td>
<select id="youtube_shortcode" size="4" name="decision2" style="height:100px;width:200px !important;">
</select>
</td><td>

</td></tr>
</table>
<input id="youtube_list" name="youtube_list" type="hidden" value="">
<br>
<input class="button" type="button" onclick="clear_links_stack();return false;" value="Clear">
<a onclick="document.getElementById('youtube_search_type').value='1';document.getElementById('youtube_output').innerHTML='';" href="#TB_inline&?width=750&height=800&inlineId=youtube_import_div" class="thickbox"><input class="button" type="button"  value="Add YouTube links"></a>
</div>

<br><br>
<?php 
if (isset($_GET['backlink']))
{	
?>
<b>Backlink:</b><br>
<span id="backlink_value" style="display:none;"><?php echo site_url();?>/?post_type=page&p=<?php echo $_GET['backlink']?></span>
<span><a id="backlink_value_href" target="blank" href="<?php echo get_permalink($_GET['backlink']);?>"><?php echo get_permalink($_GET['backlink']);?></a></span><br><br>

<?php 
}
?>
<b>Category:</b><br>

<input type="text" autocomplete="off" spellcheck="true" size="30" id="child_post_category" name="child_post_category" value="">
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
</select> <br><br>

<b>Title:</b><br>
<input type="text" autocomplete="off" spellcheck="true" value="" size="30" id="child_post_title"><br><br>
<b>Meta-description:</b><br>
<input type="text" autocomplete="off" spellcheck="true" value="" size="30" id="child_post_meta_description"><br><br>



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
	wp_editor( htmlspecialchars_decode($value), "child1_editor", $settings  );
	
	//wp_register_script( 'tinymin.js', 'http://147.91.204.66/wordpress/wp-includes/js/tinymce/tinymce.min.js?ver=4205-20150910');
	//wp_register_script( 'compat.js', 'http://147.91.204.66/wordpress/wp-includes/js/tinymce/plugins/compat3x/plugin.min.js?ver=4205-20150910');
	//wp_register_script( 'tiny.js', plugin_dir_url(__FILE__).'js/tiny.js');
	
    //wp_enqueue_script( 'tinymin.js' );
	//wp_enqueue_script( 'compat.js' );
	//wp_enqueue_script( 'tiny.js' );

?>

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
</div>
<table>
<tr>
<td>
			<?php
if ($develop==true)
{
?>
<br>Add ID's of youtube videos to add:<br>
<textarea id="youtube_text_import" style="height:150px ;"  name="youtube_text_import"></textarea><br><br>
<input onclick="ImportYouTubeText();document.getElementById('TB_closeWindowButton').click();" value="Add" class="button" type="button">
	<?php
}
?>
</td>
<td style="padding-left: 50px;">
<input id="add_youtube_details" name="add_youtube" type="<?php echo (true==true ? 'radio':'checkbox'); ?>"><label for="add_youtube_details"><b>Add Details</b></label>	<?php

//if (true==true)
{
?><input id="add_youtube_subtitles" name="add_youtube" type="radio"><label for="add_youtube_subtitles"><b>Add Subtitles</b><input id="add_none" name="add_youtube" type="radio"><label for="add_none"><b>None</b></label></label><?php
}
?><br><br>

<input name="youtube_details_position" id="youtube_details_position_1" type="radio">Above article<br><br>
<input name="youtube_details_position" id="youtube_details_position_2" type="radio">Below article<br><br>
<input name="youtube_details_position" id="youtube_details_position_3" type="radio">After paragraph<br><br>
</td>
</tr>
</table>

	
				<div id="youtube_output">
				</div>
				
	</div>
	


<div style="padding:8px;">
<!--<a onclick="document.getElementById('youtube_search_type').value='0';document.getElementById('youtube_output').innerHTML='';" href="#TB_inline&?width=750&height=800&inlineId=youtube_import_div" class="thickbox"><input class="button" type="button" value="Add YouTube video"></a>
<a onclick="document.getElementById('flickr_search_type').value='0';document.getElementById('flickr_output').innerHTML='';" href="#TB_inline?&width=750&height=800&inlineId=flickr_import_div" class="thickbox"><input class="button" type="button" value="Add Flickr image"></a>
-->
<div style="display:none;"><input class="button" type="button" onclick="jInsertEditorText_youtube('link');return false;" value="Add YouTube shortcode">
   <span> Keyword: </span><strong><span id="child_main_keyword"></span></strong></div>
<div style="float:right; text-align: right;"><span><strong>Publish: </strong></span><label><span class="screen-reader-text">Month</span><select id="mm_child" name="mm_child">
			<option value="01" data-text="Jan" <?php echo (date("n")==1?'selected':'');?> >01-Jan</option>
			<option value="02" data-text="Feb" <?php echo (date("n")==2?'selected':'');?> >02-Feb</option>
			<option value="03" data-text="Mar" <?php echo (date("n")==3?'selected':'');?> >03-Mar</option>
			<option value="04" data-text="Apr" <?php echo (date("n")==4?'selected':'');?> >04-Apr</option>
			<option value="05" data-text="May" <?php echo (date("n")==5?'selected':'');?> >05-May</option>
			<option value="06" data-text="Jun" <?php echo (date("n")==6?'selected':'');?> >06-Jun</option>
			<option value="07" data-text="Jul" <?php echo (date("n")==7?'selected':'');?> >07-Jul</option>
			<option value="08" data-text="Aug" <?php echo (date("n")==8?'selected':'');?> >08-Aug</option>
			<option value="09" data-text="Sep" <?php echo (date("n")==9?'selected':'');?> >09-Sep</option>
			<option value="10" data-text="Oct" <?php echo (date("n")==10?'selected':'');?> >10-Oct</option>
			<option value="11" data-text="Nov" <?php echo (date("n")==11?'selected':'');?> >11-Nov</option>
			<option value="12" data-text="Dec" <?php echo (date("n")==12?'selected':'');?> >12-Dec</option>
</select></label> <label><span class="screen-reader-text">Day</span><input id="jj_child" name="jj_child" value="<?php echo date("d"); ?>" size="2" maxlength="2" autocomplete="off" type="text"></label>, <label><span class="screen-reader-text">Year</span><input id="aa_child" name="aa_child" value="<?php echo date("Y"); ?>" size="4" maxlength="4" autocomplete="off" type="text"></label> @ <label><span class="screen-reader-text">Hour</span><input id="hh_child" name="hh_child" value="<?php echo date("H"); ?>" size="2" maxlength="2" autocomplete="off" type="text"></label>:<label><span class="screen-reader-text">Minute</span><input id="mn_child" name="mn_child" value="<?php echo date("i"); ?>" size="2" maxlength="2" autocomplete="off" type="text"></label></div></div>
<a style="display: none;" id="click_temp" onclick="insertAtCursor(document.getElementById('site_banner_html'),'!from_media_library!'); document.getElementById('TB_closeWindowButton').click();setTimeout(function () {tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true')},500);return false;" href="https://pinterest.com/pin/create/button/?url=<?php echo site_url(); ?>/index.php/2015/12/18/iopiup/&amp;media=_link_to_image&amp;description=%28Post%29+iopiup+%26%238211%3B+test" target="_blank" title="Pin it"><img src="<?php echo site_url();?>/wordpress/wp-content/plugins/wpanalyst/img/sharer/Pinterest.png"></a>

<div style="display:none;" id="banners_add_div">

<table class="form-table">
<tbody>
<tr>
</tr><tr>
<th scope="row">
<label for="banner_position">Position:</label>
</th>
<td>
<select id="banner_position"><!--onchange="banner_html_template(this.selectedIndex);"-->
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
<textarea name="site_banner_html" style="height:250px ;width: 400px;" id="site_banner_html"></textarea><br>
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
<br>
<div class="postbox" style="display:inline-block;padding:5px;width:50%;">
<h4 class="hndle" style="margin: 5px 0 !important;"><span>Banners:</span></h4>
<table style="width:100% !important;">
<tr><td>
<select id="banners" size="4" name="decision2" style="height:100px; width:100% !important;">
</select>
</td>
</table>
<br>
<a id="add_baner_button" onmousedown="if (document.getElementById('child_selector').selectedIndex == -1) {alert('Please, select post from list of posts.');document.getElementById('TB_closeWindowButton').click();}" href="#TB_inline&?width=750&height=800&inlineId=banners_add_div" class="thickbox"><input onclick="document.getElementById('site_banner_html').value=document.getElementById('site_banner_html').value.replace('!from_media_library!','');" class="button" type="button"  value="Add Banner"></a>
<input class="button" type="button" onclick="delBanner();return false;" value="Del Banner">
</div>
<div style="text-align: left;"><input  class="button" type="button" onclick="save_child();return false;" value="Apply changes"></div>
<div style="text-align: left;"><br><br><input  class="button button-primary button-large" type="submit" value="Submit"></div>
</div>	

<script  type="text/javascript">

function banner_html_template(selector)
{
	
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
save_child();	
}
function delBanner()
{
	if (confirm('Remove banner? ')==false) return false;
	var x = document.getElementById("banners");
    x.removeChild(x[x.selectedIndex]);

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
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=vkeyword_research_video&engine="+document.getElementById("engine").value+"&search="+encodeURIComponent(document.getElementById('keyword_search_string').value),             
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
        url: "<?php echo site_url();?>/wp-admin/admin-ajax.php?action=youtube_wpvideo&type_of_search="+document.getElementById('youtube_search_type').value+"&search="+encodeURIComponent(document.getElementById('youtube_search_string').value)<?php if ($develop==true){?> +"&youtube_url="+encodeURIComponent(document.getElementById('youtube_url').value)+"&captions="+document.getElementById('add_youtube_subtitles').checked <?php } ?>,             
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
<?php
	

}
elseif (isset($_GET['edit']))
{
	
	include_once('edit_post.php');
}
else
{	
if (isset($_POST) && !isset($_GET['edit']))
{
	
	

if ($develop!=true)
{

if 	(count($_POST['chld_array_content'])>14) {echo '<p>Only 15 post alowed same time in this version. No limits for Enterprise.</p>';exit();};

}

	
	
	$youtube_list=explode (',',	$_POST['youtube_list']);
	$iyt=0;

	
	global $wpdb;
    $table_name = $wpdb->prefix . "wp_video";	
	
	for($i=0; $i < count($_POST['chld_array_content']);$i++)
            {
				   $table_name = $wpdb->prefix . "wp_video";	
				
			$main_content=str_replace('###YouTube###',($iyt<count($youtube_list) ? '<iframe title="YouTube video player" class="youtube-player" type="text/html" width="604" height="340" src="'.$youtube_list[$iyt+1].'" frameborder="0" allowFullScreen></iframe>'  : ''),urldecode($_POST['chld_array_content'][$i]));
			if (strpos(urldecode($_POST['chld_array_content'][$i]),'###YouTube###')) $iyt=$iyt+1;
		
			$main_content=str_replace('###Link###','<a href="'.get_post_permalink().'">'.$_POST['chld_array_keyword'][$i].'</a>',$main_content);

			$time_array=explode(":",$_POST['chld_array_time'][$i]);
	//==========================
	
	$video_url=stringURLSafe($_POST['chld_array_title'][$i]=='' ? '(Untitled)':urldecode($_POST['chld_array_title'][$i]));
	do
	{
	$table_name = $wpdb->prefix . "wp_video";
	$video_url_list= $wpdb->get_results( "SELECT video_url FROM ".$table_name." WHERE video_url = '".$video_url."' " );
		if (count($video_url_list)>0)
			{
				if (substr($video_url,-2,1)=='_' || substr($video_url,-3,1)=='_' || substr($video_url,-4,1)=='_')
				{
					if (substr($video_url,-2)=='_9')
					{
						$video_url=substr($video_url,0, -2).'_10';
					}
					elseif (substr($video_url,-2)=='_99')
					{
						$video_url=substr($video_url,0, -3).'_100';
					}
					else
					{	
					++$video_url;
					}
					
				}
				else
				{	
				$video_url=$video_url.'_1';
				}
			}
	}
	while (count($video_url_list)>0);
	$wpdb->insert( 
	$table_name, 
	array( 
	    'keyword' => htmlspecialchars($_POST['chld_array_keyword'][$i]),
		'video_url' => $video_url,	
		'title' => htmlspecialchars(trim($_POST['chld_array_title'][$i])=='' ? '(Untitled)': str_replace('<!keyword>',$_POST['chld_array_keyword'][$i],stripslashes(urldecode($_POST['chld_array_title'][$i])))),
		'meta_description'=> htmlspecialchars(stripslashes(urldecode($_POST['chld_array_meta_description'][$i]))),
		'article_text' => stripslashes(htmlspecialchars($main_content)),
		'category' => ($_POST['child_post_category']=='' ? 'Untitled':$_POST['child_post_category']),
		'featured' => '',
		'date' => strtotime($time_array[1].'-'.$time_array[0].'-'.$time_array[2].' '.$time_array[3].':'.$time_array[4]),
		
	)
	);
	$lastid = $wpdb->insert_id;
//----------------------------------------------
	
$temp_banner=urldecode($_POST['chld_array_banners'][$i]);
$temp_banner_a=explode("\n",$temp_banner);


$table_name = $wpdb->prefix . "wp_video_banners"; 
foreach($temp_banner_a as $value)
{	
$value_a=explode("+|+",$value);


if (count($value_a)>1)
{
$wpdb->insert( 
	$table_name, 
	array( 
	    'type' => 'individual',
		'category' => $lastid,	
		'position' => $value_a[0],
		'html_text' => $value_a[1]
	)
	);
}
}	
	//================
			
			}
}	
global $wpdb;
	$table_name = $wpdb->prefix . "wp_video";
	$rezultat= $wpdb->get_results( "SELECT id,keyword,video_url,title,article_text,category,featured,date FROM ".$table_name." ORDER BY featured DESC,id DESC " );
	
	
?>
<script  type="text/javascript">
function rename_category(old_name)
{
	var new_name = prompt("Please enter new category name", old_name);
	//document.getElementById('old_category_name').value=old_name;
	if (new_name==null || new_name=='') return false;
	document.getElementById('new_category_name').value=new_name;
	document.getElementById('main_form').submit();
}
function video_site_set_at_frontpage_f()
{
    document.getElementById('video_site_set_at_frontpage').value='set';
	document.getElementById('main_form').submit();
}
function Post_at_social_syndication_f()
{
	
	jQuery( "<div id=\"synd_output\"><p>Please wait ...</p></div>" ).insertBefore('h2');
	 var winHeight=520;
			 var winWidth=450;
			 var winTop = (screen.height / 2) - (winHeight / 2);
        var winLeft = (screen.width / 2) - (winWidth / 2);
	window.open('<?php echo site_url().'/wp-admin/admin-ajax.php?action=wpvs_syndicate';?>', 'syndicate', 'scrollbars=yes, top=' + winTop + ',left=' + winLeft + ',menubar=no,toolbar=no,status=0,width=' + winWidth + ',height=' + winHeight);
		
	return false;
	document.getElementById('wp_video_tumblr_post').value=(document.getElementById('wp_video_tumblr_post_t').checked ? document.getElementById('wp_video_tumblr_post_t').value:'');
	document.getElementById('wp_video_twitter_post').value=(document.getElementById('wp_video_twitter_post_t').checked ? document.getElementById('wp_video_twitter_post_t').value:'');
	document.getElementById('wp_video_pinterest_post').value=(document.getElementById('wp_video_pinterest_post_t').checked ? document.getElementById('wp_video_pinterest_post_t').value:'');
    document.getElementById('TB_closeWindowButton').click();
	document.getElementById('Post_at_social_syndication').value='set';
	document.getElementById('main_form').submit();
}
</script>
<h2>WpVideoSites:</h2>
<iframe frameborder="0" src="../wp-admin/admin-ajax.php?action=wpvideo_up&amp;version=<?php $plugin_data = get_plugin_data( plugin_dir_path (__FILE__ ).'wpvideo.php' );
echo $plugin_data['Version']; ?>" style="width: 700px; height:40px;" id="search_keyword"></iframe>
<!--
<div id="keyword_import_div" style="display:none;">
<textarea id="keyword_import" name="textarea_keywords" style="height:250px ;width:400px;"></textarea>
<br>
<br>
<input id="import_button" class="button" type="button" onclick="this.disabled = true;this.value='Please wait ...';setTimeout(function () {ImportChild();document.getElementById('keyword_import_div').style='display:none;';document.getElementById('import_button').value='Import keywords';document.getElementById('import_button').disabled = false;document.getElementById('TB_closeWindowButton').click();},500)" value="Import keywords">
<input class="button" type="button" onclick="document.getElementById('keyword_import_div').style='display:none;';document.getElementById('TB_closeWindowButton').click();" value="Cancel">
<br>
<br>
</div>
<a class="thickbox" href="#TB_inline&?width=700&inlineId=keyword_import_div&width=753&height=477">
<input id="import_but_main" class="button" type="button" value="Add Category" onclick="document.getElementById('keyword_import').value=''; return false;">
</a>
-->
<p>

<a href="admin.php?page=WpVideo&add=true">
<input id="import_but_main" class="button" type="button" value="Add Posts"></a> <input onclick="video_site_set_at_frontpage_f();return false;"  class="button" type="button" value="Set at front page">
<input onclick="Post_at_social_syndication_f();return false;" class="button" type="button" value="Syndicate">
 <input onclick="rename_category();return false;"  class="button" type="button" value="Rename category"> <input onclick="if (confirm('Remove selected posts? ')==false) return false;" id="delete_selected" class="button" type="submit" value="Delete" >
<input type="hidden" id="old_category_name" name="old_category_name">
<input type="hidden" id="new_category_name" name="new_category_name">
<input type="hidden" id="video_site_set_at_frontpage" name="video_site_set_at_frontpage">


</p>
<table class="wp-list-table widefat fixed striped pages">
	<thead>
	<tr>
		<td class="manage-column column-cb check-column" id="cb"><label for="cb-select-all-1" class="screen-reader-text">Select All</label><input type="checkbox" id="cb-select-all-1"></td><th class="manage-column column-title column-primary sorted asc" id="title" scope="col"><a ><span>Title</span><span class="sorting-indicator"></span></a></th><th class="manage-column column-comments num" scope="col">Front page</th><th class="manage-column column-author" id="category" scope="col">Category</th><th class="manage-column column-comments num sortable desc" id="Featured" scope="col"><a><span><span title="Featured" class="vers"><span class="screen-reader-text">Featured</span></span></span><span class="sorting-indicator"></span></a></th><th class="manage-column column-date sortable asc" id="date" scope="col"><a><span>Date</span><span class="sorting-indicator"></span></a></th>	</tr>
	</thead>

	<tbody id="the-list">
	<?php
	foreach ($rezultat as $value) {
	echo '<tr class="iedit author-self level-0 post-1097 type-page status-draft hentry" id="post-1097">
	
	
	
	<th class="check-column" scope="row">			<label for="cb-select-1097" class="screen-reader-text">Select (Untitled)</label>
			<input type="checkbox" value="'.$value->id.'" name="post[]" id="cb-select-'.$value->id.'">
			<div class="locked-indicator"></div>
		</th>
	<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
			<a aria-label="“(Untitled)” (Edit)" href="admin.php?page=WpVideo&edit=true&id='.$value->id.'" class="row-title">'.htmlspecialchars_decode($value->title).'</a> 
			</td><td>'.($value->featured?'yes':'').'</td>
	<td>'.$value->category.($develop==true ? ' (<a target="_blank" href="'.site_url().'?feed=video-feed&category='.$value->category.'">RSS</a> | <a target="_blank" href="'.site_url().'?feed=video-sitemap&category='.$value->category.'">Sitemap</a>)':'').'</td>
	<td data-colname="Comments" class="comments column-comments">
		</td>
		
		<td data-colname="Date" class="date column-date">'.($value->date < time()? 'Published':' <font color="red">Not Published</font>').'<br><abbr title="2016/02/29 2:31:24 pm">'.date("Y-m-d G:i:s",$value->date) .'</abbr></td>		</tr>
		
		';
	}
	?>
		</tbody>

	<tfoot>
	<tr><td class="manage-column column-cb check-column"><label for="cb-select-all-2" class="screen-reader-text">Select All</label><input type="checkbox" id="cb-select-all-2"></td><th class="manage-column column-title column-primary sorted asc" scope="col"><a><span>Title</span><span class="sorting-indicator"></span></a></th><th>Front page:</th><th class="manage-column column-author" scope="col">Category</th><th class="manage-column column-comments num sortable desc" scope="col"><a href=""><span><span title="Comments" class="vers"><span class="screen-reader-text">Comments</span></span></span><span class="sorting-indicator"></span></a></th><th class="manage-column column-date sortable asc" scope="col"><a><span>Date</span><span class="sorting-indicator"></span></a></th>	</tr>
	</tfoot>

</table>
<?php
}
?>
</form>
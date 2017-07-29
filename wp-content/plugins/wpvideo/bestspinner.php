<?php
function curl_post($url, $data, &$info){

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, curl_postData($data));
  //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  $html = trim(curl_exec($ch));
  curl_close($ch);

  return $html;
}

function curl_postData($data){

  $fdata = "";
  foreach($data as $key => $val){
    $fdata .= "$key=" . urlencode($val) . "&";
  }

  return $fdata;

}

$url = 'http://thebestspinner.com/api.php';

#$testmethod = 'identifySynonyms';
$testmethod = 'replaceEveryonesFavorites';


# Build the data array for authenticating.

$data = array();
$data['action'] = 'authenticate';
$data['format'] = 'php'; # You can also specify 'xml' as the format.

# The user credentials should change for each UAW user with a TBS account.
if (isset($_GET['username']))
{
$data['username'] = $_GET['username'];
$data['password'] = $_GET['pass'];
}
else
{
$data['username'] = get_option( 'wp_video_post_bs_username','' );
$data['password'] = get_option( 'wp_video_post_bs_password','' );
}
# Authenticate and get back the session id.
# You only need to authenticate once per session.
# A session is good for 24 hours.
$output = unserialize(curl_post($url, $data, $info));

if($output['success']=='true'){
  # Success.
  $session = $output['session'];
  
  # Build the data array for the example.
  $data = array();
  $data['session'] = $session;
  $data['format'] = 'php'; # You can also specify 'xml' as the format.
  //$data['text'] = 'Losing weight can be a difficult thing to do.';
 $content=$_POST['string'];
  if (strpos($_POST['string'], '0043500352333id=')==3)
  {

	  $content_post = get_post(str_replace('5350043500352333id=','',$_POST['string']));
$content = html_entity_decode($content_post->post_title);

	  }
  if (strpos($_POST['string'], '00435003534543id=')==3){
	 
	   $content_post = get_post(str_replace('53500435003534543id=','',$_POST['string']));
$content = html_entity_decode($content_post->post_content);

	  
  }
  

  $data['text'] = $content;
  $data['action'] = $testmethod;
  $data['maxsyns'] = '3'; # The number of synonyms per term.
  
  if($testmethod=='replaceEveryonesFavorites'){
    # Add a quality score for this method.
    $data['quality'] = '1';
  }

  # Post to API and get back results.
  $output = curl_post($url, $data, $info);
  $output = unserialize($output);
  
  # Show results.
 // echo "<p><b>Method:</b><br>$testmethod</p>";
  //echo "<p><b>Text:</b><br>$data[text]</p>";
  
  $data['action'] = 'apiQuota';
  $quota = curl_post($url, $data, $info);
  $quota = unserialize($quota);
  
  if($output['success']=='true'){
	  echo stripslashes(str_replace("\r", "<br>", $output['output']));
   // echo "<p><b>Output:</b><br>" . str_replace("\r", "<br>", $output['output']) . "</p><p>Remaining quota: $quota[output]</p>";
  }
  else{
    echo "<p><b>Error:</b><br>$output[error]</p>";
  }
}
else{
  # There were errors.
  echo "<p><b>Error:</b><br>$output[error]</p>";
}
?>
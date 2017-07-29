<?php
function str_replace_first($from, $to, $subject)
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $subject, 1);
}
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

$url = 'http://articlebuilder.net/api.php';

$testmethod = 'buildArticle';
#$testmethod = 'injectContent';

# Build the data array for authenticating.

$data = array();
$data['action'] = 'authenticate';
$data['format'] = 'php'; # You can also specify 'xml' as the format.

# Change to your own username/password.
if (isset($_GET['username']))
{
$data['username'] = $_GET['username'];
$data['password'] = $_GET['pass'];
}
else
{
$data['username'] = get_option( 'wp_video_post_ab_username','' );
$data['password'] = get_option( 'wp_video_post_ab_password','' );
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
  $data['action'] = $testmethod;  
  $data['apikey'] = $apikey;
  $data['category'] =$_GET['category']; //'weight loss';
  
  if($testmethod=='buildArticle'){
    //$data['subtopics'] =  "youtube";//$_GET['keyword'];
    $data['wordcount'] = intval(get_option( 'wp_video_post_ab_wordcount','500' ));
	$data['generatenow']=1;
	$data['phrasesonly']=1;
	$data['lsireplacement']=1;
	$data['superspun']=intval(get_option( 'wp_video_post_ab_superspun','1' ));
  }
  elseif($testmethod=='injectContent'){
    $data['article'] = file_get_contents('temp.txt');    
    $data['keywords'] = 'nutrition';
    $data['volume'] = 2;
    $data['style'] = 3;
  }  

  # Post to API and get back results.
  $output = curl_post($url, $data, $info);
  $output = unserialize($output);
  
  # Show results.
  //echo "<p><b>Method:</b><br>$testmethod</p>";
  //echo "<p><b>Text:</b><br>$data[article]</p>";
  
  $data['action'] = 'categories';
  $cats = curl_post($url, $data, $info);
  $cats = unserialize($cats); 

  $data['action'] = 'apiQueries';
  $queries = curl_post($url, $data, $info);
  $queries = unserialize($queries);

  $data['action'] = 'apiMaxQueries';
  $maxqueries = curl_post($url, $data, $info);
  $maxqueries = unserialize($maxqueries);

  if($output['success']=='true'){
	 // $output['output']=substr_replace("<>\r", "\rYoutube\r", $output['output']);
    echo str_replace("\r", "<br>", str_replace("\n\n", "</p><p>", $output['output'])) . "</p>";
    
    //echo "<p><ul><b>Categories</b><select>";
    //foreach($cats['output'] as $cat){
    //  echo "<option> $cat";
    //}
    //echo "</option></select></p>";

    //echo "<p>Today's Queries: $queries[output] (Max = $maxqueries[output])</p>";
    
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
<?php

	/*
	* Note: Spin Rewriter API server is using a 120-second timeout.
	* Client scripts should use a 150-second timeout to allow for HTTP connection overhead.
	*/
	set_time_limit(150);

	$data = array();
	
	// Spin Rewriter API settings - authentication:
	if (isset($_GET['username']))
{
$data['email_address'] = $_GET['username'];
$data['api_key'] = $_GET['pass'];
}
else
{
	$data['email_address'] = get_option( 'wp_video_post_sr_username','' );				// your Spin Rewriter email address goes here
	$data['api_key'] = get_option( 'wp_video_post_sr_password','' );		// your unique Spin Rewriter API key goes here
}	
	
	
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
	// Spin Rewriter API settings - request details:
	$data['action'] = "text_with_spintax";						// possible values: 'api_quota', 'text_with_spintax', 'unique_variation', 'unique_variation_from_spintax'
	$data['text'] = $content;
	$data['protected_terms'] = '';//"John\nDouglas Adams\nthen";		// protected terms: John, Douglas Adams, then
	$data['auto_protected_terms'] = "false";					// possible values: 'false' (default value), 'true'
	$data['confidence_level'] = "medium";						// possible values: 'low', 'medium' (default value), 'high'
	$data['nested_spintax'] = "true";							// possible values: 'false' (default value), 'true'
	$data['auto_sentences'] = "false";							// possible values: 'false' (default value), 'true'
	$data['auto_paragraphs'] = "false";							// possible values: 'false' (default value), 'true'
	$data['auto_new_paragraphs'] = "false";						// possible values: 'false' (default value), 'true'
	$data['auto_sentence_trees'] = "false";						// possible values: 'false' (default value), 'true'
	$data['spintax_format'] = "{|}";							// possible values: '{|}' (default value), '{~}', '[|]', '[spin]', '#SPIN'
	



	
	// Don't change anything below this comment.
	
	// Make the actual API request and save the JSON response.
	$api_response = spinrewriter_api_post($data);
	
	// Output raw JSON response (as a string).
	//echo "<b>Raw JSON response:</b>     <br /><br />     \n\n";
	//print_r($api_response);
	//echo "<br /><br /><br /><br />     \n\n\n\n";
	
	// Output interpreted JSON response (as a native PHP array).
	$api_response_interpreted = json_decode($api_response, true);
	
	echo stripslashes(str_replace("\n", "<br>",$api_response_interpreted['response']));
	//echo "<b>Interpreted JSON response:</b>     <br /><br />     \n\n<pre>";
	//print_r($api_response_interpreted);
	//echo "</pre><br /><br /><br /><br />     \n\n\n\n";
	
	/*
	 * Example of interpreted JSON response (success):
	 * Array
	 * (
     *  	[status] => OK
     *  	[response] => John will {book|make a reservation for|reserve|} a room. Then he will read {a book|a novel} by Douglas Adams.
     *  	[api_requests_made] => 7
     *  	[api_requests_available] => 43
     *  	[protected_terms] => john, douglas adams, then
     *  	[confidence_level] => medium
	 * )
	 * 
	 * Example of interpreted JSON response (error):
	 * Array
	 * (
     *  	[status] => ERROR
     *  	[response] => API quota exceeded. You can make 100 requests per day.
	 * )
	 */
	
	
	
	/**
	 * Sends a request to the Spin Rewriter API and returns the unformatted response.
	 * @param $data
	 */
	function spinrewriter_api_post($data){
		$data_raw = "";
		foreach ($data as $key => $value){
			$data_raw = $data_raw . $key . "=" . urlencode($value) . "&";
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www.spinrewriter.com/action/api");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_raw);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = trim(curl_exec($ch));
		curl_close($ch);
		return $response;
	}

?>
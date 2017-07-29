<?php

$site_url = 'http://wpvideosites.com/licence/pluginvalidate.php';
if (isset($_POST["bmll_transid"]))
{

	$ch = curl_init();


	$timeout = 10; // set to zero for no timeout


	curl_setopt ($ch, CURLOPT_URL, $site_url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);


	$domain = $_SERVER['HTTP_HOST'];


	$postData = 'access='.$_POST["bml_access"].'&transsid='.$_POST["bmll_transid"].'&site='.$domain.'&checkit=checkit';


	curl_setopt ($ch, CURLOPT_POSTFIELDS, $postData);




	$authorise = curl_exec($ch);


	curl_close($ch);



	
if (($authorise=="Authorised!") or ($authorise=="Enterprise licence authorised!"))
{
//$settings=parse_ini_file('settings.ini');
//if (!isset($settings['active']))
//{	
//include_once("../../../wp-config.php");
update_option( 'werwerwpfg678_4567_temp', "1" );
if ($authorise=="Enterprise licence authorised!") update_option( 'werwerwpfg678_4567_temp_dev', "1" );
update_option( 'werwerwpfg678_4567_temp_dev_trans_id', $_POST["bmll_transid"] );
//$fp = fopen('settings.ini', 'a');
//fwrite($fp, 'active=1');
//fclose($fp);
	echo $authorise;
	echo '<br><a onclick="window.top.location.replace(\''.get_site_url().'/wp-admin/admin.php?page=WpVideo-settings\');return false;" href="./admin.php?page=WpVideo-settings">Go to settings page</a>';
	exit();
//}
}

if ($authorise=="")	$authorise= "This plugin is blocked by firewall. Please check your server settings. ";


}





echo '<h3>License:</h3>';

echo '<form action="" method="post" style="background-color:#DFDFDF; padding:20px; border-radius:5px; width:560px;">';

echo '<table cellspacing="10px" cellpadding="10px" style="background-color:#DFDFDF; padding:20px; border-radius:5px; width:560px;">';

// $access=get_option('bml_access');

 //$trans_id=get_option('bmll_transid');

//if($access!='' && $trans_id!='') {
if (isset($authorise))
{
	
	echo '<tr><td colspan="2"><b>'.$authorise.'</b></td></tr>';

}
    

    echo '<tr>';

        echo '<td style="width:160px;">Access key:</td>';

	echo '<td><input  style="width:330px" type="text" name="bml_access"  /></td>';

	echo '</tr>';

        echo '<tr valign="top">';

	echo '<td scope="row">Transaction ID:</td>';

	echo '<td><input  style="width:330px" type="text" name="bmll_transid"  /></tr>';

         echo '<tr valign="top">';

	echo '<td scope="row"></td>';

	echo '<td><input type="submit"  class="button" name="licence" value="Submit" /><td></tr>';
echo '</tr>';

	

	echo '</table>';

        echo '</form>';

        echo '<br>';
//}
?>
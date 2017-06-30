<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Health Care Companies</title><!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
<?php
error_reporting( 2 );
//session_start();
$servername = "vdb1b.pair.com";
$username = "working_44";
$password = "TFmu223W";
$dbname = "working_homehealth";
$table = "companies"; 
// Create connection
$conn = mysqli_connect( $servername, $username, $password );
// Check connection
if ( !$conn ) {
    die( "Connection failed: " . mysqli_connect_error() );
    echo( "Connection failed: " . mysqli_connect_error() );
    echo "<br>";
}
$db = mysqli_select_db( $conn, $dbname );

if ( !$db ) {
    die( "Connection failed: " . mysqli_connect_error() );
    echo "<br>";
}
$sql = "SELECT * FROM " . $table;
$sql .= " ORDER BY name";
echo($sql);
$result = $conn->query( $sql );

if ( $result->num_rows > 0 ) {
    echo '<section class="container text-left">';
while($row = $result->fetch_assoc()) {
    echo '<div class="row alert-info">';
    echo PHP_EOL;
    echo '<div class="col-sm-4">';
    echo PHP_EOL;
    echo '<h3>'.$row[ "name" ] .'</h3>';
    echo PHP_EOL;
    echo '</div>';
    echo PHP_EOL;
    echo '<div class="col-sm-2">';
    echo '<h3>'.$row[ "location" ] .'</h3>';
    echo '</div>';
    echo PHP_EOL;
    echo '<div class="col-sm-2">';
    echo '<h3>'.$row[ "phone" ] .'</h3>';
    echo '</div>';
    echo PHP_EOL;
    echo '</div>';
    echo PHP_EOL;
    echo '<div class="row">';
    echo '<h3>'.$row[ "url" ] .'</h3>';
    echo '</div>';
    echo PHP_EOL;
    echo '<div class="row">';
    echo PHP_EOL;
    echo '<h3>'.$row[ "tagline" ] .'</h3>';
    echo '</div>';
    echo PHP_EOL;
    echo '<div class="row">';
    echo '<h3>'.$row[ "banner" ] .'</h3>';
    echo '</div>';
    echo PHP_EOL;
    echo '<div class="row">';
    echo '<h3>'.$row[ "square logo" ] .'</h3>';
    echo '</div>';
    echo PHP_EOL;
echo '</div>';
    echo PHP_EOL;
echo '<hr>';
     }
} else {
     echo "0 results";
}
echo PHP_EOL;
echo '</section>';
?>
</body>
</html>
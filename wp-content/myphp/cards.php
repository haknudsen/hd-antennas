<?php
error_reporting( 2 );
//session_start();
$servername = "vdb1a.pair.com";
$username = "working_48";
$password = "3LqFcC8C";
$dbname = "working_antennas";
$table = "models";
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
echo '<br>';
$result = $conn->query( $sql );
if ( $result->num_rows > 0 ) {
    echo '<div class="alert alert-info" role="alert">';
    echo '<h3 class="alert-heading text-center">LAVA HD Outdoor Antennas</h3>';
    echo '</div>';
    echo '<div class="row">';
    echo PHP_EOL;
    while ( $row = $result->fetch_assoc() ) {
        echo PHP_EOL;
        echo '<div class="col-lg-3">';
        echo PHP_EOL;
        echo '<div class="card">';
        echo PHP_EOL;
        echo '<img class="card-img-top img-fluid" src="' . $row[ "ad" ] . '"/>';
        echo PHP_EOL;
        echo '<div class="card-block">';
        echo PHP_EOL;
        echo '<h4 class="card-title">' . $row[ "model" ] . '</h4>';
        echo PHP_EOL;
        echo '<p class="card-text">';
        echo $row[ "description" ];
        echo '</p>';
        echo '<div class="text-center">';
        echo PHP_EOL;
        echo '<div class="btn btn-secondary">';
        echo PHP_EOL;
        echo '<a href="' . $row[ "internal" ] . '">More about ' . $row[ "model" ] . '</a>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
    echo '<div class="clearfix">';
        echo '</div>';
    }
} else {
    echo "0 results";
}
echo PHP_EOL;
echo '</div>';
?>
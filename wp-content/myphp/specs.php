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

$sql = "SELECT * FROM " . $table . " WHERE model='" . $model . "'";
$result = $conn->query( $sql );
if ( $result->num_rows > 0 ) {
    echo '<div class="my-container">';
    echo PHP_EOL;
    while ( $row = $result->fetch_assoc() ) {
        echo PHP_EOL;
        echo '<a href="' . $row[ "url" ] . '">';
        echo '<div class="alert alert-info" role="alert">';
        echo '<h3 class="alert-heading text-center">About the ' . $model . ' Outdoor Antenna</h3>';
        echo '</div>';
        echo '</a>';
        echo PHP_EOL;
        $youtube = $row[ "youtube" ];
        if ( $youtube != "" ) {
            echo '<div class="embed-responsive embed-responsive-16by9 box">';
            echo PHP_EOL;
            echo '<iframe  class="embed-responsive-item" src="https://www.youtube.com/embed/' . $youtube . '" frameborder="0" allowfullscreen></iframe>';
        }
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '<div class="row">';
        echo PHP_EOL;
        echo '<div class="col-lg-5">';
        echo PHP_EOL;
        echo '<div class="card">';
        echo PHP_EOL;
        echo '<a href="' . $row[ "url" ] . '">';
        echo PHP_EOL;
        echo '<img class="card-img-top img-fluid" src="' . $row[ "img" ] . '"/>';
        echo PHP_EOL;
        echo '</a>';
        echo PHP_EOL;
        echo '<div class="card-block">';
        echo PHP_EOL;
        echo '<h4 class="card-title text-center">' . $model . ' Specifications</h4>';
        echo PHP_EOL;
        echo '<div class="card-text">';
        echo $row[ "specs" ];
        echo '</div>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '</div>';
        echo PHP_EOL;
        echo '<div class="col-lg-7">';
        echo PHP_EOL;
        echo '<p class="description">' . $row[ "description" ] . '</p>';
        echo PHP_EOL;
        echo '<p class="features">' . $row[ "features" ] . '</p>';
        echo PHP_EOL;
        echo '<div class="text-center">';
        echo PHP_EOL;
        echo '<a href="' . $row[ "url" ] . '">';
        echo PHP_EOL;
        echo '<button class="btn btn-primary">Learn more at AntennaDeals.com</button>';
        echo PHP_EOL;
        echo '</a>';
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
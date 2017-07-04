<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>LAVA Models</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
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
    echo( $sql );
    echo '<br>';
    $result = $conn->query( $sql );

    if ( $result->num_rows > 0 ) {
        echo '<section class="container-fluid text-left">';
        while ( $row = $result->fetch_assoc() ) {
            echo '<div class="row alert-info">';
            echo PHP_EOL;
            echo '<div class="col-sm-4">';
            echo PHP_EOL;
            echo '<h3>' . $row[ "model" ] . '</h3>';
            echo PHP_EOL;
            echo '</div>';
            echo PHP_EOL;
            echo '<div class="col-sm-4">';
            echo '<h3>' . $row[ "url" ] . '</h3>';
            echo '</div>';
            echo PHP_EOL;
            echo '<div class="col-sm-4">';
            echo PHP_EOL;
            echo '<h3>internal Page:' . $row[ "interal" ] . '</h3>';
            echo PHP_EOL;
            echo '</div>';
            echo PHP_EOL;
            echo '</div>';
            echo PHP_EOL;
            echo '<div class="row">';
            echo PHP_EOL;
            echo '<div class="col-sm-4">';
            echo PHP_EOL;
            echo '<img src="' . $row[ "img" ] . '"/>';
            echo '</div>';
            echo PHP_EOL;
            echo '<div class="col-sm-6">';
            echo PHP_EOL;
            echo '<img src="' . $row[ "ad" ] . '"/>';
            echo PHP_EOL;
            echo '</div>';
            echo PHP_EOL;
            echo '<div class="col-sm-2">';
            echo PHP_EOL;
            echo '<img src="' . $row[ "banner" ] . '"/>';
            echo PHP_EOL;
            echo '</div>';
            echo PHP_EOL;
            echo '</div>';
            echo PHP_EOL;
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
<?php
$properties = include 'properties.php';
$connection = mysqli_connect("stepheng.sgedu.site", $properties['db_user'], $properties['db_pass'], $properties['db_name'], "3306");

if (!$connection) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

return $connection;

?>
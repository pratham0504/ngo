<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "P_ved@2004";
$dbName = "ngo";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something Went Wrong;");
}
?>

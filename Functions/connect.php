<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$databasename = "lending_system";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $databasename);

if (!$conn) {

    die();

}

?>
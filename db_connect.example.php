<?php
// first create the database with script.sql then
// copy paste this file and rename it db_connect.php fill it with your own database username and password

$server = "localhost";
$username = "";
$password = "";
$db = "tickets";
$conn = mysqli_connect($server, $username, $password, $db);

if($conn->connect_error){
    die('connection failed: '. $conn->connect_error);
}
?>

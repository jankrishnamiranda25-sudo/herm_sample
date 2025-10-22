<?php
$host = "sql200.infinityfree.com";
$user = "if0_40224979 ";  // change if your DB uses another username
$pass = "AaW6lFHPfg";       // your MySQL password
$dbname = "if0_40224979_db_herm";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

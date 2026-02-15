<?php
$host = "20.0.2.91"; // तुमचा DB Private IP
$user = "root";
$pass = "my-secret-pw";
$dbname = "game_db";
$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }
?>

<?php
include('db_config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $score = $_POST['score'];
    mysqli_query($conn, "INSERT INTO scores (username, score) VALUES ('$user', '$score')");
}
?>

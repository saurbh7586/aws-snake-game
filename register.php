<?php
include('db_config.php');
if(isset($_POST['register'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
    if(mysqli_query($conn, $sql)) { echo "Registration Successful! <a href='login.php'>Login here</a>"; }
}
?>
<form method="POST">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button name="register">Register</button>
</form>

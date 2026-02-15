<?php
include('db_config.php');
if(isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    if(mysqli_num_rows($result) > 0) {
        session_start();
        $_SESSION['username'] = $user;
        header("Location: game.php");
    } else { echo "Invalid Login!"; }
}
?>
<form method="POST">
    <h2>Login</h2>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button name="login">Login</button>
</form>

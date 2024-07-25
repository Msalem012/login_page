<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>Welcome to our website</h1>
<?php
if (isset($_SESSION['user_id'])) {
    echo "<p>You are logged in. <a href='profile.php'>View Profile</a></p>";
} else {
    echo "<p><a href='login.php'>Login</a> or <a href='register.php'>Register</a></p>";
}
?>
</div>
</body>
</html>
<?php
$host = 'sql203.infinityfree.com';
$db   = 'if0_36966301_user_management';
$user = 'if0_36966301';
$pass = 'oiuFoT2jOnbB';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
session_start();
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $captcha = $_POST['smart-token'];

    // Verify Yandex SmartCaptcha
    $secret = 'ysc2_UXsX7ulYG2e3noDtlAnMu58WLJfAgsObMDg89yOeb080bd9e';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?secret=$secret&token=$captcha");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $captcha_success = json_decode($response)->status === "ok";

    if ($captcha_success) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: profile.php");
                exit();
            } else {
                $error = "Invalid login credentials";
            }
        } else {
            $error = "Invalid login credentials";
        }
    } else {
        $error = "Captcha verification failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>
<body>
<div class="container">
<h2>Login</h2>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
<form method="POST">
    <label for="login">Email or Phone:</label>
    <input type="text" id="login" name="login" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <div
        id="captcha-container"
        class="smart-captcha"
        data-sitekey="ysc1_UXsX7ulYG2e3noDtlAnM8Yd1eIyHQvtoPPLdfHtWfdc1f61c"
    ></div>

    <input type="submit" value="Login">
</form>
</div>
</body>
</html>
<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $phone, $email, $user_id);

    if ($stmt->execute()) {
        $success = "Profile updated successfully";
    } else {
        $error = "Failed to update profile";
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            $success .= " Password updated successfully";
        } else {
            $error .= " Failed to update password";
        }
    }
}

$stmt = $conn->prepare("SELECT name, phone, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Profile</h2>
<?php
if (isset($success)) echo "<p class='success'>$success</p>";
if (isset($error)) echo "<p class='error'>$error</p>";
?>
<form method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required><br>

    <label for="phone">Phone:</label>
    <input type="tel" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br>

    <label for="new_password">New Password (leave blank to keep current):</label>
    <input type="password" id="new_password" name="new_password"><br>

    <input type="submit" value="Update Profile">
</form>
    <div class="form-actions">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
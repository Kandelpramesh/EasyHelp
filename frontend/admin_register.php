<?php
require('../db_config/db.php');
session_start();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $check_sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $insert_sql = "INSERT INTO admins (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Admin registered successfully.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Registration</title>
  <link rel="stylesheet" href="css/provider.css"> <!-- Your existing CSS file -->
</head>
<body>
  <div class="background">
    <div class="form-container">
      <div class="logo">Easy<span>Help</span></div>
      <h2>Admin Registration</h2>

      <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
      <?php elseif ($success): ?>
        <p style="color:green; text-align:center;"><?php echo $success; ?></p>
      <?php endif; ?>

      <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
      </form>

      <div class="terms">
        Already registered? <a href="login.php">Login here</a>
      </div>
    </div>
  </div>
</body>
</html>

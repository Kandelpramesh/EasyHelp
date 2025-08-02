<?php
require('../db_config/db.php');
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($role === "admin") {
        $table = "admins";
    } elseif ($role === "customer") {
        $table = "customers";
    } else {
        $table = "providers";
    }

    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $role;
                $_SESSION['name'] =  $user['full_name']?? $user['name'];

                if ($role === "admin") {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = ucfirst($role) . " not found.";
        }

        $stmt->close();
    } else {
        $error = "Server error. Please try again later.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EasyHelp Login</title>
  <link rel="stylesheet" href="css/login.css" />
</head>
<body>
  <div class="outter">
    <div class="login-container">
      <div class="login-box">
        <h2>Login to EasyHelp</h2>

        <?php if (!empty($error)): ?>
          <div style="color: red; margin-bottom: 10px;">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
          <label for="role">Login as:</label>
          <select name="role" id="role" required>
            <option value="customer">Customer</option>
            <option value="provider">Service Provider</option>
            <option value="admin">Admin</option>
          </select>

          <label for="email">Email</label>
          <input type="email" name="email" required>

          <label for="password">Password</label>
          <input type="password" name="password" required>

          <button type="submit">Login</button>
        </form>

        <p class="signup-link">
          Don't have an account?<br />
          <a href="customerRegistration.php" target="_blank">Signup as Customer</a> or
          <a href="provider.php" target="_blank">Signup as Provider</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>

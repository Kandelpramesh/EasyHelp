<?php
require('../db_config/db.php');
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();

                if (password_verify($password, $admin['password'])) {
                    $_SESSION['user_id'] = $admin['id'];
                    $_SESSION['role'] = 'admin';
                    $_SESSION['name'] = $admin['full_name'] ?? $admin['name'];
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "Admin not found.";
            }

            $stmt->close();
        } else {
            $error = "Server error. Please try again later.";
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login - EasyHelp</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Segoe UI", sans-serif;
    }

    body {
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.05);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.4);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 28px;
      color: #fff;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    .form-group input {
      width: 100%;
      padding: 12px 40px 12px 15px;
      border: none;
      border-radius: 10px;
      outline: none;
    }

    .form-group i {
      position: absolute;
      right: 15px;
      top: 12px;
      color: #999;
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background-color: #00b894;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #019875;
    }

    .error-msg {
      background-color: rgba(255, 0, 0, 0.2);
      padding: 10px;
      margin-bottom: 20px;
      border-left: 4px solid red;
      color: #ff6b6b;
      border-radius: 5px;
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2><i class="fas fa-user-shield"></i> Admin Login</h2>

    <?php if (!empty($error)): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="admin_login.php" novalidate>
      <div class="form-group">
        <input type="email" name="email" placeholder="Email" required />
        <i class="fas fa-envelope"></i>
      </div>

      <div class="form-group">
        <input type="password" name="password" placeholder="Password" required minlength="6" />
        <i class="fas fa-lock"></i>
      </div>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>

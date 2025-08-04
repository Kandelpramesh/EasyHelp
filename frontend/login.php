<?php
require('../db_config/db.php');
session_start();

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!in_array($role, ['customer', 'provider'])) {
        $errorMessage = "Please select a valid role.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Enter a valid email address.";
    } else {
        $table = ($role === "customer") ? "customers" : "providers";
        $query = "SELECT * FROM $table WHERE email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $role;
                    $_SESSION['name'] = $user['full_name'] ?? $user['name'];

                    // 🔄 REDIRECT BASED ON ROLE
                    if ($role === "provider") {
                        header("Location: provider_dashboard.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit();
                } else {
                    $errorMessage = "Wrong password.";
                }
            } else {
                $errorMessage = ucfirst($role) . " account not found.";
            }
            $stmt->close();
        } else {
            $errorMessage = "Something went wrong. Try again later.";
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - EasyHelp</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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

    .login-box {
      background-color: rgba(255, 255, 255, 0.05);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.4);
      width: 100%;
      max-width: 420px;
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 28px;
      color: #fff;
    }

    .input-field {
      margin-bottom: 20px;
      position: relative;
    }

    .input-field input,
    .input-field select {
      width: 100%;
      padding: 12px 40px 12px 15px;
      border: none;
      border-radius: 10px;
      outline: none;
    }

    .input-field i {
      position: absolute;
      right: 15px;
      top: 12px;
      color: #999;
    }

    .submit-btn {
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

    .submit-btn:hover {
      background-color: #019875;
    }

    .error-box {
      background-color: rgba(255, 0, 0, 0.2);
      padding: 10px;
      margin-bottom: 20px;
      border-left: 4px solid red;
      color: #ff6b6b;
      border-radius: 5px;
    }

    .register-links {
      margin-top: 15px;
      font-size: 14px;
      text-align: center;
    }

    .register-links a {
      color: #00cec9;
      text-decoration: none;
    }

    .register-links a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .login-box {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2><i class="fas fa-sign-in-alt"></i> Login to EasyHelp</h2>

    <?php if (!empty($errorMessage)): ?>
      <div class="error-box"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <div class="input-field">
        <select name="role" required>
          <option value="" disabled selected>Select role</option>
          <option value="customer">Customer</option>
          <option value="provider">Service Provider</option>
        </select>
        <i class="fas fa-user-tag"></i>
      </div>

      <div class="input-field">
        <input type="email" name="email" placeholder="Email" required />
        <i class="fas fa-envelope"></i>
      </div>

      <div class="input-field">
        <input type="password" name="password" placeholder="Password" required minlength="6" />
        <i class="fas fa-lock"></i>
      </div>

      <button class="submit-btn" type="submit">Login</button>
    </form>

    <div class="register-links">
      Don't have an account?<br />
      <a href="customerRegistration.php" target="_blank">Signup as Customer</a> or
      <a href="provider.php" target="_blank">Signup as Provider</a><br /><br />
    </div>
  </div>
</body>
</html>

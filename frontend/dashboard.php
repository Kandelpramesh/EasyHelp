 <?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?> 

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>
  <p>You are logged in as <strong><?= $_SESSION['role'] ?></strong>.</p>
  <a href="logout.php">Logout</a>
</body>
</html>

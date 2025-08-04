<?php
include '../db_config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];

  $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  header("Location: admin_dashboard.php");
  exit();
}
?>

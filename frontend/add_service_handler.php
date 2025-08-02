<?php
include '../db_config/db.php';

$name = $_POST['name'];
$category = $_POST['category'];
$price = $_POST['price'];
$image = $_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp, "uploads/$image");

$sql = "INSERT INTO services (name, category, price, image) VALUES ('$name', '$category', '$price', '$image')";
$conn->query($sql) or die($conn->error);

header("Location: admin_dashboard.php");
?>

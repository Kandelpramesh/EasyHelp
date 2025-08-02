<?php
include '../db_config/db.php';

$service_id = $_POST['service_id'];
$name = $_POST['customer_name'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

$stmt = $conn->prepare("INSERT INTO reviews (service_id, customer_name, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $service_id, $name, $rating, $comment);
$stmt->execute();

header("Location: service_detail.php?id=$service_id");

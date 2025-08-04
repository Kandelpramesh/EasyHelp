<?php
require('../db_config/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

   
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

 
    $sql = "INSERT INTO providers (full_name, gender, email, phone, password, address, role)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $gender, $email, $phone, $hashed_password, $address, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Provider registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

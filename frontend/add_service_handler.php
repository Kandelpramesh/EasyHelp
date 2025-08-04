<?php
include '../db_config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = trim($_POST['name']);
    $category     = trim($_POST['category']);
    $price        = floatval($_POST['price']);
    $provider_id  = intval($_POST['provider_id']); 


    $stmt = $conn->prepare("
        SELECT AVG(r.rating) AS avg_rating
        FROM reviews r
        JOIN services s ON r.service_id = s.id
        WHERE s.provider_id = ?
    ");
    $stmt->bind_param("i", $provider_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $rating = round($result['avg_rating'] ?? 0, 1);
    $stmt->close();

   
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];

        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_path = $upload_dir . $image_name;

        
        if (move_uploaded_file($image_tmp, $upload_path)) {
         
            $stmt = $conn->prepare("INSERT INTO services (name, category, price, image, provider_id, rating) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssi", $name, $category, $price, $image_name, $provider_id, $rating);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: admin_dashboard.php");
                exit;
            } else {
                echo "Insert error: " . $stmt->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Image upload error. Code: " . $_FILES['image']['error'];
    }
} else {
    echo "Invalid request method.";
}
?>

<?php
include '../db_config/db.php';

$category = isset($_GET['cat']) ? $_GET['cat'] : '';
if (!$category) {
  echo "Category not specified.";
  exit;
}

$query = "
SELECT s.*, 
       COALESCE(AVG(r.rating), 0) AS avg_rating, 
       COUNT(r.id) AS review_count,
       p.full_name AS provider_name
FROM services s
LEFT JOIN reviews r ON s.id = r.service_id
LEFT JOIN providers p ON s.provider_id = p.id
WHERE s.category = ?
GROUP BY s.id
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($category); ?> Services</title>
  <link rel="stylesheet" href="css/fontPage.css">
  <style>
    .service-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      padding: 20px;
    }

    .service-card {
      width: 240px;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      font-family: sans-serif;
      background: #fff;
    }

    .service-card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }

    .service-info {
      padding: 10px;
    }

    .service-info h3 {
      font-size: 16px;
      font-weight: bold;
      margin: 5px 0;
    }

    .provider-name {
      font-size: 14px;
      color: #555;
    }

    .verified {
      color: orange;
    }

    .rating {
      font-size: 14px;
      color: #333;
    }

    .price {
      font-size: 16px;
      color: red;
      font-weight: bold;
    }

    .per-hour {
      font-weight: normal;
      font-size: 14px;
    }

    .top-bar {
      padding: 10px 20px;
      background: #ffff;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      font-family: sans-serif;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .breadcrumb a,
    .breadcrumb span {
      color: #555;
      text-decoration: none;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <header class="top-bar">
    <div class="logo">EasyHelp</div>
    <nav class="breadcrumb">
      <a href="fontPage.php">Home</a> / <span><?php echo htmlspecialchars($category); ?></span>
    </nav>
  </header>

  <main>
    <h2 style="padding-left: 20px;"><?php echo htmlspecialchars($category); ?> Services</h2>
    <div class="service-list">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="service-card">
          <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="service-info">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p class="provider-name">
              <span class="verified">✔️</span>
              <?php echo htmlspecialchars($row['provider_name'] ?? 'Unknown'); ?>
            </p>
            <p class="rating">⭐ <?php echo number_format($row['avg_rating'], 1); ?> <span class="count">(<?php echo $row['review_count']; ?>)</span></p>
            <p class="price">Rs. <?php echo $row['price']; ?> <span class="per-hour">/ Hour</span></p>
          </div>
        </div>

      <?php } ?>

      <?php if ($result->num_rows === 0) echo "<p style='padding: 20px;'>No services found in this category.</p>"; ?>
    </div>
  </main>

  <footer class="site-footer">
    <div class="footer-content">
      <p>© 2025 EasyHelp. All rights reserved.</p>
      <p>Contact: 9702897104 | Kathmandu, Baluwatar, Ward No 4</p>
      <div class="footer-links">
        <a href="#">Terms & Conditions</a> |
        <a href="#">Privacy Policy</a>
      </div>
    </div>
  </footer>
</body>

</html>
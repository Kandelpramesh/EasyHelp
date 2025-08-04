<?php
session_start();
require('../db_config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php");
    exit();
}

$provider_id = $_SESSION['user_id'];
$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Provider Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f8;
    }

    .sidebar {
      position: fixed;
      width: 220px;
      height: 100vh;
      background-color: #2c3e50;
      padding-top: 30px;
      overflow-y: auto;
    }

    .sidebar a {
      display: block;
      padding: 15px 25px;
      color: #ecf0f1;
      text-decoration: none;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #34495e;
    }

    .main {
      margin-left: 220px;
      padding: 30px;
      min-height: 100vh;
    }

    h1 {
      margin-top: 0;
      color: #333;
    }

    .service-card, .booking, .review {
      background: white;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .section-title {
      font-weight: bold;
      margin-bottom: 15px;
      color: #555;
    }

    .booking strong, .review strong {
      color: #2c3e50;
    }

    p.no-data {
      color: #999;
      font-style: italic;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <a href="provider_dashboard.php?page=dashboard" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a>
  <a href="provider_dashboard.php?page=services" class="<?= $page === 'services' ? 'active' : '' ?>"><i class="fas fa-concierge-bell"></i> My Services</a>
  <a href="provider_dashboard.php?page=bookings" class="<?= $page === 'bookings' ? 'active' : '' ?>"><i class="fas fa-calendar-check"></i> Bookings</a>
  <a href="provider_dashboard.php?page=reviews" class="<?= $page === 'reviews' ? 'active' : '' ?>"><i class="fas fa-star"></i> Reviews</a>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
  <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h1>

  <?php if ($page === 'dashboard'): ?>
    <h2>Dashboard Overview</h2>
    <p>Here’s a quick summary of your services, bookings, and reviews.</p>

    <?php
      // Count services
      $stmt = $conn->prepare("SELECT COUNT(*) as total_services FROM services WHERE provider_id = ?");
      $stmt->bind_param("i", $provider_id);
      $stmt->execute();
      $res = $stmt->get_result();
      $total_services = $res->fetch_assoc()['total_services'];

      // Count bookings
      $stmt = $conn->prepare("
        SELECT COUNT(*) as total_bookings 
        FROM bookings b 
        JOIN services s ON b.service_id = s.id 
        WHERE s.provider_id = ?");
      $stmt->bind_param("i", $provider_id);
      $stmt->execute();
      $res = $stmt->get_result();
      $total_bookings = $res->fetch_assoc()['total_bookings'];

      // Count reviews
      $stmt = $conn->prepare("
        SELECT COUNT(*) as total_reviews 
        FROM reviews r 
        JOIN services s ON r.service_id = s.id 
        WHERE s.provider_id = ?");
      $stmt->bind_param("i", $provider_id);
      $stmt->execute();
      $res = $stmt->get_result();
      $total_reviews = $res->fetch_assoc()['total_reviews'];
    ?>

    <ul>
      <li><strong>Total Services:</strong> <?= $total_services ?></li>
      <li><strong>Total Bookings:</strong> <?= $total_bookings ?></li>
      <li><strong>Total Reviews:</strong> <?= $total_reviews ?></li>
    </ul>

  <?php elseif ($page === 'services'): ?>

    <h2>My Services</h2>

    <?php
      $stmt = $conn->prepare("SELECT * FROM services WHERE provider_id = ?");
      $stmt->bind_param("i", $provider_id);
      $stmt->execute();
      $services = $stmt->get_result();
    ?>

    <?php if ($services->num_rows > 0): ?>
      <?php while ($service = $services->fetch_assoc()): ?>
        <div class="service-card">
          <h3><?= htmlspecialchars($service['name']) ?></h3>
          <p><strong>Category:</strong> <?= htmlspecialchars($service['category']) ?></p>
          <p><strong>Price:</strong> Rs. <?= $service['price'] ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="no-data">You have not added any services yet.</p>
    <?php endif; ?>

  <?php elseif ($page === 'bookings'): ?>

    <h2>Bookings</h2>

    <?php
      $stmt_bookings = $conn->prepare("
        SELECT b.booking_date, b.status, c.full_name AS customer_name, s.name AS service_name
        FROM bookings b
        JOIN customers c ON b.customer_id = c.id
        JOIN services s ON b.service_id = s.id
        WHERE s.provider_id = ?
        ORDER BY b.booking_date DESC
      ");
      $stmt_bookings->bind_param("i", $provider_id);
      $stmt_bookings->execute();
      $bookings = $stmt_bookings->get_result();
    ?>

    <?php if ($bookings->num_rows > 0): ?>
      <?php while ($booking = $bookings->fetch_assoc()): ?>
        <div class="booking">
          <strong>Service:</strong> <?= htmlspecialchars($booking['service_name']) ?><br>
          <strong>Customer:</strong> <?= htmlspecialchars($booking['customer_name']) ?><br>
          <strong>Date:</strong> <?= $booking['booking_date'] ?><br>
          <strong>Status:</strong> <?= ucfirst($booking['status']) ?>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="no-data">No bookings yet.</p>
    <?php endif; ?>

  <?php elseif ($page === 'reviews'): ?>

    <h2>Reviews</h2>

    <?php
      $stmt_reviews = $conn->prepare("
        SELECT r.rating, r.comment, r.customer_name, r.created_at, s.name AS service_name
        FROM reviews r
        JOIN services s ON r.service_id = s.id
        WHERE s.provider_id = ?
        ORDER BY r.created_at DESC
      ");
      $stmt_reviews->bind_param("i", $provider_id);
      $stmt_reviews->execute();
      $reviews = $stmt_reviews->get_result();
    ?>

    <?php if ($reviews->num_rows > 0): ?>
      <?php while ($review = $reviews->fetch_assoc()): ?>
        <div class="review">
          <strong>Service:</strong> <?= htmlspecialchars($review['service_name']) ?><br>
          <strong>Customer:</strong> <?= htmlspecialchars($review['customer_name']) ?><br>
          <strong>Rating:</strong> <?= $review['rating'] ?>/5<br>
          <em><?= htmlspecialchars($review['comment']) ?></em><br>
          <small><?= $review['created_at'] ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="no-data">No reviews yet.</p>
    <?php endif; ?>

  <?php else: ?>
    <p>Invalid page selection.</p>
  <?php endif; ?>

</div>

</body>
</html>

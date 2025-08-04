<?php
session_start();
include '../db_config/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - EasyHelp</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/frontend/css/admin_dash.css">
</head>

<body>

  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="#" onclick="showSection('dashboard')"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="#" onclick="showSection('customers')"><i class="fas fa-users"></i> Customers</a>
    <a href="#" onclick="showSection('providers')"><i class="fas fa-user-cog"></i> Providers</a>
    <a href="#" onclick="showSection('add-service')"><i class="fas fa-plus-circle"></i> Add Service</a>
    <a href="#" onclick="showSection('services')"><i class="fas fa-list"></i> All Services</a>
  </div>

  <div class="main-content">
    <div class="topbar">
      <span>Welcome, Admin</span>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    
    <div id="dashboard" class="section active">
      <h3>Dashboard Overview</h3>
      <p>Welcome to EasyHelp Admin Panel.</p>
    </div>

   
    <div id="customers" class="section">
      <h3>Customer List</h3>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
        </tr>
        <?php
        $res = $conn->query("SELECT * FROM customers");
        while ($row = $res->fetch_assoc()) {
          echo "<tr><td>{$row['id']}</td><td>{$row['full_name']}</td><td>{$row['email']}</td></tr>";
        }
        ?>
      </table>
    </div>
    <div id="providers" class="section">
      <h3>Provider List</h3>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
        </tr>
        <?php
        $res = $conn->query("SELECT * FROM providers");
        while ($row = $res->fetch_assoc()) {
          echo "<tr><td>{$row['id']}</td><td>{$row['full_name']}</td><td>{$row['email']}</td></tr>";
        }
        ?>
      </table>
    </div>

    <div id="add-service" class="section">
      <h3>Add New Service</h3>
      <form action="add_service_handler.php" method="POST" enctype="multipart/form-data">
        <label>Service Name:</label>
        <input type="text" name="name" required>

        <label>Category:</label>
        <input type="text" name="category" required>

        <label>Price (Per Hour):</label>
        <input type="number" name="price" required>

        <label>Provider Name:</label>
        <select name="provider_id" required>
          <option value="">-- Select Provider --</option>
          <?php
          $providers = $conn->query("SELECT id, full_name FROM providers");
          while ($p = $providers->fetch_assoc()) {
            echo "<option value=\"{$p['id']}\">{$p['full_name']}</option>";
          }
          ?>
        </select>


        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Add Service</button>
      </form>
    </div>
    <div id="services" class="section">
      <h3>All Services</h3>
      <table>
        <tr>
          <th>ID</th>
          <th>Service_Name</th>
          <th>Category</th>
          <th>Price</th>
          <th>Provider_Name</th>
          <th>Rating</th>
          <th>Action</th>
        </tr>

        <?php
       $sql = "SELECT s.*, p.full_name AS provider_name 
        FROM services s 
        LEFT JOIN providers p ON s.provider_id = p.id";
$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {
  echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['name']}</td>
    <td>{$row['category']}</td>
    <td>Rs. {$row['price']}</td>
    <td>{$row['provider_name']}</td>
    <td>{$row['rating']} ⭐</td>
    <td>
      <form action='delete_service.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this service?');\">
        <input type='hidden' name='id' value='{$row['id']}'>
        <button type='submit' class='delete-btn'>Delete</button>
      </form>
    </td>
  </tr>";
}

        ?>
      </table>
    </div>
  </div>

  <script>
    function showSection(sectionId) {
      document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
      });
      document.getElementById(sectionId).classList.add('active');
    }
  </script>
</body>

</html>
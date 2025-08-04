<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EasyHelp</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/fontPage.css" />
</head>

<body>
  <div class="container">
    <header class="top-bar">
      <div class="logo">EasyHelp</div>
      <div class="auth-buttons">
        <a href="login.php" class="btn login">Login</a>
        <a href="customerRegistration.php" class="btn signup">Signup as Customer</a>
        <a href="provider.php" class="btn signup">Signup as Provider</a>
      </div>
    </header>
    <main class="content">
      <div class="words">
        <h1>Home Services,</h1>
        <h1>Right at Your Doorstep</h1>
        <p>Search and book trusted professionals in seconds.</p>
        <div class="search-bar">
          <input type="text" placeholder="Search for electricians, cleaners..." />
          <button>
            <img src="https://img.icons8.com/ios-glyphs/30/000000/search--v1.png" alt="Search" />
          </button>
        </div>
      </div>
      <div class="profile-pic">
        <img src="images/a2.jpg" alt="Service Visual" />
      </div>
    </main>
    <section class="categories">
      <h2>Browse by Category</h2>
      <div class="category-grid">
        <a href="category.php?cat=Home Repair" class="category">
          <img src="images/homerepair.jpg" alt="Home Repair" />
          <p>Home Repairs</p>
        </a>
        <a href="category.php?cat=Automobile" class="category">
          <img src="images/automobile.jpg" alt="Automobile" />
          <p>Automobile</p>
        </a>
        <a href="category.php?cat=Tech" class="category">
          <img src="images/tech.jpg" alt="Tech" />
          <p>Tech</p>
        </a>
        <a href="category.php?cat=Personal Care" class="category">
          <img src="images/personalCare.jpg" alt="Personal Care" />
          <p>Personal Care</p>
        </a>
        <a href="category.php?cat=Health" class="category">
          <img src="images/health.jpg" alt="Health" />
          <p>Health</p>
        </a>
        <a href="category.php?cat=Pet Care" class="category">
          <img src="images/petcare.jpg" alt="Pet Care" />
          <p>Pet Care</p>
        </a>
      </div>
    </section>

    <section class="featured">
      <h2>Featured Services</h2>
      <div class="service-list">
        <div class="service-card">
          <img src="images/electrician.jpg" alt="Electrician" />
          <div class="service-info">
            <h3>Hire an Electrician</h3>
            <p>Starting at Rs. 500/hr</p>
          </div>
        </div>
        <div class="service-card">
          <img src="images/laptop.png" alt="Laptop Service" />
          <div class="service-info">
            <h3>Laptop Servicing</h3>
            <p>Flat rate: Rs. 1,500</p>
          </div>
        </div>
      </div>
    </section>
    <section class="testimonials">
      <h2>What Our Clients Say</h2>
      <div class="testimonial-grid">
        <div class="testimonial-card">
          <p>"Great service and fast response! Highly recommended."</p>
          <h4>Aashish Khanal</h4>
        </div>
        <div class="testimonial-card">
          <p>"Our solar system was fixed quickly and works perfectly now."</p>
          <h4>Sandeep Regmi</h4>
        </div>
        <div class="testimonial-card">
          <p>"Very affordable and professional. Will book again!"</p>
          <h4>Alij Khati</h4>
        </div>
        <div class="testimonial-card">
          <p>"Laptop works fine again. Excellent communication."</p>
          <h4>Rohan Bhandari</h4>
        </div>
      </div>
    </section>
  </div>
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


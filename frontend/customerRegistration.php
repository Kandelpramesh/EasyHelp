<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Customer Registration</title>
  <link rel="stylesheet" href="css/customerRegistration.css">
</head>

<body>
  <div class="background">
    <div class="form-container">
      <div class="logo">EasyHelp</div>
      <h2>Customer Registration</h2>
      <form method="POST" action="register_customer.php">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>

        <input type="tel" name="phone" placeholder="Phone Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="address" placeholder="Address" required>

        <button type="submit">Register</button>
      </form>
      <p class="terms">By signing up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
    </div>
  </div>
</body>

</html>
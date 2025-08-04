<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Provider Registration</title>
  <link rel="stylesheet" href="css/provider.css">
</head>

<body>
  <div class="background">
    <div class="form-container">
      <div class="logo">EasyHelp</div>
      <h2>Service Provider Registration</h2>
      <form method="POST" action="register_provider.php">
        <input type="text" name="full_name" placeholder="Full Name" required autocomplete="name">
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
        <input type="email" name="email" placeholder="Email Address" required autocomplete="email">
        <input type="tel" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}" autocomplete="tel">
        <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="address" placeholder="Address" required autocomplete="street-address">
        <select name="role" required>
          <option value="">Select Role</option>
          <option value="Plumber">Plumber</option>
          <option value="Electrician">Electrician</option>
          <option value="Gardener">Gardener</option>
        </select>
        <button type="submit">Register</button>
      </form>

      <p class="terms">By signing up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
    </div>
  </div>
</body>

</html>
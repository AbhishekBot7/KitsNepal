<?php
// Database connection
$host = "localhost";
$user = "root";        // Change this if using a different MySQL user
$pass = "";            // Change this if you have a password set
$db   = "footballkitsnepal";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $fullname = $_POST["fullname"];
  $email    = $_POST["email"];
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashed password
  $is_admin = 0; // Default to regular user

  // Check if username or email already exists
  $check_query = "SELECT * FROM user WHERE username = ? OR email = ?";
  $stmt = $conn->prepare($check_query);
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo "<script>alert('Username or email already exists!');</script>";
  } else {
    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO user (fullname, email, username, password, is_admin) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $fullname, $email, $username, $password, $is_admin);

    if ($stmt->execute()) {
      echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
      echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
  }

  $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Registration - Football Kits Nepal</title>
  <link rel="stylesheet" href="../css/register.css" />
</head>
<body>
  <section class="register-section">
    <div class="container">
      <h2>Create an Account</h2>
      <form action="#" method="post" class="register-form">
        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" required />
        </div>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <button type="submit" class="btn">Register</button>
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
      </form>
    </div>
  </section>
</body>
</html>

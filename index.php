<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For debugging - uncomment if needed
// error_log("Index.php - Session ID: " . session_id());
// error_log("Index.php - User ID: " . ($_SESSION['user_id'] ?? 'Not set'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Football Kits Nepal</title>
  <link rel="stylesheet" href="./css/Home.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #e63946;
      --primary-hover: #c1121f;
      --secondary-color: #1d3557;
      --accent-color: #a8dadc;
      --background: #f8f9fa;
      --card-bg: #ffffff;
      --text-dark: #1d3557;
      --text-light: #f1faee;
      --border-radius: 16px;
      --shadow: 0 4px 12px rgba(0,0,0,0.08);
      --transition: all 0.3s ease;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--background);
      color: var(--text-dark);
      margin: 0;
      padding: 0;
    }
    .hero {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: var(--text-light);
      padding: 100px 0 80px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .hero h2 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 1rem;
      letter-spacing: -1px;
    }
    .hero p {
      font-size: 1.25rem;
      max-width: 600px;
      margin: 0 auto 2rem auto;
      opacity: 0.95;
    }
    .hero .btn {
      display: inline-block;
      background: var(--accent-color);
      color: var(--secondary-color);
      font-weight: 700;
      padding: 16px 40px;
      border-radius: 30px;
      font-size: 1.1rem;
      text-decoration: none;
      box-shadow: var(--shadow);
      transition: var(--transition);
      border: none;
      margin-top: 1.5rem;
    }
    .hero .btn:hover {
      background: var(--primary-light, #f1faee);
      color: var(--primary-color);
      transform: translateY(-2px);
    }
    .featured-section {
      background: var(--background);
      padding: 60px 0 40px 0;
    }
    .featured-title {
      text-align: center;
      font-size: 2rem;
      color: var(--secondary-color);
      margin-bottom: 2.5rem;
      font-weight: 600;
    }
    .featured-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2rem;
      max-width: 1100px;
      margin: 0 auto;
    }
    .kit-card {
      background: var(--card-bg);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem 1.5rem 1.5rem 1.5rem;
      text-align: center;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }
    .kit-card img {
      width: 100%;
      max-width: 180px;
      height: 180px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 1.2rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.07);
      background: #f8f9fa;
    }
    .kit-card h3 {
      font-size: 1.2rem;
      color: var(--secondary-color);
      margin-bottom: 0.5rem;
      font-weight: 600;
    }
    .kit-card .price {
      color: var(--primary-color);
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 0.7rem;
    }
    .kit-card .btn {
      background: var(--primary-color);
      color: #fff;
      padding: 10px 24px;
      border-radius: 20px;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      border: none;
      transition: var(--transition);
      margin-top: 0.5rem;
      display: inline-block;
    }
    .kit-card .btn:hover {
      background: var(--secondary-color);
      color: #fff;
    }
    @media (max-width: 700px) {
      .hero { padding: 60px 0 40px; }
      .hero h2 { font-size: 2rem; }
      .featured-title { font-size: 1.3rem; }
      .kit-card img { height: 120px; max-width: 120px; }
    }
  </style>
</head>
<body>
  <?php include './components/nav.php'; ?>

  <section class="hero">
    <div class="container">
      <h2>Official Football Kits in Nepal</h2>
      <p>Get your favorite club and national team jerseys here. Shop authentic, high-quality football kits from top clubs and countries, delivered to your door anywhere in Nepal.</p>
      <a href="pages/Kits.php" class="btn"><i class="fas fa-shopping-bag"></i> Shop Now</a>
    </div>
  </section>

  <section class="featured-section">
    <div class="featured-title">Featured Kits</div>
    <div class="featured-grid">
      <div class="kit-card">
        <img src="img/RealmadridHomeKit.jpeg" alt="Real Madrid Home Kit">
        <h3>Real Madrid Home Kit 2024</h3>
        <div class="price">Rs. 3,500</div>
        <a href="pages/Kits.php" class="btn">View</a>
      </div>
      <div class="kit-card">
        <img src="img/ManUHomeKit.jpg" alt="Manchester United Home Kit">
        <h3>Manchester United Home Kit 2024</h3>
        <div class="price">Rs. 3,200</div>
        <a href="pages/Kits.php" class="btn">View</a>
      </div>
      <div class="kit-card">
        <img src="img/NepalHomeKit.jpeg" alt="Nepal National Team Kit">
        <h3>Nepal National Team Kit</h3>
        <div class="price">Rs. 2,800</div>
        <a href="pages/Kits.php" class="btn">View</a>
      </div>
      <div class="kit-card">
        <img src="img/portugal.jpg" alt="Portugal Home Kit">
        <h3>Portugal Home Kit 2024</h3>
        <div class="price">Rs. 3,400</div>
        <a href="pages/Kits.php" class="btn">View</a>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> Football Kits Nepal. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>

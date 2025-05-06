<?php
session_start();
require_once('dbconnect.php');

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM product ORDER BY product_id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add to cart functionality
if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $product_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Football Kits Nepal - Kits</title>
  <link rel="stylesheet" href="../css/Home.css" />
</head>
<body>
  <header>
    <div class="container">
      <h1>Football Kits Nepal</h1>
      <nav>
        <ul>
          <li><a href="Home.php">Home</a></li>
          <li><a href="Kits.php">Kits</a></li>
          <li><a href="aboutus.php">About</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <section class="products">
    <div class="container">
      <h2>Available Jerseys</h2>
      <div class="product-grid">
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['p_name']; ?>">
            <h3><?php echo $product['p_name']; ?></h3>
            <p class="price">NPR <?php echo $product['p_price']; ?></p>
            <p class="grade"><?php echo $product['p_grade']; ?> Version</p>
            <?php if ($product['p_qty'] > 0): ?>
              <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
              </form>
            <?php else: ?>
              <button class="btn disabled">Out of Stock</button>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; 2025 Football Kits Nepal. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>

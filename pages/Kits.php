<?php
session_start();
require_once('dbconnect.php');

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM product ORDER BY product_id DESC");
$stmt->execute();
$result = $stmt->get_result();
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Add to cart functionality
$cart_message = '';
if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Check if product already in cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Update quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    } else {
        // Insert new row
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
    $cart_message = 'Added to cart!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Football Kits Nepal - Kits</title>
  <link rel="stylesheet" href="../css/Home.css" />
  <style>
    body {
      background: #f4f6fb;
    }
    .products {
      padding: 40px 0 60px 0;
    }
    .products h2 {
      text-align: center;
      font-size: 2.2rem;
      margin-bottom: 32px;
      color: #222;
      letter-spacing: 1px;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 32px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .product-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.07);
      padding: 24px 18px 18px 18px;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: box-shadow 0.2s, transform 0.2s;
      position: relative;
    }
    .product-card:hover {
      box-shadow: 0 8px 32px rgba(0,0,0,0.13);
      transform: translateY(-6px) scale(1.03);
    }
    .product-card img {
      width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 18px;
      background: #f2f2f2;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .product-card h3 {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0 0 8px 0;
      color: #222;
      text-align: center;
    }
    .product-card .price {
      color: #ff3c00;
      font-size: 1.1rem;
      font-weight: bold;
      margin-bottom: 6px;
    }
    .product-card .grade {
      font-size: 0.98rem;
      color: #666;
      margin-bottom: 14px;
    }
    .product-card .btn {
      background: #ff3c00;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 10px 22px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: auto;
      width: 100%;
    }
    .product-card .btn:hover:not(.disabled) {
      background: #ff6b3d;
    }
    .product-card .btn.disabled {
      background: #ccc;
      color: #888;
      cursor: not-allowed;
    }
    @media (max-width: 600px) {
      .product-card img {
        width: 100px;
        height: 100px;
      }
      .product-grid {
        gap: 18px;
      }
    }
    .cart-message {
      text-align: center;
      color: #fff;
      background: #28a745;
      padding: 10px 0;
      border-radius: 6px;
      margin-bottom: 18px;
      font-weight: 600;
      max-width: 400px;
      margin-left: auto;
      margin-right: auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.07);
      letter-spacing: 1px;
    }
  </style>
</head>
<body>
  <?php include '../components/nav.php'; ?>

  <section class="products">
    <div class="container">
      <h2>Available Jerseys</h2>
      <?php if ($cart_message): ?>
        <div class="cart-message"><?php echo $cart_message; ?></div>
      <?php endif; ?>
      <div class="product-grid">
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="product-link" style="width:100%">
              <img src="<?php echo '../img/' . htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['p_name']); ?>">
              <h3><?php echo htmlspecialchars($product['p_name']); ?></h3>
              <p class="price">NPR <?php echo htmlspecialchars($product['p_price']); ?></p>
              <p class="grade"><?php echo htmlspecialchars(ucfirst($product['p_grade'])); ?> Version</p>
            </a>
            <?php if ($product['p_qty'] > 0): ?>
              <form method="POST" style="width:100%">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
              </form>
            <?php else: ?>
              <button class="btn disabled" disabled>Out of Stock</button>
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

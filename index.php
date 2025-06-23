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
</head>
<body>
  <?php include './components/nav.php'; ?>

  <section class="hero">
    <div class="container">
      <h2>Official Football Kits in Nepal</h2>
      <p>Get your favorite club and national team jerseys here.</p>
      <a href="#kits" class="btn">Shop Now</a>
    </div>
  </section>

  <section class="kits" id="kits">
    <div class="container">
      <h2>Our Latest Kits</h2>
      <div class="kit-grid">
        <div class="kit">
          <img src="./img/NepalHomeKit.jpeg" alt="Nepal National Kit" />
          <h3>Nepal National Team</h3>
          <p>Price: NPR 2500</p>
          <div class="product-options">
            <select class="size-select" id="size-nepal">
              <option value="">Select Size</option>
              <option value="S">Small</option>
              <option value="M">Medium</option>
              <option value="L">Large</option>
              <option value="XL">X-Large</option>
            </select>
            <input type="number" class="quantity-input" id="qty-nepal" min="1" value="1">
          </div>
          <button class="cart-btn" onclick="addToCart('Nepal National Team', 2500, 'nepal')">Add to Cart</button>
        </div>
        <div class="kit">
          <img src="./img/RealmadridHomeKit.jpeg" alt="Real Madrid Kit" />
          <h3>Real Madrid</h3>
          <p>Price: NPR 2800</p>
          <div class="product-options">
            <select class="size-select" id="size-madrid">
              <option value="">Select Size</option>
              <option value="S">Small</option>
              <option value="M">Medium</option>
              <option value="L">Large</option>
              <option value="XL">X-Large</option>
            </select>
            <input type="number" class="quantity-input" id="qty-madrid" min="1" value="1">
          </div>
          <button class="cart-btn" onclick="addToCart('Real Madrid', 2800, 'madrid')">Add to Cart</button>
        </div>
        <div class="kit">
          <img src="./img/ManUHomeKit.jpg" alt="Manchester United Kit" />
          <h3>Manchester United</h3>
          <p>Price: NPR 2700</p>
          <div class="product-options">
            <select class="size-select" id="size-manu">
              <option value="">Select Size</option>
              <option value="S">Small</option>
              <option value="M">Medium</option>
              <option value="L">Large</option>
              <option value="XL">X-Large</option>
            </select>
            <input type="number" class="quantity-input" id="qty-manu" min="1" value="1">
          </div>
          <button class="cart-btn" onclick="addToCart('Manchester United', 2700, 'manu')">Add to Cart</button>
        </div>
      </div>
    </div>
  </section>

  <section class="about">
    <div class="container">
      <h2>About Us</h2>
      <p>

      </p>
    </div>
  </section>

  <section class="contact">
    <div class="container">
      <h2>Contact Us</h2>
      <p>Email: info@footballkitsnepal.com</p>
      <p>Phone: +977-9812345678</p>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; 2025 Football Kits Nepal. All rights reserved.</p>
    </div>
  </footer>
  <script>
    function updateCartCount() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
      document.getElementById('cart-count').textContent = totalItems;
    }

    function addToCart(productName, price, productId) {
      const size = document.getElementById(`size-${productId}`).value;
      const quantity = document.getElementById(`qty-${productId}`).value;

      if (!size) {
        alert('Please select a size');
        return;
      }

      if (quantity < 1) {
        alert('Please select a valid quantity');
        return;
      }

      // Get existing cart items from localStorage
      let cart = JSON.parse(localStorage.getItem('cart')) || [];

      // Add new item to cart
      cart.push({
        name: productName,
        price: price,
        quantity: parseInt(quantity),
        size: size
      });

      // Save updated cart back to localStorage
      localStorage.setItem('cart', JSON.stringify(cart));

      // Update cart count
      updateCartCount();

      // Show confirmation message
      alert(`${productName} (Size: ${size}, Quantity: ${quantity}) has been added to your cart!`);
    }

    // Update cart count when page loads
    window.onload = updateCartCount;
  </script>
</body>
</html>

<?php
session_start();
require_once('dbconnect.php');

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header('Location: Kits.php');
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// If product not found, redirect to Kits page
if (!$product) {
    header('Location: Kits.php');
    exit();
}

// Add to cart functionality
if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $success_message = "Product added to cart successfully!";
}

// Static options for demo
$sizes = ['S', 'M', 'L', 'XL', 'XXL'];
$badges = ['Include', "Don't Include"];
$features = [
    "Player's Grade Jersey",
    "Made in Thailand (Branding in Neck)",
    "Aeroready Design",
    "Top Quality Available in Nepal"
];
$discount = ($product['p_grade'] === 'Player') ? 22 : 0; // Demo: 22% off for Player grade

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['p_name']); ?> - Football Kits Nepal</title>
    <link rel="stylesheet" href="../css/Home.css">
    <style>
        body { background: #fafbfc; }
        .product-detail-main {
            display: flex;
            gap: 2rem;
            max-width: 1200px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 2rem;
        }
        .product-gallery {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .product-thumbnails {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .product-thumbnails img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #eee;
            cursor: pointer;
        }
        .product-main-image {
            width: 400px;
            height: 400px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #eee;
            background: #fff;
        }
        .product-info-section {
            flex: 2;
            padding-left: 2rem;
        }
        .discount-badge {
            background: #e44d26;
            color: #fff;
            font-weight: bold;
            border-radius: 6px;
            padding: 0.3rem 1rem;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .brand {
            color: #888;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .product-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .review-section {
            color: #aaa;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .feature-list {
            margin: 1rem 0;
            padding-left: 0;
        }
        .feature-list li {
            list-style: none;
            margin-bottom: 0.5rem;
            color: #2e7d32;
        }
        .feature-list li:before {
            content: '✔';
            color: #2e7d32;
            margin-right: 0.5rem;
        }
        .product-price-range {
            font-size: 1.7rem;
            color: #e44d26;
            font-weight: bold;
            margin: 1rem 0 0.5rem 0;
        }
        .stock-status {
            color: #388e3c;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .size-options, .badge-options {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .size-btn, .badge-btn {
            padding: 0.5rem 1.2rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f5f5f5;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s, border 0.2s;
        }
        .size-btn.selected, .badge-btn.selected {
            background: #e44d26;
            color: #fff;
            border-color: #e44d26;
        }
        .qty-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .qty-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #ccc;
            background: #f5f5f5;
            border-radius: 6px;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .qty-input {
            width: 48px;
            text-align: center;
            font-size: 1.1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 0.2rem 0;
        }
        .action-btns {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .add-to-cart-btn, .buy-now-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .add-to-cart-btn {
            background: #4CAF50;
            color: #fff;
        }
        .add-to-cart-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .buy-now-btn {
            background: #e44d26;
            color: #fff;
        }
        .wishlist-compare {
            color: #888;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        @media (max-width: 900px) {
            .product-detail-main { flex-direction: column; padding: 1rem; }
            .product-info-section { padding-left: 0; }
            .product-main-image { width: 100%; height: auto; }
        }
    </style>
    <script>
    // Simple JS for size/badge/qty selection
    document.addEventListener('DOMContentLoaded', function() {
        // Size selection
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
        // Badge selection
        document.querySelectorAll('.badge-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.badge-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
        // Quantity selector
        const qtyInput = document.getElementById('qty-input');
        document.getElementById('qty-minus').onclick = function() {
            let v = parseInt(qtyInput.value); if (v > 1) qtyInput.value = v-1;
        };
        document.getElementById('qty-plus').onclick = function() {
            let v = parseInt(qtyInput.value); qtyInput.value = v+1;
        };
    });
    </script>
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
    <section class="product-detail">
        <div class="product-detail-main">
            <div class="product-gallery">
                <div class="product-thumbnails">
                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Thumbnail">
                    <!-- Add more thumbnails if you have more images in DB -->
                </div>
                <img class="product-main-image" src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['p_name']); ?>">
                <?php if ($discount > 0): ?>
                    <div class="discount-badge">-<?php echo $discount; ?>%</div>
                <?php endif; ?>
            </div>
            <div class="product-info-section">
                <div class="brand">Adidas</div>
                <div class="product-title"><?php echo htmlspecialchars($product['p_name']); ?></div>
                <div class="review-section">
                    ★★★★★ (0 Reviews) <a href="#" style="color:#888;text-decoration:underline;">Write a review</a>
                </div>
                <ul class="feature-list">
                    <?php foreach ($features as $feature): ?>
                        <li><?php echo $feature; ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="product-price-range">
                    NPR <?php echo number_format($product['p_price'] * (1 - $discount/100), 2); ?>
                    <?php if ($discount > 0): ?>
                        <span style="text-decoration:line-through;color:#aaa;font-size:1rem;">NPR <?php echo number_format($product['p_price'], 2); ?></span>
                    <?php endif; ?>
                </div>
                <div class="stock-status">
                    <?php echo $product['p_qty'] > 0 ? 'In stock' : 'Out of stock'; ?>
                </div>
                <div style="margin-bottom:0.5rem;">size:</div>
                <div class="size-options">
                    <?php foreach ($sizes as $size): ?>
                        <button type="button" class="size-btn"><?php echo $size; ?></button>
                    <?php endforeach; ?>
                </div>
                <div style="margin-bottom:0.5rem;">UCL & UFEA Badge:</div>
                <div class="badge-options">
                    <?php foreach ($badges as $badge): ?>
                        <button type="button" class="badge-btn"><?php echo $badge; ?></button>
                    <?php endforeach; ?>
                </div>
                <form method="POST">
                    <div class="qty-selector">
                        <button type="button" class="qty-btn" id="qty-minus">-</button>
                        <input type="text" name="quantity" id="qty-input" class="qty-input" value="1" readonly>
                        <button type="button" class="qty-btn" id="qty-plus">+</button>
                    </div>
                    <div class="action-btns">
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn" <?php if ($product['p_qty'] <= 0) echo 'disabled'; ?>>+ ADD TO CART</button>
                        <button type="button" class="buy-now-btn">BUY NOW</button>
                    </div>
                </form>
                <div class="wishlist-compare">
                    <span>♡ Add to wishlist</span> &nbsp; | &nbsp; <span>⇄ Add to compare</span>
                </div>
                <?php if (isset($success_message)): ?>
                    <div class="success-message">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
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
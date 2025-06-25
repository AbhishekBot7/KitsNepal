<?php
session_start();
require_once('dbconnect.php');

$error_message = '';
$products = [];
$cart_message = '';

// Fetch products
if (!isset($conn) || !$conn) {
    $error_message = 'Database connection not established.';
} else {
    $query = "SELECT * FROM product";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    } else {
        $error_message = 'Query failed: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Football Kits Nepal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/Kits.css">
</head>
<body>
<?php include '../components/nav.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="kits-container">
            <div class="hero-content">
                <h1>Premium Football Kits</h1>
                <p>Discover our exclusive collection of authentic football jerseys and kits from top clubs and national teams worldwide.</p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products">
        <div class="kits-container">
            <?php if (!empty($cart_message)): ?>
                <div class="cart-message">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($cart_message) ?></span>
                </div>
            <?php endif; ?>

            <!-- Search and Sort -->
            <div class="product-filters">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search products...">
                </div>
                <div class="sort-options">
                    <select id="sortSelect">
                        <option value="default">Sort by</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="name-asc">Name: A to Z</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="product-grid">
                <?php if ($error_message): ?>
                    <div style="color: red;"><?= htmlspecialchars($error_message) ?></div>
                <?php elseif (empty($products)): ?>
                    <div>No products available.</div>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                        <div class="product-card" data-name="<?= strtolower(htmlspecialchars($product['p_name'])) ?>" data-price="<?= (int)$product['p_price'] ?>">
                        <div class="product-image">
                            <img src="../img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['p_name']) ?>">
                            <div class="product-overlay">
                                    <button class="quick-view" data-id="<?= $product['product_id'] ?>"><i class="far fa-eye"></i> Quick View</button>
                                </div>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['p_name']) ?></h3>
                            <div class="price">Rs. <?= number_format($product['p_price']) ?></div>
                            <div class="product-actions">
                                    <button class="add-to-cart-btn" data-id="<?= $product['product_id'] ?>"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                                </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Add to Cart Modal -->
    <div id="addToCartModal" class="modal">
        <div class="modal-content">
            <span class="close-add-cart-modal">&times;</span>
            <div class="modal-body">
                <form id="addToCartForm">
                    <input type="hidden" name="product_id" id="cartProductId">
                    <label for="cartSize">Size:</label>
                    <select name="size" id="cartSize" required>
                        <option value="">Select size</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                    <label for="cartQty">Quantity:</label>
                    <input type="number" name="quantity" id="cartQty" min="1" value="1" required>
                    <button type="submit" name="add_to_cart"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                </form>
                <div id="addCartMessage" style="margin-top: 10px;"></div>
            </div>
        </div>
    </div>

    <script>
        // Modal open/close logic
        const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
        const addToCartModal = document.getElementById('addToCartModal');
        const closeModal = document.querySelector('.close-add-cart-modal');
        const cartProductIdInput = document.getElementById('cartProductId');

        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                cartProductIdInput.value = this.getAttribute('data-id');
                addToCartModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });

        closeModal.addEventListener('click', () => {
            addToCartModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        window.addEventListener('click', (e) => {
            if (e.target === addToCartModal) {
                addToCartModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // AJAX Add to Cart
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
                e.preventDefault();
            const formData = new FormData(this);
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById('addCartMessage');
                msg.textContent = data.message;
                msg.style.color = data.success ? 'green' : 'red';
                if (data.success) {
                    setTimeout(() => {
                        msg.textContent = '';
                        addToCartModal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }, 1000);
                }
            })
            .catch(() => {
                document.getElementById('addCartMessage').textContent = 'Something went wrong.';
            });
        });

        // Filter & Sort
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const productCards = document.querySelectorAll('.product-card');
        const productGrid = document.querySelector('.product-grid');

        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            productCards.forEach(card => {
                const name = card.getAttribute('data-name');
                card.style.display = name.includes(term) ? 'block' : 'none';
            });
        });

        sortSelect.addEventListener('change', function () {
            const value = this.value;
            const sorted = Array.from(productCards).sort((a, b) => {
                if (value === 'price-asc') return a.dataset.price - b.dataset.price;
                if (value === 'price-desc') return b.dataset.price - a.dataset.price;
                if (value === 'name-asc') return a.dataset.name.localeCompare(b.dataset.name);
                return 0;
            });
            sorted.forEach(card => productGrid.appendChild(card));
        });
    </script>
</body>
</html>

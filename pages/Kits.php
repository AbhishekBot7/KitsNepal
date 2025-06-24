<?php
session_start();
require_once('dbconnect.php');

$error_message = '';
$products = [];
$cart_message = '';

if (!isset($conn) || !$conn) {
    $error_message = 'Database connection not established.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
        $cart_message = "Added to cart!";
    }
}

if (!$error_message) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Football Kits Nepal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #ff3c00;
            --primary-hover: #e63400;
            --secondary-color: #1a1a1a;
            --background: #fafbfc;
            --surface: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, #ff6b3d 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient-dark);
            color: var(--surface);
            padding: 100px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.5;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, var(--surface) 0%, #f1f5f9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Products Section */
        .products {
            padding: 80px 0;
        }

        /* Success Message */
        .cart-message {
            background: var(--success-color);
            color: var(--surface);
            padding: 16px 24px;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
            font-weight: 500;
            text-align: center;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 40px;
        }

        /* Product Filters */
        .product-filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 48px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }

        .search-box input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 16px;
            background: var(--surface);
            transition: var(--transition);
            outline: none;
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 60, 0, 0.1);
        }

        .sort-options select {
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 16px;
            background: var(--surface);
            cursor: pointer;
            outline: none;
            transition: var(--transition);
            min-width: 180px;
        }

        .sort-options select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 60, 0, 0.1);
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 32px;
            margin-bottom: 60px;
        }

        /* Product Card */
        .product-card {
            background: var(--surface);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            transition: var(--transition);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .product-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 2;
            display: flex;
            gap: 8px;
        }

        .grade-badge, .new-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grade-badge {
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-primary);
            backdrop-filter: blur(10px);
        }

        .new-badge {
            background: var(--gradient-primary);
            color: var(--surface);
        }

        .product-image {
            position: relative;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            overflow: hidden;
            background: #f8f9fa;
        }

        .product-image img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .quick-view {
            background: var(--surface);
            color: var(--text-primary);
            border: none;
            padding: 12px 24px;
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-view:hover {
            background: var(--primary-color);
            color: var(--surface);
            transform: translateY(-2px);
        }

        .product-info h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
            line-height: 1.3;
        }

        .price {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .product-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .add-to-cart-form {
            flex: 1;
        }

        .add-to-cart-btn, .login-to-buy {
            width: 100%;
            background: var(--gradient-primary);
            color: var(--surface);
            border: none;
            padding: 14px 20px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 15px;
        }

        .add-to-cart-btn:hover, .login-to-buy:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .wishlist-btn {
            background: var(--surface);
            border: 2px solid var(--border-color);
            color: var(--text-secondary);
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: var(--surface);
            margin: 5% auto;
            padding: 40px;
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 600px;
            position: relative;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            color: var(--text-muted);
            font-size: 28px;
            font-weight: 300;
            cursor: pointer;
            transition: var(--transition);
        }

        .close-modal:hover {
            color: var(--text-primary);
            transform: scale(1.1);
        }

        /* Footer */
        footer {
            background: var(--secondary-color);
            color: var(--surface);
            padding: 40px 0;
            text-align: center;
        }

        footer p {
            opacity: 0.8;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 16px;
            }

            .hero {
                padding: 60px 0 40px;
            }

            .products {
                padding: 40px 0;
            }

            .product-filters {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }

            .search-box {
                max-width: none;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }

            .modal-content {
                margin: 10% auto;
                padding: 24px;
                width: 95%;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-card {
                padding: 16px;
            }

            .product-image img {
                height: 180px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation would be included here via PHP -->
    <!-- <?php include '../components/nav.php'; ?> -->

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Premium Football Kits</h1>
                <p>Discover our exclusive collection of authentic football jerseys and kits from top clubs and national teams worldwide.</p>
            </div>
        </div>
    </section>

    <section class="products">
        <div class="container">
            <!-- Success Message (would be conditionally shown via PHP) -->
            <?php if (!empty($cart_message)): ?>
                <div class="cart-message" style="display: flex;">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($cart_message) ?></span>
                </div>
            <?php endif; ?>

            <!-- Product Filters -->
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
                <?php if (!empty($error_message)): ?>
                    <div style="color: red; font-weight: bold; padding: 1rem; background: #fff0f0; border-radius: 8px; margin-bottom: 2rem;">
                        Error loading products: <?= htmlspecialchars(
                            $error_message) ?>
                    </div>
                <?php endif; ?>
                <?php if (empty($products) && empty($error_message)): ?>
                    <div style="color: #555; font-weight: 500; padding: 1rem; background: #f8f8f8; border-radius: 8px; margin-bottom: 2rem;">
                        No products found in the database.
                    </div>
                <?php endif; ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card"
                        data-name="<?= strtolower(htmlspecialchars($product['p_name'])) ?>"
                        data-price="<?= (int)$product['p_price'] ?>">
                        <div class="product-badge">
                            <span class="grade-badge"><?= htmlspecialchars($product['p_grade']) ?></span>
                        </div>
                        <div class="product-image">
                            <img src="../img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['p_name']) ?>">
                            <div class="product-overlay">
                                <button class="quick-view" data-id="<?= $product['product_id'] ?>">
                                    <i class="far fa-eye"></i> Quick View
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['p_name']) ?></h3>
                            <div class="price">Rs. <?= number_format($product['p_price']) ?></div>
                            <div class="product-actions">
                                <form method="post" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                                <button class="wishlist-btn" data-id="<?= $product['product_id'] ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Quick View Modal -->
    <div id="quickViewModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Football Kits Nepal. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const productCards = document.querySelectorAll('.product-card');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            productCards.forEach(card => {
                const productName = card.getAttribute('data-name');
                if (productName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Sort functionality
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const productGrid = document.querySelector('.product-grid');
            const cards = Array.from(productCards);

            cards.sort((a, b) => {
                switch (sortValue) {
                    case 'price-asc':
                        return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                    case 'price-desc':
                        return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                    case 'name-asc':
                        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                    default:
                        return 0;
                }
            });

            cards.forEach(card => productGrid.appendChild(card));
        });

        // Quick View Modal
        const quickViewBtns = document.querySelectorAll('.quick-view');
        const modal = document.getElementById('quickViewModal');
        const modalContent = document.querySelector('.modal-body');
        const closeBtn = document.querySelector('.close-modal');

        quickViewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                modalContent.innerHTML = `
                    <div class="quick-view-content">
                        <h3>Product Details</h3>
                        <p>Loading product details for ID: ${productId}</p>
                        <p>This would typically load via AJAX from your backend.</p>
                    </div>
                `;
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Wishlist functionality
        const wishlistBtns = document.querySelectorAll('.wishlist-btn');
        wishlistBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.style.color = '#ff3c00';
                    this.style.borderColor = '#ff3c00';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.style.color = '';
                    this.style.borderColor = '';
                }
            });
        });

        // Add to cart animation
        const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                // Show success message
                const cartMessage = document.querySelector('.cart-message');
                if (cartMessage) {
                    cartMessage.style.display = 'flex';
                    setTimeout(() => {
                        cartMessage.style.display = 'none';
                    }, 3000);
                }

                // Button animation
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Added!';
                this.style.background = '#10b981';

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.background = '';
                }, 2000);
            });
        });

        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>
</body>
</html>

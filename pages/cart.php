<?php
session_start();
require_once('dbconnect.php');
$is_logged_in = isset($_SESSION['user_id']);
$cart_items = [];
$total = 0;
$message = '';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];

    // Handle Add to My Order
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_order']) && isset($_POST['cart_id'])) {
        $cart_id = intval($_POST['cart_id']);
        // Check if already in order_table
        $check = $conn->prepare("SELECT order_id FROM order_table WHERE cart_id = ?");
        $check->bind_param("i", $cart_id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $message = "This item is already in your orders.";
        } else {
            // Get cart item details for total price
            $stmt = $conn->prepare("SELECT c.*, p.p_price FROM cart c JOIN product p ON c.product_id = p.product_id WHERE c.cart_id = ? AND c.user_id = ?");
            $stmt->bind_param("ii", $cart_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($item = $result->fetch_assoc()) {
                $total_price = $item['p_price'] * $item['quantity'];
                $insert = $conn->prepare("INSERT INTO order_table (cart_id, status, total_price) VALUES (?, 'Not Confirmed', ?)");
                $insert->bind_param("id", $cart_id, $total_price);
                $insert->execute();
                $insert->close();
                $message = "Added to your orders!";
            }
            $stmt->close();
        }
        $check->close();
    }

    // Fetch cart items
    $stmt = $conn->prepare("SELECT c.*, p.p_name, p.p_price, p.image, p.p_grade FROM cart c JOIN product p ON c.product_id = p.product_id WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['p_price'] * $row['quantity'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Football Kits Nepal</title>
    <link rel="stylesheet" href="../css/Home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-container {
            padding: 40px 20px;
            background: #fff;
            min-height: 70vh;
        }

        .cart-items {
            max-width: 800px;
            margin: 0 auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            background: #f8f8f8;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .cart-item-details {
            flex-grow: 1;
            margin-left: 20px;
        }

        .cart-item-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: #ff3c00;
            font-weight: bold;
        }

        .cart-summary {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
        }

        .cart-total {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .empty-cart {
            text-align: center;
            padding: 40px;
            font-size: 1.2rem;
            color: #666;
        }

        .remove-btn {
            background: #ff0000;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .remove-btn:hover {
            background: #cc0000;
        }

        .checkout-btn {
            background: #ff3c00;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            margin-top: 20px;
            float: right;
        }

        .checkout-btn:hover {
            background: #e63500;
        }

        .cart-icon {
            position: relative;
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #ff3c00;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include '../components/nav.php'; ?>

    <div class="cart-container">
        <div class="cart-items">
            <h2>Your Shopping Cart</h2>
            <?php if ($message): ?>
                <div style="color: #10b981; margin-bottom: 1rem;"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <div id="cart-items-container">
                <?php if ($is_logged_in): ?>
                    <?php if (empty($cart_items)): ?>
                        <div class="empty-cart">Your cart is empty</div>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item">
                                <img src="../img/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['p_name']); ?>" style="width:70px;height:70px;border-radius:8px;object-fit:cover;">
                                <div class="cart-item-details">
                                    <div class="cart-item-name"><?php echo htmlspecialchars($item['p_name']); ?></div>
                                    <div class="cart-item-price">NPR <?php echo htmlspecialchars($item['p_price']); ?></div>
                                    <div>Grade: <?php echo htmlspecialchars(ucfirst($item['p_grade'])); ?></div>
                                    <div>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></div>
                                    <form method="post" style="margin-top:10px;">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <button type="submit" name="add_to_order" class="checkout-btn" style="float:none;">Add to My Order</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Fallback to localStorage for guests -->
                <?php endif; ?>
            </div>
            <div class="cart-summary">
                <div class="cart-total">
                    Total: NPR <span id="cart-total"><?php echo $is_logged_in ? $total : 0; ?></span>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Football Kits Nepal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 
<?php
session_start();
require_once('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch order history for this user
$orders = [];
$stmt = $conn->prepare(
    "SELECT o.*, c.size, c.quantity, p.p_name, p.image, p.p_price
     FROM order_table o
     JOIN cart c ON o.cart_id = c.cart_id
     JOIN product p ON c.product_id = p.product_id
     WHERE c.user_id = ?
     ORDER BY o.order_id DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order History - Football Kits Nepal</title>
    <link rel="stylesheet" href="../css/Home.css">
    <style>
        .order-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            padding: 2rem;
        }

        .order-title {
            font-size: 2rem;
            color: #1d3557;
            margin-bottom: 1.5rem;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-table th,
        .order-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .order-table th {
            background: #f8f9fa;
            color: #1d3557;
        }

        .order-table tr:last-child td {
            border-bottom: none;
        }

        .status-confirmed {
            color: #10b981;
            font-weight: 600;
        }

        .status-pending {
            color: #e63946;
            font-weight: 600;
        }

        .confirm-btn {
            background: #e63946;
            color: #fff;
            border: none;
            padding: 7px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .confirm-btn:hover {
            background: #c1121f;
        }

        .message {
            margin-bottom: 1rem;
            color: #10b981;
            font-weight: 600;
        }

        .order-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <?php include '../components/nav.php'; ?>
    <div class="order-container">
        <div class="order-title">Order History</div>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if (empty($orders)): ?>
            <p>You have no orders yet.</p>
        <?php else: ?>
            <table class="order-table">
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['p_name']) ?></td>
                        <td><img src="../img/<?= htmlspecialchars($order['image']) ?>" class="order-img" alt=""></td>
                        <td><?= htmlspecialchars($order['size']) ?></td>
                        <td><?= htmlspecialchars($order['quantity']) ?></td>
                        <td class="status-<?= strtolower($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></td>
                        <td>Rs. <?= number_format($order['total_price']) ?></td>
                        <td>
                            <?php if (strtolower($order['status']) !== 'confirmed'): ?>
                                <form method="POST" action="payment.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <input type="hidden" name="amount" value="<?= $order['total_price'] ?>">
                                    <input type="hidden" name="productName" value="<?= $order['p_name'] ?>">
                                    <button type="submit" name="pay_order" class="confirm-btn">Confirm</button>
                                </form>
                            <?php else: ?>
                                <span class="status-confirmed">Confirmed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
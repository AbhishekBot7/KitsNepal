<?php
session_start();
require_once('../dbconnect.php');

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Get statistics
$stmt = $conn->prepare("SELECT COUNT(*) as total_orders FROM order_table");
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT COUNT(*) as total_products FROM product");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Football Kits Nepal</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php">Manage Products</a></li>
                <li><a href="add_product.php">Add Products</a></li>
                <li><a href="orders.php">View Orders</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p><?php echo $orders['total_orders']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p><?php echo $products['total_products']; ?></p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

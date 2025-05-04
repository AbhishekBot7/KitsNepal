<?php
session_start();
// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
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
        <nav class="admin-sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <h1>Welcome to Admin Dashboard</h1>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p><?php
                        require_once '../../config/database.php';
                        $query = "SELECT COUNT(*) as total FROM product";
                        $result = mysqli_query($conn, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo $row['total'];
                    ?></p>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 
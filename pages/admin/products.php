<?php
session_start();
// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Football Kits Nepal</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php" class="active">Products</a></li>
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <h1>Manage Products</h1>
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM product ORDER BY product_id DESC";
                        $result = mysqli_query($conn, $query);
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['product_id'] . "</td>";
                            echo "<td><img src='../../img/" . $row['image'] . "' alt='" . $row['p_name'] . "' width='50'></td>";
                            echo "<td>" . $row['p_name'] . "</td>";
                            echo "<td>NPR " . $row['p_price'] . "</td>";
                            echo "<td>" . $row['p_qty'] . "</td>";
                            echo "<td>" . ucfirst($row['p_grade']) . "</td>";
                            echo "<td>
                                    <a href='edit_product.php?id=" . $row['product_id'] . "' class='btn-edit'>Edit</a>
                                    <a href='delete_product.php?id=" . $row['product_id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html> 
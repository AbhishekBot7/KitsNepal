<?php
session_start();
require_once('../dbconnect.php');
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}
if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}
$product_id = intval($_GET['id']);
// Get product image before deleting
$query = "SELECT image FROM product WHERE product_id = '$product_id'";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);
if ($product) {
    // Delete the product
    $query = "DELETE FROM product WHERE product_id = '$product_id'";
    if (mysqli_query($conn, $query)) {
        // Delete the image file if it exists
        if ($product['image']) {
            $image_path = "../../img/" . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        header('Location: products.php');
        exit();
    } else {
        header('Location: products.php?error=delete');
        exit();
    }
} else {
    header('Location: products.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product - Football Kits Nepal</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        </main>
    </div>
</body>
</html> 
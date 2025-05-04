<?php
session_start();
// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../../config/database.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM product WHERE product_id = '$id'";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload
    $image = $product['image']; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../../img/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        
        // Move uploaded file
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }
    
    $query = "UPDATE product SET 
              name = '$name', 
              price = '$price', 
              quantity = '$quantity',
              grade = '$grade',
              description = '$description',
              image = '$image' 
              WHERE product_id = '$id'";
    if (mysqli_query($conn, $query)) {
        header('Location: products.php');
        exit;
    } else {
        $error = "Error updating product: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Football Kits Nepal</title>
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
            <h1>Edit Product</h1>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (NPR):</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="grade">Grade:</label>
                    <select id="grade" name="grade" required>
                        <option value="original" <?php echo $product['grade'] === 'original' ? 'selected' : ''; ?>>Original</option>
                        <option value="replica" <?php echo $product['grade'] === 'replica' ? 'selected' : ''; ?>>Replica</option>
                        <option value="training" <?php echo $product['grade'] === 'training' ? 'selected' : ''; ?>>Training</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <p>Current image: <?php echo htmlspecialchars($product['image']); ?></p>
                </div>
                
                <button type="submit" class="btn-submit">Update Product</button>
            </form>
        </main>
    </div>
</body>
</html> 
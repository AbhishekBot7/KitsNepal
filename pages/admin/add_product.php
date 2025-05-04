<?php
session_start();
// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../../img/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        
        // Move uploaded file
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }
    
    $query = "INSERT INTO product (p_name, p_price, p_qty, p_grade, p_desc, image) 
              VALUES ('$name', '$price', '$quantity', '$grade', '$description', '$image')";
    if (mysqli_query($conn, $query)) {
        header('Location: products.php');
        exit;
    } else {
        $error = "Error adding product: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Football Kits Nepal</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="add_product.php" class="active">Add Product</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <h1>Add New Product</h1>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (NPR):</label>
                    <input type="number" id="price" name="price" required>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                
                <div class="form-group">
                    <label for="grade">Grade:</label>
                    <select id="grade" name="grade" required>
                        <option value="original">Original</option>
                        <option value="replica">Replica</option>
                        <option value="training">Training</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                
                <button type="submit" class="btn-submit">Add Product</button>
            </form>
        </main>
    </div>
</body>
</html> 
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
// Fetch product data
$stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    header('Location: products.php');
    exit();
}
// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $grade = $_POST['grade'];
    $description = $_POST['description'];
    $image = $product['image']; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../../img/";
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_image = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_image;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $new_image;
        }
    }
    $stmt = $conn->prepare("UPDATE product SET p_name=?, p_price=?, p_qty=?, p_grade=?, p_desc=?, image=? WHERE product_id=?");
    $stmt->bind_param("siisssi", $name, $price, $quantity, $grade, $description, $image, $product_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated successfully!";
        header('Location: products.php');
        exit();
    } else {
        $error = "Error updating product: " . $conn->error;
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
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 220px;
            background: #263445;
            color: #fff;
            padding: 32px 0 0 0;
            min-height: 100vh;
        }
        .admin-sidebar h2 {
            margin-left: 24px;
            margin-bottom: 32px;
            font-size: 1.6rem;
        }
        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }
        .admin-sidebar ul li {
            margin-bottom: 16px;
        }
        .admin-sidebar ul li a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .admin-sidebar ul li a.active,
        .admin-sidebar ul li a:hover {
            background: #32425a;
        }
        .admin-content {
            flex: 1;
            background: #fff;
            min-height: 100vh;
            padding: 40px 30px;
            box-sizing: border-box;
        }
        .product-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .btn-submit {
            background-color: #ff3c00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #ff6b3d;
        }
        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php" class="active">Products</a></li>
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
        <main class="admin-content">
            <h1>Edit Product</h1>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['p_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (NPR):</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['p_price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['p_qty']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="grade">Grade:</label>
                    <select id="grade" name="grade" required>
                        <option value="original" <?php if($product['p_grade']==='original') echo 'selected'; ?>>Original</option>
                        <option value="replica" <?php if($product['p_grade']==='replica') echo 'selected'; ?>>Replica</option>
                        <option value="training" <?php if($product['p_grade']==='training') echo 'selected'; ?>>Training</option>
                        <option value="Player" <?php if($product['p_grade']==='Player') echo 'selected'; ?>>Player Version</option>
                        <option value="Fan" <?php if($product['p_grade']==='Fan') echo 'selected'; ?>>Fan Version</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($product['p_desc']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Product Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <?php if ($product['image']): ?>
                        <br><img src="../../img/<?php echo $product['image']; ?>" alt="Current Image" width="80">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-submit">Update Product</button>
            </form>
        </main>
    </div>
</body>
</html> 
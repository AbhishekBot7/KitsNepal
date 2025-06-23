<?php
session_start();
// Check if user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

require_once '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../../img/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Generate unique filename
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $image = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $image;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            $error = "File is not an image.";
        }
        // Check file size (5MB max)
        else if ($_FILES["image"]["size"] > 5000000) {
            $error = "File is too large. Maximum size is 5MB.";
        }
        // Allow certain file formats
        else if($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
            $error = "Only JPG, JPEG & PNG files are allowed.";
        }
        // Upload file
        else if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File uploaded successfully
        } else {
            $error = "Error uploading file.";
        }
    } else {
        $error = "Please select an image.";
    }
    
    // If no errors, insert into database
    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO product (p_name, p_price, p_qty, p_grade, p_desc, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisss", $name, $price, $quantity, $grade, $description, $image);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully!";
            header('Location: products.php');
            exit();
        } else {
            $error = "Error adding product: " . $conn->error;
        }
        $stmt->close();
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
                <li><a href="products.php">Products</a></li>
                <li><a href="add_product.php" class="active">Add Product</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <h1>Add New Product</h1>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (NPR):</label>
                    <input type="number" id="price" name="price" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="0" required>
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
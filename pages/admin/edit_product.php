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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/forms.css">
</head>
<body>
    <div class="admin-container">
        <?php include_once('../../includes/admin/sidebar.php'); ?>
        
        <button class="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <main class="admin-content">
            <div class="page-header">
                <h1>Edit Product</h1>
                <a href="products.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form class="form-container" action="edit_product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Enter product name" 
                               value="<?php echo htmlspecialchars($product['p_name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (NPR)</label>
                        <div class="input-group">
                            <span class="input-icon">NPR</span>
                            <input type="number" id="price" name="price" step="0.01" 
                                   placeholder="0.00" required
                                   value="<?php echo htmlspecialchars($product['p_price']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" 
                               placeholder="Enter quantity" required
                               value="<?php echo htmlspecialchars($product['p_qty']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="grade">Grade</label>
                        <div class="select-wrapper">
                            <select id="grade" name="grade" required>
                                <option value="" disabled>Select grade</option>
                                <option value="A" <?php if($product['p_grade']==='A') echo 'selected'; ?>>Grade A</option>
                                <option value="B" <?php if($product['p_grade']==='B') echo 'selected'; ?>>Grade B</option>
                                <option value="C" <?php if($product['p_grade']==='C') echo 'selected'; ?>>Grade C</option>
                                <option value="Player" <?php if($product['p_grade']==='Player') echo 'selected'; ?>>Player Version</option>
                                <option value="Fan" <?php if($product['p_grade']==='Fan') echo 'selected'; ?>>Fan Version</option>
                            </select>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" 
                                 placeholder="Enter product description" 
                                 required><?php echo htmlspecialchars($product['p_desc']); ?></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="image">Product Image</label>
                        <?php if ($product['image']): ?>
                            <div class="current-image">
                                <p>Current Image:</p>
                                <img src="../../img/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="Current Product" class="current-product-image">
                            </div>
                        <?php endif; ?>
                        
                        <div class="file-upload">
                            <input type="file" id="image" name="image" accept="image/*">
                            <div class="file-upload-preview">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Click to upload new image or drag and drop</p>
                                <span>PNG, JPG, JPEG (Max. 5MB)</span>
                            </div>
                        </div>
                        <div class="image-preview" id="imagePreview"></div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="products.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.querySelector('.menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.admin-sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            if (window.innerWidth <= 768 && sidebar && menuToggle && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
        
        // Update active state on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.querySelector('.admin-sidebar');
                if (sidebar) sidebar.classList.remove('active');
            }
        });

        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        
        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.innerHTML = `
                            <div class="preview-image">
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="remove-image">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        
                        // Add event listener to remove button
                        const removeBtn = imagePreview.querySelector('.remove-image');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function() {
                                imageInput.value = '';
                                imagePreview.innerHTML = '';
                            });
                        }
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>
<?php
session_start();
// Check if user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

require_once '../dbconnect.php';

$error = null; // Initialize error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
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
                // Attempt to create directory, throw exception if it fails
                if (!mkdir($target_dir, 0777, true)) {
                    throw new Exception("Failed to create image upload directory.");
                }
            }
            
            // Generate unique filename
            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $image = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $image;
            
            // Check if image file is a actual image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check === false) {
                throw new Exception("File is not an image.");
            }
            // Check file size (5MB max)
            else if ($_FILES["image"]["size"] > 5000000) {
                throw new Exception("File is too large. Maximum size is 5MB.");
            }
            // Allow certain file formats
            else if($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
                throw new Exception("Only JPG, JPEG & PNG files are allowed.");
            }
            // Upload file
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                error_log("TMP: " . $_FILES["image"]["tmp_name"]);
                error_log("TARGET: " . $target_file);
                error_log("IS UPLOADED: " . (is_uploaded_file($_FILES["image"]["tmp_name"]) ? "YES" : "NO"));
                error_log("DIR EXISTS: " . (file_exists($target_dir) ? "YES" : "NO"));
                throw new Exception("Error uploading file Image Issue.");
}
        } else {
            throw new Exception("Please select an image.");
        }
        
        // If no errors so far, insert into database
        $stmt = $conn->prepare("INSERT INTO product (p_name, p_price, p_qty, p_grade, p_desc, image) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("siisss", $name, $price, $quantity, $grade, $description, $image);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully!";
            header('Location: products.php');
            exit();
        } else {
            throw new Exception("Error adding product to database: " . $stmt->error);
        }
        $stmt->close();
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Football Kits Nepal</title>
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
                <h1>Add New Product</h1>
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
            
            <form class="form-container" action="add_product.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Enter product name">
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (NPR)</label>
                        <div class="input-group">
                            <span class="input-icon">NPR</span>
                            <input type="number" id="price" name="price" step="0.01" 
                                   placeholder="0.00" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" 
                               placeholder="Enter quantity" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="grade">Grade</label>
                        <div class="select-wrapper">
                            <select id="grade" name="grade" required>
                                <option value="" disabled selected>Select grade</option>
                                <option value="A">Grade A</option>
                                <option value="B">Grade B</option>
                                <option value="C">Grade C</option>
                            </select>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" 
                                 placeholder="Enter product description" required></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="image">Product Image</label>
                        <div class="file-upload">
                            <input type="file" id="image" name="image" accept="image/*" required>
                            <div class="file-upload-preview">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Click to upload or drag and drop</p>
                                <span>PNG, JPG, JPEG (Max. 5MB)</span>
                            </div>
                        </div>
                        <div class="image-preview" id="imagePreview"></div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-outline">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Product
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
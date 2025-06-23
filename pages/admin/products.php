<?php
session_start();
require_once('../dbconnect.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['p_name'];
    $price = $_POST['p_price'];
    $qty = $_POST['p_qty'];
    $grade = $_POST['p_grade'];
    $desc = $_POST['p_desc'];

    // Handle image upload
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $stmt = $conn->prepare("INSERT INTO product (p_name, p_price, p_qty, p_grade, p_desc, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $qty, $grade, $desc, $target_file]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Football Kits Nepal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/products.css">
</head>
<body>
    <div class="admin-container">
        <?php include_once('../../includes/admin/sidebar.php'); ?>
        
        <button class="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>

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
    
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.admin-sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
        
        // Update active state on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.admin-sidebar').classList.remove('active');
            }
        });
    </script>
</body>
</html>

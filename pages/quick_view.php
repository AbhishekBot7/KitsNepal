<?php
require_once('dbconnect.php');

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    echo '<div class="quick-view-content"><h3>Product Not Found</h3><p>Invalid product ID.</p></div>';
    exit;
}

$product_id = intval($_GET['product_id']);
$sql = "SELECT * FROM product WHERE product_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $product = $result->fetch_assoc();
    echo '<div class="quick-view-content">';
    echo '<div style="text-align:center; margin-bottom:1rem;"><img src="../img/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['p_name']) . '" style="max-width:220px; max-height:220px; border-radius:12px;"></div>';
    echo '<h3>' . htmlspecialchars($product['p_name']) . '</h3>';
    echo '<div style="color:#e63946; font-size:1.3rem; font-weight:700; margin-bottom:0.5rem;">Rs. ' . number_format($product['p_price']) . '</div>';
    echo '<div style="margin-bottom:0.5rem;"><span style="background:#f1faee; color:#1d3557; padding:4px 12px; border-radius:12px; font-size:0.95rem;">' . htmlspecialchars($product['p_grade']) . '</span></div>';
    echo '<p style="color:#444; margin-bottom:1rem;">' . htmlspecialchars($product['p_desc']) . '</p>';
    echo '</div>';
} else {
    echo '<div class="quick-view-content"><h3>Product Not Found</h3><p>No product found for this ID.</p></div>';
}
$stmt->close();
$conn->close(); 
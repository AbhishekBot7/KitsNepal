<?php
session_start();
header('Content-Type: application/json');
require_once('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add to cart.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $size = $_POST['size'] ?? '';
    $quantity = max(1, intval($_POST['quantity'] ?? 1));
    $user_id = $_SESSION['user_id'];
    if (!$product_id || !$size) {
        echo json_encode(['success' => false, 'message' => 'Invalid product or size.']);
        exit;
    }
    $stmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
    $stmt->bind_param("iis", $user_id, $product_id, $size);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $new_qty = $row['quantity'] + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $update->bind_param("ii", $new_qty, $row['cart_id']);
        $update->execute();
        $update->close();
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, size, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $insert->bind_param("iisi", $user_id, $product_id, $size, $quantity);
        $insert->execute();
        $insert->close();
    }
    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'Added to cart!']);
    exit;
}
echo json_encode(['success' => false, 'message' => 'Invalid request.']); 
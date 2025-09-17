<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request (for CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database configuration
require 'database_connection.php';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['productId'])) {
        throw new Exception('Invalid input data');
    }

    $productId = intval($input['productId']);
    $quantity  = isset($input['quantity']) ? max(1, intval($input['quantity'])) : 1;

    if ($productId <= 0) {
        throw new Exception('Invalid product ID');
    }

    // Get user session ID
    $sessionId = session_id();

    // Check if product already exists in cart
    $checkSql = "SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$sessionId, $productId]);
    $existingItem = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // Update quantity
        $newQuantity = $existingItem['quantity'] + $quantity;
        $updateSql = "UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Insert new product
        $insertSql = "INSERT INTO cart (session_id, product_id, quantity, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$sessionId, $productId, $quantity]);
    }

    // Get total items in cart
    $countSql = "SELECT SUM(quantity) AS total FROM cart WHERE session_id = ?";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute([$sessionId]);
    $cartTotal = $countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // Success response
    echo json_encode([
        'success'   => true,
        'message'   => 'Product added to cart successfully',
        'cartCount' => $cartTotal
    ]);

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => 'Error adding product to cart: ' . $e->getMessage()
    ]);
}

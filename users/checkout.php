<?php
session_start();
header('Content-Type: application/json');
require '../includes/database_connection.php';

// Check for user login
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to check out.']);
    exit;
}

// Get the data sent from the JavaScript fetch request
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['total']) || !isset($input['subtotal'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid checkout data received.']);
    exit;
}

// Fetch current cart items from the database for validation
$stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.session_id = ?");
$stmt->execute([session_id()]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cartItems) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty.']);
    exit;
}

// === SERVER-SIDE VALIDATION ===
// Recalculate the subtotal on the server to prevent tampering
$serverSubtotal = 0;
foreach ($cartItems as $item) {
    $serverSubtotal += $item['price'] * $item['quantity'];
}

// Compare server-calculated subtotal with the subtotal sent from the client
// We use a small tolerance (0.01) for floating-point comparisons
if (abs($serverSubtotal - floatval($input['subtotal'])) > 0.01) {
    echo json_encode([
        'success' => false,
        'message' => 'Price mismatch detected. Please refresh the page and try again.'
    ]);
    exit;
}

// If validation passes, use the final total sent from the client,
// as it correctly includes taxes, shipping, and discounts.
$finalTotal = floatval($input['total']);

try {
    $pdo->beginTransaction();

    // 1. Create the order using the validated final total
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, created_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $finalTotal]);
    $order_id = $pdo->lastInsertId();

    // 2. Insert the items from the cart into order_items
    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cartItems as $item) {
        $stmt_item->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // 3. Clear the user's cart
    $pdo->prepare("DELETE FROM cart WHERE session_id = ?")->execute([session_id()]);

    $pdo->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    $pdo->rollBack();
    // Use a generic error message for security
    error_log($e->getMessage()); // Log the actual error for debugging
    echo json_encode(['success' => false, 'message' => 'Could not complete your order. Please try again.']);
}
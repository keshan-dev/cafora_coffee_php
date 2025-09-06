<?php
session_start();
header('Content-Type: application/json');
require '../includes/database_connection.php';

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo json_encode(['success'=>false,'message'=>'You must log in']);
    exit;
}

// Fetch cart items
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price 
                       FROM cart c 
                       JOIN products p ON c.product_id=p.id 
                       WHERE c.session_id=?");
$stmt->execute([session_id()]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cartItems) {
    echo json_encode(['success'=>false,'message'=>'Cart is empty']);
    exit;
}

// Calculate total
$total = 0;
foreach($cartItems as $item){
    $total += $item['price'] * $item['quantity'];
}

try {
    $pdo->beginTransaction();

    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, created_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();

    // Insert order items
    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach($cartItems as $item){
        $stmt_item->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // Clear cart
    $pdo->prepare("DELETE FROM cart WHERE session_id=?")->execute([session_id()]);

    $pdo->commit();
    echo json_encode(['success'=>true, 'order_id'=>$order_id]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success'=>false,'message'=>'Checkout failed: '.$e->getMessage()]);
}

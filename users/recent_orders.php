<?php
session_start();
header('Content-Type: application/json');
require '../includes/database_connection.php';

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) { echo json_encode([]); exit; }

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$result = [];
foreach($orders as $order){
    $items_stmt = $pdo->prepare("SELECT oi.quantity, oi.price, p.name 
                                 FROM order_items oi 
                                 JOIN products p ON oi.item_id=p.id 
                                 WHERE oi.order_id=?");
    $items_stmt->execute([$order['order_id']]);
    $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

    $result[] = [
        'order_id'=>$order['order_id'],
        'status'=>$order['status'],
        'total'=>$order['total'],
        'created_at'=>$order['created_at'],
        'items'=>$items
    ];
}

echo json_encode($result);

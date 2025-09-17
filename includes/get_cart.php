<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database configuration
require 'database_connection.php';

try {
    // Ensure PDO is available
    if (!isset($pdo)) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Get user session ID
    $sessionId = session_id();

    // Fetch cart items with product details
    $sql = "SELECT 
                c.id,
                c.quantity,
                p.name,
                p.description,
                p.price,
                p.category,
                p.image_url AS image
            FROM cart c 
            INNER JOIN products p ON c.product_id = p.id 
            WHERE c.session_id = ? 
            ORDER BY c.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sessionId]);

    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate subtotal
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    // Return success response
    echo json_encode([
        'success'  => true,
        'items'    => $cartItems,
        'count'    => count($cartItems),
        'subtotal' => $subtotal
    ]);

} catch (PDOException $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>

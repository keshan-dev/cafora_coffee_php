<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
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
    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['itemId']) || !isset($input['quantity'])) {
        throw new Exception('Invalid input data');
    }

    $itemId = intval($input['itemId']);
    $quantity = intval($input['quantity']);
    $sessionId = session_id();

    if ($quantity < 1) {
        throw new Exception('Quantity must be at least 1');
    }

    // Ensure PDO is available
    if (!isset($pdo)) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Update cart item quantity
    $sql = "UPDATE cart 
            SET quantity = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ? AND session_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$quantity, $itemId, $sessionId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Cart item not found or quantity unchanged');
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Cart updated successfully'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error updating cart: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>

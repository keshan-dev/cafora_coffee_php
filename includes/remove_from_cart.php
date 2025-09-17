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

    if (!$input || !isset($input['itemId'])) {
        throw new Exception('Invalid input data');
    }

    $itemId = intval($input['itemId']);
    $sessionId = session_id();

    // Ensure PDO is available
    if (!isset($pdo)) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Remove cart item
    $sql = "DELETE FROM cart WHERE id = ? AND session_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId, $sessionId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Cart item not found or already removed');
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Item removed from cart successfully'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error removing item: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>

<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$servername = "localhost";
$username = "root";   
$password = "";   
$dbname = "coffe_shop";  

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['itemId'])) {
        throw new Exception('Invalid input data');
    }
    
    $itemId = intval($input['itemId']);
    $sessionId = session_id();
    
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Remove cart item
    $sql = "DELETE FROM cart WHERE id = ? AND session_id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$itemId, $sessionId]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Cart item not found');
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Item removed from cart successfully'
    ]);
    
} catch(Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error removing item: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>
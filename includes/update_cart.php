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
$dbname = "coffee_shop";      

try {
    // Get JSON input
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
    
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update cart item quantity
    $sql = "UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ? AND session_id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$quantity, $itemId, $sessionId]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Cart item not found or no changes made');
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Cart updated successfully'
    ]);
    
} catch(Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error updating cart: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>
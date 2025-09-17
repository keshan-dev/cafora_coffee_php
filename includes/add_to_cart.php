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
    
    if (!$input || !isset($input['productId'])) {
        throw new Exception('Invalid input data');
    }
    
    $productId = intval($input['productId']);
    $quantity = isset($input['quantity']) ? intval($input['quantity']) : 1;
    
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get user session ID (you can modify this based on your user system)
    $sessionId = session_id();
    
    // Check if product already exists in cart
    $checkSql = "SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$sessionId, $productId]);
    $existingItem = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingItem) {
        // Update quantity if product already in cart
        $newQuantity = $existingItem['quantity'] + $quantity;
        $updateSql = "UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Add new item to cart
        $insertSql = "INSERT INTO cart (session_id, product_id, quantity, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$sessionId, $productId, $quantity]);
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart successfully'
    ]);
    
} catch(Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error adding product to cart: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>

<?php

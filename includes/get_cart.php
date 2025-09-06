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
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get user session ID
    $sessionId = session_id();
    
    // SQL query to fetch cart items with product details
    $sql = "SELECT 
                c.id,
                c.quantity,
                p.name,
                p.description,
                p.price,
                p.category,
                p.image_url as image
            FROM cart c 
            INNER JOIN products p ON c.product_id = p.id 
            WHERE c.session_id = ? 
            ORDER BY c.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sessionId]);
    
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'items' => $cartItems,
        'count' => count($cartItems)
    ]);
    
} catch(PDOException $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>
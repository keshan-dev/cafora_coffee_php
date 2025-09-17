<?php
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
    // Ensure PDO is available
    if (!isset($pdo)) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Fetch all active products
    $sql = "SELECT 
                id, 
                name, 
                description, 
                price, 
                category, 
                image_url AS image,
                badge,
                created_at
            FROM products 
            WHERE active = 1 
            ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return success response
    echo json_encode([
        'success'  => true,
        'products' => $products,
        'count'    => count($products)
    ], JSON_UNESCAPED_UNICODE);

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

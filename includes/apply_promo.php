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
    
    if (!$input || !isset($input['promoCode']) || !isset($input['subtotal'])) {
        throw new Exception('Invalid input data');
    }
    
    $promoCode = strtoupper(trim($input['promoCode']));
    $subtotal = floatval($input['subtotal']);
    
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if promo code exists and is active
    $sql = "SELECT * FROM promo_codes 
            WHERE code = ? AND active = 1 
            AND (expires_at IS NULL OR expires_at > NOW())
            AND (usage_limit IS NULL OR usage_count < usage_limit)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$promoCode]);
    
    $promo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$promo) {
        throw new Exception('Invalid or expired promo code');
    }
    
    // Check minimum order amount
    if ($promo['minimum_amount'] && $subtotal < $promo['minimum_amount']) {
        throw new Exception('Minimum order amount of $' . number_format($promo['minimum_amount'], 2) . ' required');
    }
    
    // Calculate discount
    $discount = 0;
    if ($promo['discount_type'] === 'percentage') {
        $discount = ($subtotal * $promo['discount_value']) / 100;
        // Apply maximum discount limit if set
        if ($promo['max_discount'] && $discount > $promo['max_discount']) {
            $discount = $promo['max_discount'];
        }
    } else if ($promo['discount_type'] === 'fixed') {
        $discount = $promo['discount_value'];
        // Don't allow discount to exceed subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }
    }
    
    // Update usage count
    $updateSql = "UPDATE promo_codes SET usage_count = usage_count + 1 WHERE id = ?";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([$promo['id']]);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'discount' => $discount,
        'message' => 'Promo code applied successfully'
    ]);
    
} catch(Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>

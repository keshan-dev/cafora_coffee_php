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

// Database connection
require 'database_connection.php';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['promoCode']) || !isset($input['subtotal'])) {
        throw new Exception('Invalid input data');
    }

    $promoCode = strtoupper(trim($input['promoCode']));
    $subtotal  = floatval($input['subtotal']);

    if ($subtotal <= 0) {
        throw new Exception('Invalid subtotal amount');
    }

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
    $minAmount = isset($promo['minimum_amount']) ? floatval($promo['minimum_amount']) : 0;
    if ($minAmount > 0 && $subtotal < $minAmount) {
        throw new Exception('Minimum order amount of $' . number_format($minAmount, 2) . ' required');
    }

    // Calculate discount
    $discount = 0;
    if ($promo['discount_type'] === 'percentage') {
        $discount = ($subtotal * floatval($promo['discount_value'])) / 100;
        $maxDiscount = isset($promo['max_discount']) ? floatval($promo['max_discount']) : 0;
        if ($maxDiscount > 0 && $discount > $maxDiscount) {
            $discount = $maxDiscount;
        }
    } elseif ($promo['discount_type'] === 'fixed') {
        $discount = floatval($promo['discount_value']);
        if ($discount > $subtotal) {
            $discount = $subtotal; // Don’t exceed subtotal
        }
    }

    $finalTotal = $subtotal - $discount;

    // ⚠️ Ideally, update usage_count only after order completion
    $updateSql = "UPDATE promo_codes SET usage_count = usage_count + 1 WHERE id = ?";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([$promo['id']]);

    // Success response
    echo json_encode([
        'success'    => true,
        'discount'   => $discount,
        'finalTotal' => $finalTotal,
        'message'    => 'Promo code applied successfully'
    ]);

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>

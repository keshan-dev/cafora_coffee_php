<?php
$requiredRole = "user";   // only users can view this
require '../includes/auth.php';
require '../includes/database_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/cafora_coffee_php/assets/css/font.css">
    <link rel="stylesheet" href="/cafora_coffee_php/assets/css/cart.css">
</head>
<body>
    <div class="container">
        <div class="cart-header">
            <h1 class="cart-title fs-43">Your Shopping Cart</h1>
            <p class="cart-subtitle">Review your items and proceed to checkout.</p>
        </div>

        <div class="cart-content">
            
            <!-- Cart Items Section -->
            <div class="cart-items-section">
                <div id="cartItems" class="cart-items">
                    <div class="loading">Loading your cart...</div>
                </div>
                
                <div class="cart-actions">
                    <button class="continue-shopping-btn base-font" onclick="goBack()">
                        <span>←</span> Continue Shopping
                    </button>
                </div>
            </div>

             <!-- Order Summary Section -->
            <div class="order-summary">
                <h2 class="summary-title fs-24">Order Summary</h2>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal">$0.00</span>
                </div>
                
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="shipping">$5.00</span>
                </div>
                
                <div class="summary-row">
                    <span>Taxes</span>
                    <span id="taxes">$0.00</span>
                </div>
                
                <div class="summary-divider"></div>
                
                <div class="summary-row total">
                    <span class="fs-24">Total</span>
                    <span class="fs-24" id="total">$0.00</span>
                </div>
                
                <div class="promo-section">
                    <label class="promo-label">Have a promo code?</label>
                    <div class="promo-input-group">
                        <input type="text" id="promoCode" placeholder="Enter code" class="promo-input">
                        <button class="apply-btn base-font" onclick="applyPromo()">Apply</button>
                    </div>
                </div>
                
                <button class="checkout-btn fs-24" onclick="proceedToCheckout()">
                    Proceed to Checkout
                </button>
            </div>
        </div>

        <!-- Recent Orders Section -->
    <div class="recent-orders-section">
        <h2 class="summary-title fs-24">Recent Orders</h2>
            <div id="recentOrders" class="recent-orders">
            <!-- Recent orders will be dynamically loaded here via cart.js -->
            <p>Loading recent orders...</p>
            </div>
    </div>
        <!-- Empty Cart Message -->
        <div id="emptyCart" class="empty-cart" style="display: none;">
            <h2 class="fs-32">Your cart is empty</h2>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <button class="continue-shopping-btn base-font" onclick="goBack()">
                Continue Shopping
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-bottom">
            &copy; 2025 Caf̄ora Co. All Rights Reserved.
        </div>
    </footer>

    <script src="/cafora_coffee_php/assets/js/cart.js"></script>
    
</body>
</html>
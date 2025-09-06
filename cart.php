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
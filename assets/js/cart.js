// Global variables
let cartItems = [];
let subtotal = 0;
let shipping = 5.00;
let taxRate = 0.08; // 8% tax rate
let promoDiscount = 0;

// Initialize cart page
document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
});

// Load cart items from PHP backend
async function loadCartItems() {
    try {
        const response = await fetch('/cafora_coffee_php/includes/get_cart.php');
        
        if (!response.ok) {
            throw new Error('Failed to fetch cart items');
        }
        
        const data = await response.json();
        
        if (data.success) {
            cartItems = data.items;
            renderCartItems();
            calculateTotals();
        } else {
            showError(data.message || 'Failed to load cart items');
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        showError('Unable to load cart. Please try again later.');
    }
}

// Render cart items
function renderCartItems() {
    const cartContainer = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');
    const cartContent = document.querySelector('.cart-content');
    
    if (cartItems.length === 0) {
        cartContent.style.display = 'none';
        emptyCart.style.display = 'block';
        return;
    }
    
    cartContent.style.display = 'grid';
    emptyCart.style.display = 'none';
    
    cartContainer.innerHTML = cartItems.map(item => `
        <div class="cart-item" data-item-id="${item.id}">
            <img src="${item.image || 'https://via.placeholder.com/80x80?text=No+Image'}" 
                 alt="${item.name}" 
                 class="item-image"
                 onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'">
            
            <div class="item-details">
                <h3 class="item-name fs-24">${escapeHtml(item.name)}</h3>
                <p class="item-description">${escapeHtml(item.description || item.category)}</p>
            </div>
            
            <div class="item-price fs-24">$${parseFloat(item.price).toFixed(2)}</div>
            
            <div class="quantity-controls">
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">−</button>
                <input type="number" 
                       class="quantity-input" 
                       value="${item.quantity}" 
                       min="1" 
                       onchange="updateQuantity(${item.id}, this.value)">
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
            </div>
            
            <button class="remove-btn" onclick="removeItem(${item.id})" title="Remove item">
                🗑️
            </button>
        </div>
    `).join('');
}

// Update item quantity
async function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) {
        removeItem(itemId);
        return;
    }
    
    try {
        const response = await fetch('/cafora_coffee_php/includes/update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                itemId: itemId,
                quantity: parseInt(newQuantity)
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update local cart items
            const itemIndex = cartItems.findIndex(item => item.id == itemId);
            if (itemIndex !== -1) {
                cartItems[itemIndex].quantity = parseInt(newQuantity);
                calculateTotals();
            }
        } else {
            alert('Failed to update quantity. Please try again.');
            // Reload cart to reset values
            loadCartItems();
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
        alert('Error updating quantity. Please try again.');
        loadCartItems();
    }
}

// Remove item from cart
async function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }
    
    try {
        const response = await fetch('/cafora_coffee_php/includes/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                itemId: itemId
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Remove item from local array
            cartItems = cartItems.filter(item => item.id != itemId);
            renderCartItems();
            calculateTotals();
        } else {
            alert('Failed to remove item. Please try again.');
        }
    } catch (error) {
        console.error('Error removing item:', error);
        alert('Error removing item. Please try again.');
    }
}

// Calculate totals
function calculateTotals() {
    subtotal = cartItems.reduce((total, item) => {
        return total + (parseFloat(item.price) * parseInt(item.quantity));
    }, 0);
    
    const taxes = subtotal * taxRate;
    const total = subtotal + shipping + taxes - promoDiscount;
    
    // Update DOM
    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('taxes').textContent = `$${taxes.toFixed(2)}`;
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
}

// Apply promo code
async function applyPromo() {
    const promoCode = document.getElementById('promoCode').value.trim();
    
    if (!promoCode) {
        alert('Please enter a promo code');
        return;
    }
    
    try {
        const response = await fetch('/cafora_coffee_php/includes/apply_promo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                promoCode: promoCode,
                subtotal: subtotal
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            promoDiscount = result.discount;
            alert(`Promo code applied! You saved $${promoDiscount.toFixed(2)}`);
            
            // Add discount row to summary
            const summaryContainer = document.querySelector('.order-summary');
            const existingDiscount = summaryContainer.querySelector('.discount-row');
            if (existingDiscount) {
                existingDiscount.remove();
            }
            
            const discountRow = document.createElement('div');
            discountRow.className = 'summary-row discount-row';
            discountRow.innerHTML = `
                <span>Discount (${promoCode})</span>
                <span style="color: #4CAF50;">-$${promoDiscount.toFixed(2)}</span>
            `;
            
            const divider = summaryContainer.querySelector('.summary-divider');
            summaryContainer.insertBefore(discountRow, divider);
            
            calculateTotals();
        } else {
            alert(result.message || 'Invalid promo code');
        }
    } catch (error) {
        console.error('Error applying promo:', error);
        alert('Error applying promo code. Please try again.');
    }
}

// Go back to products page
function goBack() {
    window.location.href = '/cafora_coffee_php/product.php';
}

// Proceed to checkout
function proceedToCheckout() {
    if (cartItems.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    // You can redirect to checkout page or implement checkout logic
    // For now, let's just show an alert
    const total = subtotal + shipping + (subtotal * taxRate) - promoDiscount;
    
    if (confirm(`Proceed to checkout?\nTotal: $${total.toFixed(2)}`)) {
        // Redirect to checkout page
        window.location.href = 'checkout.html';
        
        //implement checkout logic here
        // processCheckout();
    }
}

// Process checkout (optional - for future implementation)
async function processCheckout() {
    try {
        const response = await fetch('process_checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                items: cartItems,
                subtotal: subtotal,
                shipping: shipping,
                taxes: subtotal * taxRate,
                discount: promoDiscount,
                total: subtotal + shipping + (subtotal * taxRate) - promoDiscount
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Order placed successfully!');
            // Redirect to success page
            window.location.href = 'order-success.html';
        } else {
            alert('Checkout failed. Please try again.');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        alert('Checkout failed. Please try again.');
    }
}

// Show error message
function showError(message) {
    const cartContainer = document.getElementById('cartItems');
    cartContainer.innerHTML = `<div class="error">${escapeHtml(message)}</div>`;
}

// Escape HTML to prevent XSS attacks
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Auto-save cart changes (optional feature)
function autoSave() {
    // This function can be called periodically to save cart state
    //persistent cart across sessions
}
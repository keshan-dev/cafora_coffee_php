// ===========================
// Global Variables
// ===========================
let cartItems = [];
let subtotal = 0;
let shipping = 5.00;
let taxRate = 0.08; // 8% tax rate
let promoDiscount = 0;

// ===========================
// Initialize Cart Page
// ===========================
document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    loadRecentOrders();
});

// ===========================
// Load Cart Items
// ===========================
async function loadCartItems() {
    try {
        const response = await fetch('/cafora_coffee_php/includes/get_cart.php');
        const data = await response.json();

        if (data.success) {
            cartItems = data.items;
            renderCartItems();
            calculateTotals();
        } else {
            showError(data.message || 'Failed to load cart items');
        }
    } catch (err) {
        console.error(err);
        showError('Unable to load cart. Please try again later.');
    }
}

// ===========================
// Render Cart Items
// ===========================
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
                <input type="number" class="quantity-input" value="${item.quantity}" min="1" onchange="updateQuantity(${item.id}, this.value)">
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
            </div>
            <button class="remove-btn" onclick="removeItem(${item.id})" title="Remove item">🗑️</button>
        </div>
    `).join('');
}

// ===========================
// Update Item Quantity
// ===========================
async function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) {
        removeItem(itemId);
        return;
    }

    try {
        const res = await fetch('/cafora_coffee_php/includes/update_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ itemId: itemId, quantity: parseInt(newQuantity) })
        });
        const data = await res.json();

        if (data.success) {
            const idx = cartItems.findIndex(i => i.id == itemId);
            if (idx !== -1) {
                cartItems[idx].quantity = parseInt(newQuantity);
                renderCartItems();
                calculateTotals();
            }
        } else {
            alert('Failed to update quantity. Reloading cart...');
            loadCartItems();
        }
    } catch(err) {
        console.error(err);
        alert('Error updating quantity. Reloading cart...');
        loadCartItems();
    }
}

// ===========================
// Remove Item
// ===========================
async function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) return;

    try {
        const res = await fetch('/cafora_coffee_php/includes/remove_from_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ itemId: itemId })
        });
        const data = await res.json();

        if (data.success) {
            cartItems = cartItems.filter(i => i.id != itemId);
            renderCartItems();
            calculateTotals();
        } else {
            alert('Failed to remove item.');
        }
    } catch(err) {
        console.error(err);
        alert('Error removing item.');
    }
}

// ===========================
// Calculate Totals
// ===========================
function calculateTotals() {
    subtotal = cartItems.reduce((sum, i) => sum + (parseFloat(i.price) * parseInt(i.quantity)), 0);
    const taxes = subtotal * taxRate;
    const total = subtotal + shipping + taxes - promoDiscount;

    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('taxes').textContent = `$${taxes.toFixed(2)}`;
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
}

// ===========================
// Apply Promo Code
// ===========================
async function applyPromo() {
    const code = document.getElementById('promoCode').value.trim();
    if (!code) { alert('Enter promo code'); return; }

    try {
        const res = await fetch('/cafora_coffee_php/includes/apply_promo.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({ promoCode: code, subtotal: subtotal })
        });
        const data = await res.json();

        if (data.success) {
            promoDiscount = data.discount;
            alert(`Promo applied! You saved $${promoDiscount.toFixed(2)}`);
            calculateTotals();
        } else {
            alert(data.message || 'Invalid promo code');
        }
    } catch(err) {
        console.error(err);
        alert('Error applying promo code.');
    }
}

// ===========================
// Go Back to Products
// ===========================
function goBack() {
    window.location.href = '/cafora_coffee_php/product.php';
}

// ===========================
// Proceed to Checkout
// ===========================
async function proceedToCheckout() {
    if (cartItems.length === 0) { alert('Cart is empty'); return; }

    const totalAmount = subtotal + shipping + subtotal*taxRate - promoDiscount;
    if (!confirm(`Proceed to checkout?\nTotal: $${totalAmount.toFixed(2)}`)) return;

    try {
        const res = await fetch('/cafora_coffee_php/users/checkout.php', { method:'POST' });
        const data = await res.json();

        if (data.success) {
            alert(`Order placed successfully! Order ID: ${data.order_id}`);
            cartItems = [];
            renderCartItems();
            calculateTotals();
            loadRecentOrders();
        } else {
            alert(data.message || 'Checkout failed');
        }
    } catch(err) {
        console.error(err);
        alert('Checkout failed. Try again.');
    }
}

// ===========================
// Load Recent Orders
// ===========================
async function loadRecentOrders() {
    try {
        const res = await fetch('/cafora_coffee_php/users/recent_orders.php');
        const orders = await res.json();

        const container = document.getElementById('recentOrders');
        if (!orders || orders.length === 0) {
            container.innerHTML = "<p>No recent orders.</p>";
            return;
        }

        container.innerHTML = orders.map(order => `
            <div class="order-card">
                <h3>Order #${order.order_id} - ${order.status}</h3>
                <ul>
                    ${order.items.map(i=>`<li>${i.name} x ${i.quantity} - $${i.price}</li>`).join('')}
                </ul>
                <p>Total: $${order.total}</p>
                <p>Placed on: ${order.created_at}</p>
            </div>
        `).join('');
    } catch(err) {
        console.error(err);
    }
}

// ===========================
// Show Error
// ===========================
function showError(msg) {
    document.getElementById('cartItems').innerHTML = `<div class="error">${escapeHtml(msg)}</div>`;
}

// ===========================
// Escape HTML
// ===========================
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

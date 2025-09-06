// Global variables
let allProducts = [];
let currentCategory = 'all';
let displayedProducts = 4;

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    setupEventListeners();
});

// Load products from PHP backend
async function loadProducts() {
    try {
        const response = await fetch('/test2/getproducts.php');
        
        if (!response.ok) {
            throw new Error('Failed to fetch products');
        }
        
        const data = await response.json();
        
        if (data.success) {
            allProducts = data.products;
            renderProducts();
        } else {
            showError(data.message || 'Failed to load products');
        }
    } catch (error) {
        console.error('Error loading products:', error);
        showError('Unable to load products. Please try again later.');
    }
}

// Render products based on current filters
function renderProducts() {
    const grid = document.getElementById('productsGrid');
    
    // Filter products based on current category
    const filteredProducts = currentCategory === 'all' 
        ? allProducts 
        : allProducts.filter(product => product.category.toLowerCase() === currentCategory.toLowerCase());

    // Get products to display
    const productsToShow = filteredProducts.slice(0, displayedProducts);
    
    if (productsToShow.length === 0) {
        grid.innerHTML = '<div class="error">No products found in this category.</div>';
        document.getElementById('showMoreBtn').style.display = 'none';
        return;
    }
    
    // Generate product cards HTML
    grid.innerHTML = productsToShow.map(product => `
        <div class="product-card">
            <div class="product-image-container">
                <img src="${product.image || 'https://via.placeholder.com/400x250?text=No+Image'}" 
                     alt="${product.name}" 
                     class="product-image"
                     onerror="this.src='https://via.placeholder.com/400x250?text=No+Image'">
                ${product.badge ? `<div class="product-badge">${product.badge}</div>` : ''}
            </div>
            <div class="product-info">
                <h3 class="product-name fs-24">${escapeHtml(product.name)}</h3>
                <p class="product-description">${escapeHtml(product.description)}</p>
                <div class="product-bottom">
                    <span class="product-price fs-24">$${parseFloat(product.price).toFixed(2)}</span>
                    <button class="add-btn base-font" onclick="addToCart(${product.id})">
                        <span>+</span> Add
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    // Show/hide "Show More" button
    updateShowMoreButton(filteredProducts.length);
}

// Update show more button visibility
function updateShowMoreButton(totalProducts) {
    const showMoreBtn = document.getElementById('showMoreBtn');
    if (displayedProducts >= totalProducts) {
        showMoreBtn.style.display = 'none';
    } else {
        showMoreBtn.style.display = 'block';
    }
}

// Add product to cart
async function addToCart(productId) {
    const product = allProducts.find(p => p.id == productId);
    if (product) {
        try {
            // Send product to cart via PHP
            const response = await fetch('/test2/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    productId: productId, 
                    quantity: 1,
                    name: product.name,
                    price: product.price,
                    image: product.image
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Redirect to cart page
                window.location.href = '/test2/cart.php';
            } else {
                alert('Failed to add product to cart. Please try again.');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            alert('Error adding product to cart. Please try again.');
        }
    }
}

// Show more products
function showMore() {
    displayedProducts += 4;
    renderProducts();
}

// Show error message
function showError(message) {
    const grid = document.getElementById('productsGrid');
    grid.innerHTML = `<div class="error">${escapeHtml(message)}</div>`;
    document.getElementById('showMoreBtn').style.display = 'none';
}

// Escape HTML to prevent XSS attacks
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Setup event listeners
function setupEventListeners() {
    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            
            // Update category and reset displayed products
            currentCategory = e.target.dataset.category;
            displayedProducts = 4;
            
            renderProducts();
        });
    });

    // Show more button
    document.getElementById('showMoreBtn').addEventListener('click', showMore);
}

// Utility function to refresh products (can be called externally)
function refreshProducts() {
    displayedProducts = 4;
    currentCategory = 'all';
    document.querySelector('.filter-btn[data-category="all"]').classList.add('active');
    document.querySelectorAll('.filter-btn:not([data-category="all"])').forEach(btn => {
        btn.classList.remove('active');
    });
    loadProducts();
}
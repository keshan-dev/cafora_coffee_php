<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caf̄ora Coffee Shop</title>
    
    <link rel="stylesheet" href="/cafora_coffee_php/assets/css/font.css">
    <link rel="stylesheet" href="/cafora_coffee_php/assets/css/index.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    

</head>

<body>

    <main>
          <!-- Import navbar -->
           <?php include 'includes/navbar.php'; ?>

        <section class="hero">
            <div class="hero-content">
                <p class="subtitle base-font">WELCOME</p>

                <h1 class="fs-76">Unforgettable Coffee Simply Made!</h1>
                
                <p class="description">
                    From bold espressos to creamy lattes and fresh pastries, find your new favorite treat.
                </p>

                <a href="/cafora_coffee_php/product.php" class="cta-button base-font">Order Now</a>
            </div>
        </section>
    </main>

    <section class="bestsellers-section">
        <div class="container">
            <div class="section-header">
                <h2 class="fs-43">Our Bestsellers</h2>
                <p class="section-subtitle">Discover our most loved coffee blends and single-origin beans.</p>
            </div>
            
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image espresso-blend">
                        <h3 class="fs-32 product-title">Espresso Blend</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Classic Espresso Blend</h4>
                        <p class="product-description">Rich, dark, and intense with notes of chocolate and nuts.</p>
                        <div class="product-footer">
                            <span class="price">$18.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image single-origin">
                        <h3 class="fs-32 product-title">Single Origin</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Ethiopian Single Origin</h4>
                        <p class="product-description">Bright and fruity with floral notes. A truly unique experience.</p>
                        <div class="product-footer">
                            <span class="price">$22.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image iced-latte">
                        <h3 class="fs-32 product-title">Iced Latte</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Cascading Iced Latte</h4>
                        <p class="product-description">Bright and fruity with floral notes. A truly unique experience.</p>
                        <div class="product-footer">
                            <span class="price">$22.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image Americano">
                        <h3 class="fs-32 product-title">Americano</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Americano Coffee</h4>
                        <p class="product-description">Bold and smooth with rich, roasted notes. A classic coffee experience.</p>
                        <div class="product-footer">
                            <span class="price">$16.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bestsellers-section">
        <div class="container">
            <div class="section-header">
                <h2 class="fs-43">Our Signature Desserts</h2>
                <p class="section-subtitle">Discover our most loved cakes and timeless treats.</p>
            </div>
            
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image caramel-torte">
                        <h3 class="fs-32 product-title">Caramel torte</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Golden Caramel Torte</h4>
                        <p class="product-description">Layers of caramel cream with a crispy crunch in every bite.</p>
                        <div class="product-footer">
                            <span class="price">$10.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image tropical-dream">
                        <h3 class="fs-32 product-title">Tropical  Dream</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Tropical Coconut Dream</h4>
                        <p class="product-description">Moist coconut layers with a creamy coconut frosting. A true island treat.</p>
                        <div class="product-footer">
                            <span class="price">$12.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image cheesecake">
                        <h3 class="fs-32 product-title">Cheesecake</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Berry Bliss Cheesecake</h4>
                        <p class="product-description">Creamy cheesecake with fresh berries for a refreshing treat.</p>
                        <div class="product-footer">
                            <span class="price">$15.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image Coffee-ice-cream">
                        <h3 class="fs-32 product-title">Coffee Ice Cream</h3>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">Coffee Ice Cream</h4>
                        <p class="product-description">Rich and creamy with a bold coffee kick. A smooth indulgence for true coffee lovers.</p>
                        <div class="product-footer">
                            <span class="price">$18.00</span>
                            <button class="add-to-cart-btn" onclick="window.location.href='/cafora_coffee_php/product.php#shop';">Shop Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="interior-moments-section">
        <div class="container">
            <div class="section-header">
                <h2 class="fs-43">Our Interior Moments</h2>
                <p class="section-subtitle">Experience the warm and inviting atmosphere of our coffee shop, where every corner tells a story of comfort and community.</p>
            </div>
            
            <div class="moments-grid">
                <div class="moment-card large-card">
                    <div class="moment-image cozy-corner">
                        <div class="image-overlay">
                            <h3 class="fs-32 moment-title">Cozy Corner</h3>
                            <p class="moment-description">Perfect spot for intimate conversations</p>
                        </div>
                    </div>
                </div>

                <div class="moment-card">
                    <div class="moment-image barista-station">
                        <div class="image-overlay">
                            <h3 class="fs-24 moment-title">Barista's Craft</h3>
                            <p class="moment-description">Artistry in every cup</p>
                        </div>
                    </div>
                </div>

                <div class="moment-card">
                    <div class="moment-image reading-nook">
                        <div class="image-overlay">
                            <h3 class="fs-24 moment-title">Reading Nook</h3>
                            <p class="moment-description">Your quiet escape</p>
                        </div>
                    </div>
                </div>

                <div class="moment-card">
                    <div class="moment-image communal-table">
                        <div class="image-overlay">
                            <h3 class="fs-24 moment-title">Community Table</h3>
                            <p class="moment-description">Where stories are shared</p>
                        </div>
                    </div>
                </div>

                <div class="moment-card">
                    <div class="moment-image window-view">
                        <div class="image-overlay">
                            <h3 class="fs-24 moment-title">Window Views</h3>
                            <p class="moment-description">Natural light and inspiration</p>
                        </div>
                    </div>
                </div>

                <div class="moment-card large-card">
                    <div class="moment-image cafe-atmosphere">
                        <div class="image-overlay">
                            <h3 class="fs-32 moment-title">Café Atmosphere</h3>
                            <p class="moment-description">The heart of our community</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="why-choose-section">
        <div class="container">
            <div class="section-header">
                <h2 class="fs-43">Why Choose Caf̄ora?</h2>
                <p class="section-subtitle">We're more than just coffee.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <div class="icon-wrapper">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" stroke="white" stroke-width="2"/>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9 9h.01M15 9h.01" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                <path d="M12 2C8.5 2 6 4.5 6 8c0 2 1 3 2 4l4 8 4-8c1-1 2-2 2-4 0-3.5-2.5-6-6-6z" fill="white"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="fs-24 feature-title">Premium Quality</h3>
                    <p class="feature-description">Only the best, ethically sourced beans from around the make it into our bags.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <div class="icon-wrapper">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 18V6a2 2 0 0 0-2-2c-1.1 0-2 .9-2 2v12" stroke="white" stroke-width="2"/>
                                <path d="M15 18H9" stroke="white" stroke-width="2"/>
                                <path d="M19 18h2l-3-3V9l-2-2h-4l-2 2v6l-3 3h2" stroke="white" stroke-width="2"/>
                                <rect x="2" y="18" width="20" height="2" fill="white"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="fs-24 feature-title">Fast Shipping</h3>
                    <p class="feature-description">We roast to order and ship within 48 hours to ensure maximum freshness.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <div class="icon-wrapper">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 8h1a4 4 0 1 1 0 8h-1" stroke="white" stroke-width="2"/>
                                <path d="M6.5 8H6a4 4 0 0 0 0 8h.5" stroke="white" stroke-width="2"/>
                                <path d="M6.5 8a5.5 5.5 0 0 1 11 0v3a5 5 0 0 1-5 5H10a3.5 3.5 0 0 1-3.5-3.5V8z" stroke="white" stroke-width="2"/>
                                <path d="M9 9v6" stroke="white" stroke-width="1.5"/>
                                <path d="M15 9v6" stroke="white" stroke-width="1.5"/>
                                <circle cx="9" cy="6" r="1" fill="white"/>
                                <circle cx="15" cy="6" r="1" fill="white"/>
                                <circle cx="12" cy="4" r="1" fill="white"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="fs-24 feature-title">Expertly Roasted</h3>
                    <p class="feature-description">Our master roasters craft each batch to bring out the unique flavors of every bean.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Import footer -->
           <?php include 'includes/footer.php'; ?>
       
</body>
</html>
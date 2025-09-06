<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Curated Coffee Selection</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="font.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

     <section class="hero-section">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="images/coffee.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="hero-content">
            <h1>Shop Our Menu</h1>
            <p>Discover amazing things happening here.</p>
            <a href="#shop" class="cta-button">Shop Now</a>
        </div>
    </section>


    <div class="container">
        <div class="section-header">
            <h1 class="section-title fs-43">Our Curated Selection</h1>
            <p class="section-description">Discover exotic flavors and rich aromas from the world's finest coffee-growing regions, refreshing beverages, and delightful desserts. Each product tells a story.</p>
        </div>
        <section id="shop">
        <div class="filter-container">
            <button class="filter-btn active base-font" data-category="all">All</button>
            <button class="filter-btn base-font" data-category="coffee">Coffee</button>
            <button class="filter-btn base-font" data-category="desserts">Desserts</button>
            <button class="filter-btn base-font" data-category="soft drinks">Soft Drinks</button>
        </div>

        <div class="products-grid" id="productsGrid">
            <div class="loading">Loading products...</div>
        </div>

        <button class="show-more-btn base-font" id="showMoreBtn" style="display: none;">Show More Products</button>
        </section>
    </div>

</body>
</html>

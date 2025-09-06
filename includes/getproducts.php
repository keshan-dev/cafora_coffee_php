<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$servername = "localhost";
$username = "root";    // Replace with your database username
$password = "";    // Replace with your database password
$dbname = "products";      // Replace with your database name

try {
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL query to fetch products
    // Adjust the table name and column names according to your database structure
    $sql = "SELECT 
                id, 
                name, 
                description, 
                price, 
                category, 
                image_url as image,
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
        'success' => true,
        'products' => $products,
        'count' => count($products)
    ]);
    
} catch(PDOException $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>

<?php
/*
DATABASE SETUP INSTRUCTIONS:

1. Create a database table with this structure:

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

2. Insert sample data:

INSERT INTO `products` (`name`, `description`, `price`, `category`, `image_url`, `badge`) VALUES
('Ethiopian Yirgacheffe', 'Bright, floral notes of bergamot, jasmine, and hint of blueberry. Light roast.', 22.50, 'coffee', 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=250&fit=crop', 'Best Seller'),
('Colombian Supremo', 'A balanced cup with notes of caramel, nutty undertones, and a smooth, chocolatey finish.', 19.00, 'coffee', 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=400&h=250&fit=crop', NULL),
('Sumatra Mandheling', 'Earthy and complex with a heavy body, featuring notes of dark chocolate, cedar, and spice.', 24.00, 'coffee', 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=250&fit=crop', 'Organic'),
('Golden Bean Espresso Blend', 'Our signature blend. Rich crema, notes of roasted almond, cocoa, and a hint of sweet vanilla.', 21.00, 'coffee', 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=400&h=250&fit=crop', NULL),
('Chocolate Lava Cake', 'Decadent molten chocolate cake with a gooey center, served warm with vanilla ice cream.', 8.50, 'desserts', 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=400&h=250&fit=crop', NULL),
('Classic Tiramisu', 'Traditional Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cream.', 7.00, 'desserts', 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=400&h=250&fit=crop', NULL),
('Artisan Cheesecake', 'Creamy New York style cheesecake with seasonal berry compote and graham cracker crust.', 6.50, 'desserts', 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=400&h=250&fit=crop', NULL),
('Sparkling Lemonade', 'Refreshing house-made lemonade with fresh mint leaves and sparkling water.', 4.50, 'soft drinks', 'https://images.unsplash.com/photo-1523371683702-0084537b60e7?w=400&h=250&fit=crop', NULL),
('Tropical Fruit Smoothie', 'Blend of mango, pineapple, and passion fruit with coconut milk and fresh lime.', 5.75, 'soft drinks', 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=400&h=250&fit=crop', NULL),
('Iced Green Tea', 'Premium sencha green tea served over ice with honey and fresh lemon slices.', 3.25, 'soft drinks', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=250&fit=crop', 'Organic');

3. Update the database configuration at the top of this file:
   - $servername: Your database server (usually 'localhost')
   - $username: Your database username
   - $password: Your database password  
   - $dbname: Your database name

4. Make sure your web server has PHP and MySQL/MariaDB installed and running.

5. Place all files in your web server directory (htdocs for XAMPP, www for WAMP, etc.)

COLUMN MAPPING:
- id: Product unique identifier
- name: Product name
- description: Product description  
- price: Product price (decimal)
- category: Product category (coffee, desserts, soft drinks)
- image_url: URL to product image
- badge: Optional badge text (Best Seller, Organic, etc.)
- active: Whether product is active (1 = active, 0 = inactive)
- created_at: When product was created

*/
?>
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 15, 2025 at 06:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffee_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `session_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(97, 'o2lc7409ar41vds8nmbfru22tq', 29, 1, '2025-09-14 21:08:00', '2025-09-14 21:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled','processing') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total`, `status`, `created_at`, `updated_at`) VALUES
(16, 1249, 20.12, 'cancelled', '2025-09-13 10:13:33', '2025-09-14 20:06:31'),
(17, 1249, 20.12, 'pending', '2025-09-14 20:55:41', NULL),
(18, 1249, 20.12, 'pending', '2025-09-14 21:27:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `item_id`, `quantity`, `price`) VALUES
(16, 26, 1, 14.00),
(17, 26, 1, 14.00),
(18, 26, 1, 14.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image_url`, `badge`, `active`, `created_at`) VALUES
(6, 'Classic Espresso Blend', 'Rich, dark, and intense with notes of chocolate and nuts.', 14.00, 'coffee', '/cafora_coffee_php/assets/images/products/Classic-presso-Blend.jpeg', 'best', 1, '2025-09-05 15:54:08'),
(7, 'Ethiopian Single Orgin', 'Bright and fruity with floral notes. A truly unique experience', 13.00, 'coffee', '/cafora_coffee_php/assets/images/products/Ethiopian-Single-Orgin.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(8, 'Velvet Mocha', 'Decadent chocolate flavor with a creamy, velvety finish.', 15.00, 'coffee', '/cafora_coffee_php/assets/images/products/Velvet-Mocha.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(9, 'Vanilla Latte', 'Classic espresso with creamy milk and a touch of vanilla.', 18.00, 'coffee', '/cafora_coffee_php/assets/images/products/Vanilla-Latte.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(10, 'Cappuccino Classic', 'Perfectly frothed milk over bold espresso for a rich taste.', 17.00, 'coffee', '/cafora_coffee_php/assets/images/products/Cappuccino-Classic.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(11, 'Hot Latte', 'Creamy espresso with steamed milk and a thin layer of foam.', 14.00, 'coffee', '/cafora_coffee_php/assets/images/products/Hot-Latte.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(12, 'Macchiato', 'Espresso “stained” with a small dollop of milk foam.', 16.00, 'coffee', '/cafora_coffee_php/assets/images/products/Macchiato.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(13, 'Flat White', 'Smooth espresso with velvety microfoam.', 13.00, 'coffee', '/cafora_coffee_php/assets/images/products/Flat-White.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(14, 'Chai Latte', 'Smooth espresso with velvety microfoam.', 11.00, 'coffee', '/cafora_coffee_php/assets/images/products/Chai-Latte.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(15, 'Hazelnut Latte', 'Nutty flavor combined with espresso and steamed milk.', 19.00, 'coffee', '/cafora_coffee_php/assets/images/products/Hazelnut-Latte.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(16, 'Espresso', 'Strong, bold, and concentrated coffee shot.', 20.00, 'coffee', '/cafora_coffee_php/assets/images/products/Espresso.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(17, 'Chocolate Mint Dessert', 'Layers of rich chocolate and refreshing mint, served in a delightful jar.', 17.00, 'desserts', '/cafora_coffee_php/assets/images/products/Chocolate-Mint-Dessert.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(18, 'Chocolate Mousse', 'Light and creamy chocolate mousse in a jar', 20.00, 'desserts', '/cafora_coffee_php/assets/images/products/Chocolate-Mousse.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(19, 'Creme Brule', 'Smooth custard topped with caramelized sugar in a jar.', 22.00, 'desserts', '/cafora_coffee_php/assets/images/products/Creme-Brule.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(20, 'Red Velvet', 'Moist red velvet cake layered with creamy frosting in a jar.', 15.00, 'desserts', '/cafora_coffee_php/assets/images/products/Red-Velvet.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(21, 'Honey Baklava', 'Moist red velvet cake layered with creamy frosting in a jar.', 16.00, 'desserts', '/cafora_coffee_php/assets/images/products/Honey-Baklava.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(22, 'Sparkling Citrus Cooler', 'Zesty citrus flavors with a bubbly, refreshing finish.', 15.00, 'soft drinks', '/cafora_coffee_php/assets/images/products/Sparkling-Citrus-Cooler.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(24, 'Berry Fizz', 'Mixed berries blended with sparkling water for a fruity delight.', 18.00, 'soft drinks', '/cafora_coffee_php/assets/images/products/Berry-Fizz.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(25, 'Cucumber Mint Refresher', 'Cool cucumber and fresh mint for a light, crisp drink.', 13.00, 'soft drinks', '/cafora_coffee_php/assets/images/products/Cucumber-Mint-Refresher.jpeg', NULL, 1, '2025-09-05 15:54:08'),
(26, 'Hot Coffee Bliss', 'Chilled and refreshing with a smooth coffee kick.', 14.00, 'Coffee', '/cafora_coffee_php/assets/images/products/Hot-coffee-Bliss.jpeg', 'new', 1, '2025-09-05 16:12:33'),
(27, 'Cream for Oreo', 'Layers of creamy delight with crunchy Oreo in a jar.', 17.00, 'desserts', '/cafora_coffee_php/assets/images/products/Cream-for-Oreo.jpeg', NULL, 1, '2025-09-05 16:12:33'),
(28, 'Tropical Punch', 'A vibrant mix of tropical fruits for a sweet, refreshing sip.', 17.00, 'soft drinks', '/cafora_coffee_php/assets/images/products/Tropical-Punch.jpeg', NULL, 1, '2025-09-05 16:12:33'),
(29, 'Mint Lemonade', 'Classic lemonade with a refreshing hint of mint.', 17.00, 'soft drinks', '/cafora_coffee_php/assets/images/products/Mint-Lemonade.jpeg', NULL, 1, '2025-09-05 16:12:33'),
(30, 'Peach Iced Tea', 'Sweet peach flavors combined with chilled black tea.', 15.00, 'soft drinks', '/cafora_coffee_php/assets/images/products/Peach-Iced-Tea.jpeg', NULL, 1, '2025-09-05 16:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `minimum_amount` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `code`, `discount_type`, `discount_value`, `minimum_amount`, `max_discount`, `usage_limit`, `usage_count`, `active`, `expires_at`, `created_at`) VALUES
(1, 'SAVE10', 'percentage', 10.00, 10.00, 500.00, 100, 13, 1, '2025-12-31 23:59:59', '2025-09-06 22:14:31'),
(2, 'FLAT200', 'fixed', 200.00, NULL, NULL, 50, 4, 1, '2025-12-31 23:59:59', '2025-09-06 22:14:31'),
(3, 'HALFPRICE', 'percentage', 50.00, 2000.00, 1000.00, 20, 0, 1, '2025-09-30 23:59:59', '2025-09-06 22:14:31'),
(4, 'WELCOME100', 'fixed', 100.00, 500.00, NULL, 10, 0, 1, '2025-10-31 23:59:59', '2025-09-06 22:14:31'),
(5, 'FREESHIP', 'fixed', 50.00, NULL, NULL, NULL, 0, 1, NULL, '2025-09-06 22:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `address`, `phone`, `status`, `created_at`) VALUES
(1, 'Central Perk', '123 Main Street, Kurunegala', '037-1234567', 'active', '2025-09-06 23:07:34'),
(2, 'Bean & Brew', '45 Coffee Lane, Colombo', '011-7654321', 'active', '2025-09-06 23:07:34'),
(3, 'Mocha Magic', '78 Espresso Road, Kandy', '081-9876543', 'active', '2025-09-06 23:07:34'),
(4, 'Latte Lounge', '22 Cappuccino Ave, Galle', '091-2345678', 'inactive', '2025-09-06 23:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `store_items`
--

CREATE TABLE `store_items` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `issued` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_items`
--

INSERT INTO `store_items` (`id`, `store_id`, `name`, `description`, `price`, `stock`, `issued`, `status`, `created_at`) VALUES
(23, 1, 'Cappuccino', 'Rich espresso with steamed milk foam', 3.50, 13, 8, 'active', '2025-09-06 23:07:49'),
(24, 1, 'Espresso', 'Strong single shot of espresso', 2.50, 30, 5, 'active', '2025-09-06 23:07:49'),
(25, 1, 'Blueberry Muffin', 'Freshly baked muffin with blueberries', 2.00, 15, 0, 'active', '2025-09-06 23:07:49'),
(26, 2, 'Latte', 'Espresso with creamy steamed milk', 3.75, 25, 0, 'active', '2025-09-06 23:07:49'),
(27, 2, 'Caramel Macchiato', 'Latte with caramel drizzle', 4.25, 20, 0, 'active', '2025-09-06 23:07:49'),
(28, 2, 'Chocolate Brownie', 'Rich chocolate brownie', 2.50, 10, 0, 'active', '2025-09-06 23:07:49'),
(29, 3, 'Americano', 'Espresso diluted with hot water', 2.75, 18, 0, 'active', '2025-09-06 23:07:49'),
(30, 3, 'Mocha', 'Espresso with chocolate and steamed milk', 4.00, 12, 0, 'active', '2025-09-06 23:07:49'),
(31, 3, 'Croissant', 'Buttery flaky croissant', 2.25, 14, 0, 'active', '2025-09-06 23:07:49'),
(32, 4, 'Flat White', 'Espresso with microfoam', 3.50, 10, 0, 'inactive', '2025-09-06 23:07:49'),
(33, 4, 'Pumpkin Spice Latte', 'Seasonal flavored latte', 4.50, 5, 0, 'inactive', '2025-09-06 23:07:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive','blocked') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`, `status`) VALUES
(1249, 'user', 'user@coffee.com', '$2y$10$MN5JpK6u9gE2rBqax3H83.dOWJ3QidRQKtIv5z6lV4EorsP9FeMsG', 'user', '2025-09-06 17:02:15', 'active'),
(1250, 'admin', 'admin@coffee.com', '$2y$10$jJCVDBpnpONmVhJxMayKP.KUdtRdtiugwtXgv1qEz.myjNDsiNn5W', 'admin', '2025-09-06 18:48:15', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_items`
--
ALTER TABLE `store_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `store_items`
--
ALTER TABLE `store_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1253;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_items`
--
ALTER TABLE `store_items`
  ADD CONSTRAINT `store_items_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

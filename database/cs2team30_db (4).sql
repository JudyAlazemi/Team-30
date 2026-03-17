-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 17, 2026 at 02:46 PM
-- Server version: 8.0.45-0ubuntu0.22.04.1
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs2team30_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'pending',
  `must_change_password` tinyint(1) DEFAULT '1',
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `approval_status`, `must_change_password`, `approved_by`, `approved_at`, `created_at`) VALUES
(1, 'Main Admin', 'admin@sabil.com', '$2y$10$r1q2qxo5rWAC4Ta/hU/cEOUGJZ.31NjA65FzbM/klst7h5uA5XnjS', 'approved', 0, NULL, '2026-03-11 19:38:14', '2026-03-11 19:38:14'),
(2, 'Chandni Admin', 'chandniadmin@sabil.com', '$2y$10$wp5jXrBksp6PxSyUs4HCJedV09LQ7qXyKZlTKK9f31NBOidYc5F3W', 'approved', 1, 1, '2026-03-12 11:28:48', '2026-03-12 11:28:10'),
(3, 'jhon john', 'john9@gmail.com', '$2y$10$Ggyw9vpltOZLQ0oUSmKFie2JYKkVoCqeChRmb/MhnHOci6ddfzaPW', 'pending', 1, NULL, NULL, '2026-03-15 21:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'perfume', 'Perfumes and fragrances'),
(2, 'car-perfume', 'Car perfumes and fresheners'),
(3, 'candle', 'Scented candles'),
(4, 'home-spray', 'Home sprays and room fresheners'),
(5, 'body-wash', 'Body wash and shower gels');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`id`, `user_id`, `session_id`, `product_id`, `created_at`) VALUES
(2, 5, NULL, 1, '2025-12-04 22:04:53'),
(3, 5, NULL, 4, '2025-12-04 22:13:37'),
(4, NULL, 'gsotj1ev2a313r843r2ngipf8i', 4, '2025-12-05 11:26:35'),
(7, NULL, '90nrancim1kovpooge0o5t3mu9', 2, '2025-12-08 15:37:04'),
(8, NULL, '90nrancim1kovpooge0o5t3mu9', 6, '2025-12-08 15:37:24'),
(9, NULL, 'mvse2vtfm00b3gdnkibm5tq05d', 8, '2025-12-08 15:39:45'),
(10, NULL, 'hgd6m7m700egi8mgseg7rl8n7g', 1, '2025-12-08 16:10:44'),
(11, NULL, 'pt97mkvceum12gbaleflmt5ocs', 2, '2025-12-08 16:47:26'),
(12, NULL, 'pt97mkvceum12gbaleflmt5ocs', 4, '2025-12-08 16:47:48'),
(13, NULL, 'opa3jlj5rf7579ek5hq3j0ct75', 2, '2026-01-27 15:14:02'),
(14, NULL, 'opa3jlj5rf7579ek5hq3j0ct75', 3, '2026-02-02 19:17:27'),
(15, NULL, '58hfa66df7j1u6q3u62l4mjvf3', 1, '2026-02-05 12:47:16'),
(18, NULL, 'ri8km5po43jb09t82rfvpsgao8', 9, '2026-02-12 15:28:13'),
(19, NULL, '8h0v0nsbschdk5m39vmshq7c13', 1, '2026-02-12 17:01:53'),
(20, 10, NULL, 8, '2026-02-13 16:31:48'),
(22, NULL, '29o194d62a71dkhc82ds932b9d', 1, '2026-02-17 14:37:37'),
(23, NULL, '29o194d62a71dkhc82ds932b9d', 2, '2026-02-17 14:47:33'),
(29, 7, NULL, 1, '2026-03-10 14:06:42'),
(30, NULL, '242vv4ff5fiunjd39o2bqjlc5u', 1, '2026-03-10 15:44:19'),
(31, NULL, '8rsvs7pvoeotod584j3e698sig', 2, '2026-03-10 15:44:56');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 12, '74.79', 'processing', '2026-03-08 14:19:34', NULL),
(2, 9, '144.98', 'return_pending', '2026-03-09 22:10:59', '2026-03-09 22:12:58'),
(3, 13, '85.59', 'return_pending', '2026-03-09 22:24:40', '2026-03-09 22:25:00'),
(4, 7, '150.38', 'processing', '2026-03-10 14:06:27', NULL),
(5, 13, '38.35', 'processing', '2026-03-10 14:47:44', NULL),
(6, 9, '103.94', 'processing', '2026-03-16 19:27:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, '59.99'),
(2, 2, 1, 1, '59.99'),
(3, 2, 3, 1, '64.99'),
(4, 3, 2, 1, '69.99'),
(5, 4, 1, 1, '59.99'),
(6, 4, 2, 1, '69.99'),
(7, 5, 4, 1, '16.99'),
(8, 6, 2, 1, '69.99'),
(9, 6, 4, 1, '16.99');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image_url`, `category_id`) VALUES
(1, 'Ocean Breeze', 'Fresh aquatic fragrance with marine notes', '59.99', 97, 'assets/images/oceanmist.PNG', 1),
(2, 'Midnight Oud', 'Deep and mysterious oriental fragrance', '69.99', 97, 'assets/images/midnightoud.PNG', 1),
(3, 'Velvet Rose', 'Luxurious rose with velvety undertones', '64.99', 99, 'assets/images/velvetmusk.JPEG', 1),
(4, 'Unleaded Petrol', 'Energetic and bold car fragrance', '16.99', 98, 'assets/images/carperfdark.jpeg', 2),
(5, 'Ionix Fresh', 'Air-purifying fresh car scent', '14.99', 100, 'assets/images/carperflight.jpeg', 2),
(6, 'Lavender Cruise', 'Calming lavender for relaxed drives', '18.99', 100, 'assets/images/carperfmed.png', 2),
(7, 'Vanilla Dream Candle', 'Warm vanilla and cream scented candle', '12.99', 100, 'assets/images/candle.png', 3),
(8, 'Amber Woods Candle', 'Earthy amber with woody undertones', '16.99', 100, 'assets/images/candle.png', 3),
(9, 'Cherry Blossom Candle', 'Delicate floral cherry blossom scent', '14.99', 100, 'assets/images/candle.png', 3),
(10, 'Lavender Cloud Spray', 'Soothing lavender mist for relaxation', '17.99', 100, 'assets/images/homespraysilver.png', 4),
(11, 'Jasmine Home Spray', 'Exotic jasmine room freshener', '19.99', 100, 'assets/images/homespraygold.jpeg', 4),
(12, 'Ocean Breeze Spray', 'Fresh coastal air room spray', '16.99', 100, 'assets/images/homesprayblue.jpeg', 4),
(13, 'Tropical Breeze Body Wash', 'Exotic tropical fruit cleansing wash', '8.99', 100, 'assets/images/tropicalbreeze.JPEG', 5),
(14, 'Strawberry Silk Body Wash', 'Sweet strawberry with silk proteins', '9.99', 100, 'assets/images/strawbsilk.JPEG', 5),
(15, 'Ultra Fresh Body Wash', 'Deep cleansing with mint freshness', '7.99', 100, 'assets/images/ultrafresh.JPEG', 5);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int NOT NULL,
  `answer1` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `answer2` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `answer3` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `answer4` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `suggestion` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 9, 5, 'Amazing', '2026-03-08 00:28:49'),
(2, 8, 9, 5, 'Amazing', '2026-03-16 15:35:01'),
(3, 2, 9, 5, '..', '2026-03-16 19:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `site_reviews`
--

CREATE TABLE `site_reviews` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `site_reviews`
--

INSERT INTO `site_reviews` (`id`, `user_id`, `display_name`, `rating`, `comment`, `created_at`) VALUES
(2, 7, 'Anonymous', 5, 'I am obsessed!', '2026-03-16 18:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token_hash` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `security_question` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `security_answer_hash` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `reset_token_hash`, `reset_token_expires`, `security_question`, `security_answer_hash`, `is_active`) VALUES
(1, 'Judy', 'test@test.com', '$2y$10$OwPY6/xJ2XIqLDIgptHW.eLmmxrEpyF1P0WtR4PO73u4n39jyf2Bu', '2025-12-04 16:10:04', NULL, NULL, NULL, NULL, 1),
(5, 'SARA', 'SARA@Testing.com', '$2y$10$gTO9cjo7vPoLwn20/qLhj.rpIZv0xM9M3aVVIkJ/ZU7cbWVeq4h1m', '2025-12-04 20:25:32', NULL, NULL, NULL, NULL, 1),
(6, 'abc', 'abc@gmail.com', '$2y$10$HbE0MrTR.b65fwa9eT/v3.Wf/oafI516HZuJH7ch2G0r6qtPYOa7i', '2025-12-04 21:16:26', NULL, NULL, NULL, NULL, 1),
(7, 'judy', 'judy2@test.com', '$2y$10$sQOUjkxt1PdAusKm/yjxZO9Wy/BpJhWbnuFmV9.21o3cI44TPS8IW', '2025-12-04 21:41:02', NULL, NULL, NULL, NULL, 1),
(8, 'Sara', 'SaraTest@icloud.com', '$2y$10$mwv.WvFNfjD1MFn6Dnz1luOOADtqdTlLts3s0cdKsKD4jfCRH57MO', '2026-02-03 15:05:10', NULL, NULL, NULL, NULL, 1),
(9, 'jane', 'jane@s.com', '$2y$10$SYKCbnzhREiIeus.9BWIf.G3tVmaDzvTpft8XDp6c9tPzl8C4bBKK', '2026-02-08 17:00:03', NULL, NULL, NULL, NULL, 1),
(10, 'John Smith', 'johnsmith@gmail.com', '$2y$10$THKNXhFE3DcnLMkjq3tbNeDgSElCxWA97JZMlPM659haAX7mZkoou', '2026-02-13 01:13:03', NULL, NULL, NULL, NULL, 1),
(11, 'Chandni Test', 'chandnitest@gmail.com', '$2y$10$8gs/WbcggYdmcLCaSdyX9.mxv5vXrzpBEfVCpZa7xlIWx5xMbSHiy', '2026-02-13 16:59:04', NULL, NULL, NULL, NULL, 1),
(12, 'Chandni', 'chandni12@mail.com', '$2y$10$u42Iyqb/bOXU.uaVttMnsu3YdE9/DarU1i0SFO/r/0m4w1F4ocM7.', '2026-03-06 17:04:54', NULL, NULL, NULL, NULL, 1),
(13, 'chandni', 'chandni@mail.com', '$2y$10$RhqoGapnRz.Ger17QLJuR.Zf1t/p/X6EUvBe8lZ3eBsoUJOq7O//y', '2026-03-09 22:22:28', NULL, NULL, 'What was the name of your first school?', '$2y$10$vB7m04gbFCyoxXuDAQtsAeRFu532BegRAwkZ23z4cJghZ9Q1JP/Ni', 1),
(14, 'Sara', 'sara@123.com', '$2y$10$DqO03EpMhnm2Sv65.UVwlOyhLkXIH4.3XechY130xyfcs7Sq1X7i6', '2026-03-15 03:31:03', '$2y$10$PUImfqEE8DzncW7e9013T.IU1VZDI9pLSTKLD6YBP26aL9LIN9OaK', '2026-03-15 20:05:00', 'What is your mother’s first name?', '$2y$10$q3cdnve7yOe6jszSAszXrupyRdVOy7VOWwXKJEpZHQuunhG24c8wW', 1),
(15, 'Sara', 'sara2@123.com', '$2y$10$3vfHrl3LbtM2dfmo5rCxM.K2AYV7gr5Rf.DnknjdnAxcEs0kwAr3e', '2026-03-15 03:32:12', NULL, NULL, 'What was the name of your first pet?', '$2y$10$1lxydibjFlDOvKwwS6jG8OYmUXYoUHRXlO7YKVfwq5ADrflNBu2Ga', 1),
(16, 'John', 'john9@gmail.com', '$2y$10$3iIo.105isfnIBFw4UIq5uulLU3s8gpIOxvCVzK4Ol25d0YG7aeD6', '2026-03-15 21:29:53', NULL, NULL, 'What is your mother’s first name?', '$2y$10$nULJpP/WARfLie4AMIywT.JxwSWBm3qJHjIeLXBigY.sgr8rZGAP6', 1),
(17, 'Chandni', 'chandni@test.com', '$2y$10$lMLMq.MDVmcG7X/kyAGb6uboUaUUDgMWIoPyifmHpA6uRe31qisL2', '2026-03-16 11:52:30', NULL, NULL, 'What was the name of your first school?', '$2y$10$ecRtC1GQ09PBDw9ZAPYDZOnLD9ItyLeuwdKrfVioYeaUzO0TGDxeW', 1),
(18, 'chandni', 'chandni@yahoo.com', '$2y$10$Rg6HY7xj6FTOqXrKvDRW2ejZ9xo4O2stby4JkmsQjcWBrbozjt3ba', '2026-03-17 14:25:30', NULL, NULL, 'What was the name of your first school?', '$2y$10$cjATy6xPcHhrrn3JJ/nu/.p0fXhOCrDJm6HX4HmpmvYX8zw.FzqSi', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_admin_approved_by` (`approved_by`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `site_reviews`
--
ALTER TABLE `site_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `site_reviews`
--
ALTER TABLE `site_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admin_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `site_reviews`
--
ALTER TABLE `site_reviews`
  ADD CONSTRAINT `site_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

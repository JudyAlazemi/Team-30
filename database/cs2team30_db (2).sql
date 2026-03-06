-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2026 at 03:09 AM
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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
(21, 9, NULL, 1, '2026-02-13 23:19:05'),
(22, NULL, '29o194d62a71dkhc82ds932b9d', 1, '2026-02-17 14:37:37'),
(23, NULL, '29o194d62a71dkhc82ds932b9d', 2, '2026-02-17 14:47:33');

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
  `security_answer_hash` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `reset_token_hash`, `reset_token_expires`, `security_question`, `security_answer_hash`) VALUES
(1, 'Judy', 'test@test.com', '$2y$10$OwPY6/xJ2XIqLDIgptHW.eLmmxrEpyF1P0WtR4PO73u4n39jyf2Bu', '2025-12-04 16:10:04', NULL, NULL, NULL, NULL),
(5, 'SARA', 'SARA@Testing.com', '$2y$10$gTO9cjo7vPoLwn20/qLhj.rpIZv0xM9M3aVVIkJ/ZU7cbWVeq4h1m', '2025-12-04 20:25:32', NULL, NULL, NULL, NULL),
(6, 'abc', 'abc@gmail.com', '$2y$10$HbE0MrTR.b65fwa9eT/v3.Wf/oafI516HZuJH7ch2G0r6qtPYOa7i', '2025-12-04 21:16:26', NULL, NULL, NULL, NULL),
(7, 'judy', 'judy2@test.com', '$2y$10$sQOUjkxt1PdAusKm/yjxZO9Wy/BpJhWbnuFmV9.21o3cI44TPS8IW', '2025-12-04 21:41:02', NULL, NULL, NULL, NULL),
(8, 'Sara', 'SaraTest@icloud.com', '$2y$10$mwv.WvFNfjD1MFn6Dnz1luOOADtqdTlLts3s0cdKsKD4jfCRH57MO', '2026-02-03 15:05:10', NULL, NULL, NULL, NULL),
(9, 'jane', 'jane@s.com', '$2y$10$SYKCbnzhREiIeus.9BWIf.G3tVmaDzvTpft8XDp6c9tPzl8C4bBKK', '2026-02-08 17:00:03', NULL, NULL, NULL, NULL),
(10, 'John Smith', 'johnsmith@gmail.com', '$2y$10$THKNXhFE3DcnLMkjq3tbNeDgSElCxWA97JZMlPM659haAX7mZkoou', '2026-02-13 01:13:03', NULL, NULL, NULL, NULL),
(11, 'Chandni Test', 'chandnitest@gmail.com', '$2y$10$8gs/WbcggYdmcLCaSdyX9.mxv5vXrzpBEfVCpZa7xlIWx5xMbSHiy', '2026-02-13 16:59:04', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

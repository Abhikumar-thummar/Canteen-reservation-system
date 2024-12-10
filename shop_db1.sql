-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 11:28 PM
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
-- Database: `shop_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(14, 16, 'user', 'user@gmail.com', '4656', 'can i have 2 seats ');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `years` int(4) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `reference_code` varchar(10) NOT NULL,
  `time_slot_id` int(11) NOT NULL,
  `seat_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `years`, `branch`, `total_products`, `total_price`, `placed_on`, `payment_status`, `reference_code`, `time_slot_id`, `seat_number`) VALUES
(1, 16, 'user', '2563', 'user@gmail.com', 'credit card', 2025, 'tech', 'Fish and Chips  (1) ', 7, '18-Nov-2024', 'pending', '563A500B', 0, 'A2'),
(2, 16, 'user', '2563', 'user@gmail.com', 'credit card', 2025, 'tech', 'Vegetarian Ploughman’s Lunch (1) ', 4, '18-Nov-2024', 'completed', '074AF763', 2, '11'),
(3, 16, 'user', '2563', 'user@gmail.com', 'paytm', 2025, 'tech', 'Vegetarian Ploughman’s Lunch (1) ', 4, '18-Nov-2024', 'pending', '72D3C956', 2, '14'),
(4, 16, 'user', '2563', 'user@gmail.com', 'paypal', 2024, 'tech', 'Bacon Sandwich (1) , Fish and Chips  (1) ', 10, '18-Nov-2024', 'pending', '886D8716', 3, '18'),
(5, 16, 'user', '2563', 'user@gmail.com', 'paypal', 2024, 'tech', 'Shepherd’s Pie (1) ', 3, '18-Nov-2024', 'completed', '8DF62948', 3, '12'),
(6, 16, 'user', '2563', 'user@gmail.com', 'paypal', 2025, 'tech', 'Bacon Sandwich (1) , Vegetarian Ploughman’s Lunch (2) ', 11, '18-Nov-2024', 'pending', '5E046CAB', 2, '15'),
(7, 16, 'user', '2563', 'user@gmail.com', 'credit card', 2024, 'tech', 'Christmas package (1) , Bacon Sandwich (1) ', 13, '25-Nov-2024', 'pending', '484595BF', 1, '13'),
(8, 16, 'user', '2563', 'user@gmail.com', 'paypal', 2024, 'tech', 'Christmas package (1) , Shepherd’s Pie (2) ', 16, '25-Nov-2024', 'completed', '89BF89BF', 1, '19'),
(9, 16, 'user', '2563', 'user@gmail.com', 'paypal', 2024, 'tech', 'Christmas package (1) , Bacon Sandwich (2) ', 16, '25-Nov-2024', 'pending', '5E68453D', 2, '20'),
(10, 5, 'Abhikumar Thummar', '25', 'abhithummar375@gmail.com', 'credit card', 2024, 'MSC', 'Bacon Sandwich (1) ', 3, '03-Dec-2024', 'pending', '2C3EB7A3', 1, '17');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `package_description` text DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discounted_price` decimal(10,2) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `package_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`package_id`, `package_name`, `package_description`, `discount_percentage`, `discounted_price`, `start_time`, `end_time`, `package_image`) VALUES
(7, 'Christmas package', 'It\'s special for Christmas\r\n', 5.00, 10.40, '2024-11-19 18:23:00', '2024-11-28 18:23:00', 'food.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `package_items`
--

CREATE TABLE `package_items` (
  `package_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_items`
--

INSERT INTO `package_items` (`package_id`, `product_id`) VALUES
(7, 21),
(7, 22),
(7, 23);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image`, `stock`) VALUES
(21, 'Shepherd’s Pie', 'Minced lamb in rich gravy, topped with creamy mashed potatoes and baked to perfection.', 3, '24202-shepherds-pie-vi-ddmfs-beauty-1x2-50ef5508bcb44191bae30213b3325572.jpg', 14),
(22, 'Bacon Sandwich', 'Creamy oats served warm, topped with a drizzle of honey. Perfect for a cozy start to your day.', 3, 'dusty2.jpg', 11),
(23, 'Fish and Chips ', 'Golden-battered cod fillet served with chunky chips, mushy peas, and a wedge of lemon.', 7, 'intro-import (1).webp', 18),
(24, 'Vegetarian Ploughman’s Lunch', 'A selection of cheese, pickle, crusty bread, apple slices, and fresh salad.', 4, 'FV-Insta-2.jpg', 23),
(25, 'Bangers and Mash', 'Succulent pork sausages served on creamy mashed potatoes with onion gravy.\r\n\r\n', 6, 'images (1).jpg', 0),
(27, 'Vegetable sandwitch', 'Vegetables served between slices of soft multigrain bread, it’s the perfect choice for a light yet satisfying meal.', 7, '20220812_220717-01.jpeg', 21);

-- --------------------------------------------------------

--
-- Table structure for table `product_discounts`
--

CREATE TABLE `product_discounts` (
  `product_id` int(11) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(11) NOT NULL,
  `seat_number` varchar(20) NOT NULL,
  `status` enum('available','booked') DEFAULT 'available',
  `time_slot_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`seat_id`, `seat_number`, `status`, `time_slot_id`) VALUES
(2, 'A1', 'available', 1),
(11, 'A1', 'booked', 2),
(12, 'A1', 'booked', 3),
(13, 'A2', 'booked', 1),
(14, 'A3', 'available', 2),
(15, 'A2', 'booked', 2),
(16, 'A2', 'available', 3),
(17, 'A3', 'available', 1),
(18, 'A3', 'booked', 3),
(19, 'B1', 'available', 1),
(20, 'B2', 'available', 2);

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `time_slot_id` int(11) NOT NULL,
  `time_slot` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`time_slot_id`, `time_slot`) VALUES
(1, '10:00 AM - 12:00 PM'),
(2, '12:00 PM - 2:00 PM'),
(3, '2:00 PM - 4:00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(1, 'Admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'user B', 'user02@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user'),
(3, 'user A', 'user01@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user'),
(4, 'user', 'user@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user'),
(5, 'Abhikumar Thummar', 'abhithummar375@gmail.com', '202cb962ac59075b964b07152d234b70', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `package_items`
--
ALTER TABLE `package_items`
  ADD PRIMARY KEY (`package_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD PRIMARY KEY (`product_id`,`start_time`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `fk_time_slot` (`time_slot_id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`time_slot_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `time_slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `package_items`
--
ALTER TABLE `package_items`
  ADD CONSTRAINT `package_items_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD CONSTRAINT `product_discounts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `fk_time_slot` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots` (`time_slot_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots` (`time_slot_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

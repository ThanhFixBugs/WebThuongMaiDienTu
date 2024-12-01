-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 09:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nuhah`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(1, 3, 1, 22),
(3, 3, 4, 20),
(5, 3, 6, 20),
(6, 1, 1, 1),
(7, 1, 2, 20),
(8, 1, 3, 8),
(9, 5, 1, 6),
(10, 5, 2, 1),
(11, 5, 3, 2),
(12, 5, 4, 1),
(13, 6, 1, 1),
(14, 6, 2, 2),
(15, 6, 3, 1),
(17, 7, 2, 4),
(18, 7, 3, 3),
(19, 7, 4, 3),
(20, 2, 1, 1),
(21, 2, 2, 1),
(22, 2, 3, 1),
(23, 8, 1, 1),
(24, 8, 2, 1),
(25, 7, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(225) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `status`) VALUES
(1, 'Giày Beyono', 1),
(2, 'Giày Sao Vàng', 1),
(3, 'Giày Mizuno', 1),
(4, 'Giày Asics', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `product_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`id`, `order_id`, `product_id`, `quantity`, `product_price`) VALUES
(8, 16, 1, 6, 18),
(9, 16, 2, 4, 12),
(10, 17, 1, 6, 18),
(11, 18, 2, 4, 12),
(12, 18, 3, 3, 17),
(13, 19, 1, 6, 18),
(14, 19, 4, 3, 7),
(15, 20, 1, 1, 18),
(16, 21, 1, 6, 18),
(17, 22, 1, 6, 18),
(18, 23, 1, 1, 18),
(19, 23, 2, 1, 12),
(20, 24, 1, 1, 18),
(21, 24, 2, 1, 12),
(22, 25, 1, 1, 18),
(23, 25, 2, 1, 12),
(24, 26, 1, 6, 18),
(25, 26, 2, 4, 12),
(26, 27, 1, 7, 18);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `payment_date`, `order_status`, `payment_method`, `total_price`) VALUES
(16, 7, '2024-11-22 15:35:35', -1, 'cash', 156),
(17, 7, '2024-11-22 15:36:37', -1, 'PayPal', 108),
(18, 7, '2024-11-22 16:03:17', -1, 'PayPal', 99),
(19, 7, '2024-11-22 16:24:20', -1, 'PayPal', 129),
(20, 2, '2024-11-23 03:58:18', 0, 'cash', 18),
(21, 7, '2024-11-23 11:00:43', 0, 'PayPal', 108),
(22, 7, '2024-11-23 11:37:12', 0, 'PayPal', 108),
(23, 2, '2024-11-25 14:56:42', 0, 'PayPal', 30),
(24, 2, '2024-11-25 15:50:50', 0, 'PayPal', 30),
(25, 8, '2024-11-25 15:53:28', 0, 'cash', 30),
(26, 7, '2024-11-25 16:34:08', -1, 'PayPal', 156),
(27, 7, '2024-11-26 16:17:52', 0, 'cash', 126);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `catid` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(225) NOT NULL,
  `price` decimal(11,0) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(225) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `catid`, `title`, `price`, `description`, `image`, `date_added`, `status`) VALUES
(1, 1, 'Giày bóng chuyền Beyono Thunder 1', 18, 'Giày bóng chuyền Beyono Thunder với thiết kế nhẹ và độ bám sân tốt.', 'thunder.webp', '2024-11-13 17:00:00', 1),
(2, 1, 'Giày bóng chuyền Beyono Warrior', 12, 'Giày bóng chuyền Beyono Warrior hỗ trợ độ bật nhảy tốt.', 'Warrior.png', '2024-11-26 09:07:40', 1),
(3, 1, 'Giày bóng chuyền Beyono Orca', 17, 'Giày bóng chuyền Beyono Orca với đệm êm ái và độ bền cao.', 'orca1.png', '2024-11-26 09:09:38', 1),
(4, 2, 'Giày bóng chuyền Sao Vàng 102', 7, 'Giày bóng chuyền Sao Vàng 102 thiết kế bền bỉ, chống trượt.', 'saovang102.webp', '2024-11-14 01:44:17', 1),
(5, 2, 'Giày bóng chuyền Sao Vàng 301', 4, 'Giày bóng chuyền Sao Vàng 301 phù hợp cho người mới chơi.', 'saovang301.png', '2024-11-14 01:44:17', 1),
(6, 2, 'Giày bóng chuyền Sao Vàng 401', 5, 'Giày bóng chuyền Sao Vàng 401 với lớp đệm siêu nhẹ.', 'saovang401.jpg', '2024-11-14 01:44:17', 1),
(7, 3, 'Giày bóng chuyền Mizuno Wave Lightning', 6, 'Giày bóng chuyền Mizuno Wave Lightning hỗ trợ tốc độ và sự linh hoạt.', 'lightning.webp', '2024-11-14 01:44:17', 1),
(8, 3, 'Giày bóng chuyền Mizuno Wave Momentum', 9, 'Giày bóng chuyền Mizuno Wave Momentum đệm cao cấp, hỗ trợ vận động viên chuyên nghiệp.', 'momentum.webp', '2024-11-14 01:44:17', 1),
(9, 3, 'Giày bóng chuyền Mizuno Thuner Blade Z', 13, 'Giày bóng chuyền Mizuno Thunder Blade Z thiết kế mạnh mẽ, ổn định.', 'bladez.png', '2024-11-26 09:13:46', 1),
(10, 4, 'Giày bóng chuyền Asics Metarise 2', 18, 'Giày bóng chuyền Asics Metarise 2 với công nghệ Gel, giảm chấn tốt.', 'metarise2.webp', '2024-11-14 01:44:17', 1),
(11, 4, 'Giày bóng chuyền Asics Metarise', 17, 'Giày bóng chuyền Asics Metarise với thiết kế nhẹ, hỗ trợ di chuyển nhanh.', 'metarise.webp', '2024-11-14 01:44:17', 1),
(12, 4, 'Giày bóng chuyền Asics Sky Elite', 19, 'Giày bóng chuyền Asics Sky Elite đế giày chắc chắn, tăng độ bền.', 'sky.webp', '2024-11-14 01:44:17', 1),
(13, 1, 'Mì', 5, 'Đắt mà đéo ngon', 'th.jpg', '2024-11-29 17:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type` enum('member','admin','') DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `type`, `email`, `date_created`) VALUES
(1, 'whatloveone', '$2y$10$e/dhwwIzVuy./7IoASLC.OHVhfIwwPnx848GJbZ7tTZva.cPOtSSu', '', 'whatloveone1234@gmai.com', NULL),
(2, 'thanh123', '$2y$10$InyRuO5lNKGQpiQ.ECM8q.93ZRiyc5K7ZybfFC7RhPIumtS47xX0G', '', 'thanh@gmail.com', NULL),
(3, 'aa', '$2y$10$uvoaxFlYNKEK2Q7Tg/eCCuVEhB7UgCLIvPvYx3T9uVPu00SiBy0W.', '', 'aa@aa.aa', NULL),
(4, 'whatloveone2', '$2y$10$tUHY68gWInEdJdr.x4tw9e6ACTbccOk53w/zDyD2XVnHvIVjsJbbO', '', 'whatloveone12345@gmai.com', NULL),
(5, 'thanh1234', '$2y$10$h6/UPuhZuPd6NvIz1461GO2h2emYPd.3k94UeHuTw6UzQLXLFOvFS', '', 'whatloveone12346@gmai.com', NULL),
(6, 'thanh123456', '$2y$10$kCo2vR34PRCauNp/pIyui.kl7l3qCIQpdjlUr1BbCfYo.I9tV9C4u', '', 'whatloveone1234567@gmai.com', NULL),
(7, 'thanhkingchess', '$2y$10$WmwGNHBopmKcNI88WrEAZuZueDNCc/twP4SdidmeEsXd4coPv1rMu', '', 'thanhkingches123@gmail.com', NULL),
(8, 'whatloveone1234', '$2y$10$0ZVH4TmhqIB6.V9jgmC4FudtX/JRVoh6i.jKCPdZXtqZ4DK3jNq8C', '', 'nhuquynh191120044@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usersmeta`
--

CREATE TABLE `usersmeta` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usersmeta`
--

INSERT INTO `usersmeta` (`id`, `user_id`, `country`, `first_name`, `last_name`, `company`, `address`, `city`, `state`, `postcode`, `phone`) VALUES
(1, 7, 'Nhật Bổn', 'Thanh', 'Lê', 'Trách Nhiệm Hữu Hạn 1 Mình Tao', 'Long Hồ', 'Vĩnh Long', 'Đang Giao', 'u2184192', '3878218382'),
(2, 2, 'Nhật Bổn', 'Thanh', 'Lê', 'Trách Nhiệm Hữu Hạn 1 Mình Tao', 'Long Hồ', 'Vĩnh Long', 'Đang Giao', 'u2184192', '3878218382'),
(3, 8, 'Nhật Bổn', 'Thanh', 'Lê', 'Trách Nhiệm Hữu Hạn 1 Mình Tao', 'Long Hồ', 'Vĩnh Long', 'Đang Giao', 'u2184192', '3878218382');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `FK_cart_product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_orderitems_order_id` (`order_id`),
  ADD KEY `FK_orderitems_product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_orders_user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_catid` (`catid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usersmeta`
--
ALTER TABLE `usersmeta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_usersmeta_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `usersmeta`
--
ALTER TABLE `usersmeta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_cart_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `FK_orderitems_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `FK_orderitems_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_catid` FOREIGN KEY (`catid`) REFERENCES `category` (`id`);

--
-- Constraints for table `usersmeta`
--
ALTER TABLE `usersmeta`
  ADD CONSTRAINT `FK_usersmeta_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

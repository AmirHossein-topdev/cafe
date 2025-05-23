-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 06:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_title` varchar(255) DEFAULT NULL,
  `table_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `alt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `image`, `alt`) VALUES
(15, 'کیک', '1730906216_cake.png', 'caked'),
(16, 'نوشیدنی', '1733937404_1730906407_cup.png', 'drink'),
(17, 'فست فود', '1730906607_pizza.png', 'fast-food'),
(18, 'دمنوش', '1730907250_health tea.png', 'health-tea'),
(21, 'شیک', '1733934104_milkshake-table.png', 'shake'),
(22, 'قلیان', '1734899140_icons8-hookah-64.png', 'hookah');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_stock_tracked` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `price`, `category_id`, `image`, `description`, `stock`, `is_stock_tracked`) VALUES
(12, 'موکا', 70000, 16, '1730912477_CafeMocha-hero.61028a28.jpg', 'اسپرسو، شیر بخار داده‌شده و شکلات ذوب شده یا شکلات پودر شده', 0, 0),
(13, 'کاپوچینو', 80000, 16, '1730912629_Cappuccino_at_Sightglass_Coffee.jpg', 'اسپرسو، شیر بخار داده‌شده و فوم شیر', 0, 0),
(14, 'پیتزا رست بیف', 300000, 17, '1733170581_pitza.jpeg', 'نان پیتزا، سس گوجه، پنیر موتزارلا، رست بیف، پیاز قرمز', 5, 1),
(15, 'اسپرسو سینگل', 30000, 16, '1733932752_spersoo single.jpg', '', 0, 0),
(16, 'اسپرسو دبل', 45000, 16, '1733932782_spersoo double.jpg', '', 0, 0),
(17, 'آمریکانو', 60000, 16, '1733932839_amricano.jpg', '', 0, 0),
(18, 'کارامل ماکیتو', 80000, 16, '1733932858_caramel-makito.jpg', '', 0, 0),
(20, 'شیک هویج بستنی', 100000, 21, '1733934472_carrot shake.jpg', '', 10, 1),
(21, 'شیک لوتوس', 110000, 21, '1733934603_lotous shake.jpg', '', 10, 1),
(22, 'شیک دبل چاکلت', 140000, 21, '1733934760_chocolate shake.jpg', 'بستنی شکلات تکه ای ، سس فندق ، کرم شکلات ، تاپینگ شکلات و موز یخی', 10, 1),
(23, 'شیک نوتلا', 170000, 21, '1733934891_notela shake.jpg', '', 10, 1),
(24, 'کیک قهوه و گردو', 35000, 15, '1734005200_cofee and nut cake.jpg', '', 10, 1),
(25, 'کیک شکلاتی', 40000, 15, '1734005252_chocolate cake.jpg', '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `slide_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `image`, `slide_name`) VALUES
(2, '1730891900_cafeaks.jpg', 'عکس اصلی کافه'),
(3, '1730891920_coffe.jpg', 'عکس کافه ۲'),
(4, '1730891932_coffe1.jpg', 'عکس کافه ۱\r\n'),
(5, '1730892039_coffee3.jpg', 'عکس کافه ۳');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'ali@gmail.com', '123456789'),
(2, 'nima@gmail.com', '123456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

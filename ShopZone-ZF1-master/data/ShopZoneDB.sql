-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2017 at 06:22 PM
-- Server version: 5.6.35
-- PHP Version: 7.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ShopZoneDB`
--
CREATE DATABASE IF NOT EXISTS `ShopZoneDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ShopZoneDB`;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_off`()
BEGIN

DELETE FROM `offer` WHERE DATE_FORMAT(`offer_end`, '%M-%d-%y') < DATE_FORMAT(CURDATE(), '%M-%d-%y');

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cartitem`
--

CREATE TABLE IF NOT EXISTS `cartitem` (
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `comment` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE IF NOT EXISTS `coupon` (
  `id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `code` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `type` varchar(150) NOT NULL,
  `is_active` varchar(150) NOT NULL DEFAULT 'true'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE IF NOT EXISTS `offer` (
  `id` bigint(20) NOT NULL,
  `offer_per` float DEFAULT NULL,
  `offer_start` date DEFAULT NULL,
  `offer_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(12,2) NOT NULL,
  `customer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE IF NOT EXISTS `orderitem` (
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `order` int(11) NOT NULL,
  `product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `picture` longblob,
  `price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_users_rate` bigint(20) DEFAULT '0',
  `total_rates` bigint(20) DEFAULT '0',
  `rates_avg` float DEFAULT '0',
  `offer` bigint(20) DEFAULT NULL,
  `category` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `product_BEFORE_UPDATE` BEFORE UPDATE ON `product`
 FOR EACH ROW BEGIN
IF NOT ((NEW.total_users_rate <=> OLD.total_users_rate) AND (NEW.total_rates <=> OLD.total_rates)) THEN
      SET NEW.rates_avg = NEW.total_rates / NEW.total_users_rate ;
   END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE IF NOT EXISTS `rate` (
  `id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `rate` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cartitem__customer` (`customer`),
  ADD KEY `idx_cartitem__product` (`product`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_comment__customer_id` (`customer_id`),
  ADD KEY `idx_comment__product` (`product`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_coupon__customer` (`customer`),
  ADD KEY `idx_coupon__order` (`order`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order__customer` (`customer`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`order`,`product`),
  ADD KEY `idx_orderitem__product` (`product`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product__customer_id` (`customer_id`),
  ADD KEY `idx_product__offer` (`offer`),
  ADD KEY `idx_product__category` (`category`);

--
-- Indexes for table `rate`
--
ALTER TABLE `rate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rate__customer` (`customer`),
  ADD KEY `idx_rate__product` (`product`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wishlist__customer_id` (`customer_id`),
  ADD KEY `idx_wishlist__product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartitem`
--
ALTER TABLE `cartitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rate`
--
ALTER TABLE `rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD CONSTRAINT `fk_cartitem__customer` FOREIGN KEY (`customer`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `fk_cartitem__product` FOREIGN KEY (`product`) REFERENCES `product` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment__customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `fk_comment__product` FOREIGN KEY (`product`) REFERENCES `product` (`id`);

--
-- Constraints for table `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `fk_coupon__customer` FOREIGN KEY (`customer`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `fk_coupon__order` FOREIGN KEY (`order`) REFERENCES `order` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order__customer` FOREIGN KEY (`customer`) REFERENCES `customer` (`id`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `fk_orderitem__order` FOREIGN KEY (`order`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `fk_orderitem__product` FOREIGN KEY (`product`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product__category` FOREIGN KEY (`category`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `fk_product__customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `fk_product__offer` FOREIGN KEY (`offer`) REFERENCES `offer` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `fk_rate__customer` FOREIGN KEY (`customer`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `fk_rate__product` FOREIGN KEY (`product`) REFERENCES `product` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wishlist__customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `fk_wishlist__product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `offer_check` ON SCHEDULE EVERY 1 DAY STARTS '2017-03-01 00:00:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM `offer` WHERE DATE_FORMAT(`offer_end`, '%M-%d-%y') < DATE_FORMAT(CURDATE(), '%M-%d-%y')$$

CREATE DEFINER=`root`@`localhost` EVENT `SQL_SAFE_UPDATES0` ON SCHEDULE EVERY 1 DAY STARTS '2017-03-01 00:00:00' ON COMPLETION PRESERVE ENABLE DO SET SQL_SAFE_UPDATES=0$$

CREATE DEFINER=`root`@`localhost` EVENT `SQL_SAFE_UPDATES1` ON SCHEDULE EVERY 1 DAY STARTS '2017-03-01 00:15:00' ON COMPLETION PRESERVE ENABLE DO SET SQL_SAFE_UPDATES=1$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

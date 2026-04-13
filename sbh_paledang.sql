-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 05:29 PM
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
-- Database: `sbh_paledang`
--

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `room_no` int(11) NOT NULL,
  `guest_name` varchar(150) NOT NULL,
  `status` enum('ACTIVE','CHECKOUT') DEFAULT 'ACTIVE',
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `device_id`, `room_no`, `guest_name`, `status`, `checkin_date`, `checkout_date`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'a6af444e374846b3', 106, 'Febri', 'ACTIVE', '2025-12-15', '2025-12-16', 1, '2025-12-15 09:09:20', '2025-12-15 15:27:18', NULL),
(2, '123df', 105, 'Arya', 'ACTIVE', '2025-12-16', '2025-12-17', 1, '2025-12-15 02:42:38', '2025-12-15 15:43:57', '2025-12-15 15:43:57'),
(3, '1233', 104, 'Nita', 'ACTIVE', '2025-12-16', '2025-12-17', 1, '2025-12-15 02:49:37', '2025-12-15 22:13:23', '2025-12-15 02:51:15');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 1, 'American Breakfast', 'Eggs, sausage, toast, butter, jam', 80000.00, NULL, 1, 1, '2025-12-16 19:31:02', 1, '2025-12-17 12:07:14', NULL, NULL),
(2, 1, 'Nasi Goreng Breakfast', 'Fried rice with egg and chicken', 65000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(3, 2, 'French Fries', 'Crispy potato fries', 45000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(4, 2, 'Spring Roll', 'Vegetable spring roll with sweet chili sauce', 40000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(5, 3, 'Grilled Chicken Steak', 'Grilled chicken with blackpepper sauce', 120000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(6, 3, 'Beef Rendang', 'Traditional Indonesian spicy beef', 135000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(7, 4, 'Chocolate Lava Cake', 'Warm chocolate cake with melted center', 55000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(8, 4, 'Fruit Platter', 'Seasonal fresh fruits', 45000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(9, 5, 'Mineral Water', '600ml bottled water', 15000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(10, 5, 'Fresh Orange Juice', 'Freshly squeezed orange juice', 30000.00, NULL, 1, 1, '2025-12-16 19:31:02', 1, '2025-12-16 22:35:42', NULL, NULL),
(11, 6, 'Club Sandwich', 'Triple layer sandwich with fries', 50000.00, '1765898882_2153eaff0e2162e28733.jpg', 1, 1, '2025-12-16 19:31:02', 1, '2025-12-17 12:12:10', NULL, NULL),
(12, 7, 'Kids Chicken Nugget', 'Chicken nugget with fries', 50000.00, 'kids_nugget.jpg', 1, 1, '2025-12-16 19:31:02', 1, '2025-12-17 09:30:09', NULL, NULL),
(13, 8, 'Potato Chips', 'Classic salted potato chips', 23000.00, 'potato_chips.jpg', 1, 1, '2025-12-16 19:31:02', 1, '2025-12-17 11:49:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus2`
--

CREATE TABLE `menus2` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus2`
--

INSERT INTO `menus2` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 1, 'American Breakfast', 'Eggs, sausage, toast, butter, jam', 85000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(2, 1, 'Nasi Goreng Breakfast', 'Fried rice with egg and chicken', 65000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(3, 2, 'French Fries', 'Crispy potato fries', 45000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(4, 2, 'Spring Roll', 'Vegetable spring roll with sweet chili sauce', 40000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(5, 3, 'Grilled Chicken Steak', 'Grilled chicken with blackpepper sauce', 120000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(6, 3, 'Beef Rendang', 'Traditional Indonesian spicy beef', 135000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(7, 4, 'Chocolate Lava Cake', 'Warm chocolate cake with melted center', 55000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(8, 4, 'Fruit Platter', 'Seasonal fresh fruits', 45000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(9, 5, 'Mineral Water', '600ml bottled water', 15000.00, NULL, 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(10, 5, 'Fresh Orange Juice', 'Freshly squeezed orange juice', 30000.00, NULL, 1, 1, '2025-12-16 19:31:02', 1, '2025-12-16 22:35:42', NULL, NULL),
(11, 6, 'Club Sandwich', 'Triple layer sandwich with fries', 75000.00, '1765898882_2153eaff0e2162e28733.jpg', 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(12, 7, 'Kids Chicken Nugget', 'Chicken nugget with fries', 50000.00, 'kids_nugget.jpg', 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL),
(13, 8, 'Potato Chips', 'Classic salted potato chips', 25000.00, 'potato_chips.jpg', 1, 1, '2025-12-16 19:31:02', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort_order` int(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `name`, `sort_order`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'Breakfast', 1, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(2, 'Appetizer', 2, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(3, 'Main Course', 3, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(4, 'Dessert', 4, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(5, 'Beverages', 5, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(6, 'Room Service', 6, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(7, 'Kids Menu', 7, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(8, 'Snack', 8, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(9, 'Alcoholic Drinks', 9, 0, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(10, 'Cemilan', 10, 0, 1, '2025-12-17 00:34:05', 1, '2025-12-17 00:47:13', NULL, NULL),
(11, 'Gorengan', 11, 0, 1, '2025-12-17 01:09:21', 1, '2025-12-17 09:21:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories2`
--

CREATE TABLE `menu_categories2` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort_order` int(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_categories2`
--

INSERT INTO `menu_categories2` (`id`, `name`, `sort_order`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'Breakfast', 1, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(2, 'Appetizer', 2, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(3, 'Main Course', 3, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(4, 'Dessert', 4, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(5, 'Beverages', 5, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(6, 'Room Service', 6, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(7, 'Kids Menu', 7, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(8, 'Snack', 8, 1, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(9, 'Alcoholic Drinks', 9, 0, 1, '2025-12-16 23:55:05', NULL, NULL, NULL, NULL),
(10, 'Cemilan', 10, 0, 1, '2025-12-17 00:34:05', 1, '2025-12-17 00:47:13', NULL, NULL),
(11, 'Gorengan', 11, 0, 1, '2025-12-17 01:09:21', 1, '2025-12-17 01:21:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_variants`
--

CREATE TABLE `menu_variants` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_by` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_variants`
--

INSERT INTO `menu_variants` (`id`, `menu_id`, `name`, `price`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 1, 'Regular', 85000.00, 1, '2025-12-17 07:36:25', NULL, '2025-12-17 11:55:52', NULL, NULL),
(2, 1, 'Large', 100000.00, 1, '2025-12-17 07:36:25', NULL, '2025-12-17 11:55:52', NULL, NULL),
(3, 2, 'Regular', 65000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(4, 2, 'Large', 80000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(5, 3, 'Small', 35000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(6, 3, 'Medium', 45000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(7, 3, 'Large', 60000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(8, 5, 'Standard', 120000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(9, 5, 'Extra Sauce', 130000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(10, 6, 'Regular', 135000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(11, 6, 'Large', 160000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(12, 10, 'Medium', 30000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(13, 10, 'Large', 40000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(14, 11, 'Regular', 75000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(15, 11, 'Double Meat', 95000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(16, 12, '5 pcs', 50000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(17, 12, '8 pcs', 70000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_variants2`
--

CREATE TABLE `menu_variants2` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_by` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_variants2`
--

INSERT INTO `menu_variants2` (`id`, `menu_id`, `name`, `price`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 1, 'Regular', 85000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(2, 1, 'Large', 100000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(3, 2, 'Regular', 65000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(4, 2, 'Large', 80000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(5, 3, 'Small', 35000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(6, 3, 'Medium', 45000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(7, 3, 'Large', 60000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(8, 5, 'Standard', 120000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(9, 5, 'Extra Sauce', 130000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(10, 6, 'Regular', 135000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(11, 6, 'Large', 160000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(12, 10, 'Medium', 30000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(13, 10, 'Large', 40000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(14, 11, 'Regular', 75000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(15, 11, 'Double Meat', 95000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(16, 12, '5 pcs', 50000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL),
(17, 12, '8 pcs', 70000.00, 1, '2025-12-17 07:36:25', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(30) DEFAULT NULL,
  `room_no` varchar(20) DEFAULT NULL,
  `guest_name` varchar(150) DEFAULT NULL,
  `order_type` enum('POS','ROOM','QR') DEFAULT 'POS',
  `source_device` varchar(50) DEFAULT NULL,
  `status` enum('PENDING','COOKING','READY','DELIVERED','CANCEL') DEFAULT 'PENDING',
  `payment_status` varchar(20) DEFAULT 'UNPAID',
  `payment_method` varchar(20) DEFAULT NULL,
  `midtrans_order_id` varchar(50) DEFAULT NULL,
  `midtrans_transaction_id` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `total` decimal(12,2) DEFAULT 0.00,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `canceled_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `room_no`, `guest_name`, `order_type`, `source_device`, `status`, `payment_status`, `payment_method`, `midtrans_order_id`, `midtrans_transaction_id`, `notes`, `total`, `created_by`, `created_at`, `updated_at`, `updated_by`, `canceled_by`) VALUES
(1, 'ORD20251216001', '101', 'Budi Santoso', 'ROOM', NULL, 'DELIVERED', 'UNPAID', NULL, '', NULL, NULL, 85000.00, 1, '2025-12-16 12:15:00', '2025-12-17 23:36:40', 1, 1),
(2, 'ORD20251216002', NULL, 'Walk In - Andi', 'POS', NULL, 'COOKING', 'UNPAID', NULL, '', NULL, NULL, 65000.00, 2, '2025-12-16 12:30:00', NULL, NULL, NULL),
(3, 'ORD20251216003', '203', 'Siti Aisyah', 'QR', NULL, 'READY', 'UNPAID', NULL, '', NULL, NULL, 120000.00, 1, '2025-12-16 13:05:00', NULL, NULL, NULL),
(4, 'ORD20251216004', '305', 'John Doe', 'ROOM', NULL, 'DELIVERED', 'UNPAID', NULL, '', NULL, NULL, 45000.00, 1, '2025-12-16 13:40:00', NULL, NULL, NULL),
(5, 'ORD20251216005', NULL, 'Walk In - Rina', 'POS', NULL, 'CANCEL', 'UNPAID', NULL, '', NULL, NULL, 30000.00, 2, '2025-12-16 14:00:00', NULL, NULL, NULL),
(6, 'ORD20251218105727', '1', 'Saya', 'POS', NULL, 'PENDING', 'UNPAID', NULL, '', NULL, NULL, 38000.00, 1, '2025-12-18 10:57:27', '2025-12-18 10:57:27', NULL, NULL),
(7, 'ORD20251218115344', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, '', NULL, NULL, 80000.00, 1, '2025-12-18 11:53:44', '2025-12-18 11:53:44', NULL, NULL),
(8, 'ORD20251218173658', '10', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, '', NULL, NULL, 38000.00, 1, '2025-12-18 17:36:58', '2025-12-18 17:36:58', NULL, NULL),
(9, 'ORD20251218174220', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, '', NULL, NULL, 30000.00, 1, '2025-12-18 17:42:20', '2025-12-18 17:42:20', NULL, NULL),
(10, 'ORD20251218174631', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, '', NULL, NULL, 30000.00, 1, '2025-12-18 17:46:31', '2025-12-18 17:46:31', NULL, NULL),
(11, 'ORD20251218175157', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, '', NULL, NULL, 90000.00, 1, '2025-12-18 17:51:57', '2025-12-18 17:51:57', NULL, NULL),
(12, 'ORD20251218180907', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, '', NULL, NULL, 170000.00, 1, '2025-12-18 18:09:07', '2025-12-18 18:09:07', NULL, NULL),
(13, 'ORD20251218191721', '1001', 'Saya', 'POS', NULL, 'PENDING', 'UNPAID', NULL, NULL, NULL, NULL, 65000.00, 1, '2025-12-18 19:17:21', '2025-12-18 19:17:21', NULL, NULL),
(14, 'ORD20251218192608', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, NULL, NULL, NULL, 80000.00, 1, '2025-12-18 19:26:08', '2025-12-18 19:26:08', NULL, NULL),
(15, 'ORD20251218203033', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, NULL, NULL, NULL, 45000.00, 1, '2025-12-18 20:30:33', '2025-12-18 20:30:33', NULL, NULL),
(16, 'ORD20251218203807', '1002', 'Kami', 'POS', NULL, '', 'UNPAID', 'CASH', NULL, NULL, NULL, 80000.00, 1, '2025-12-18 20:38:07', '2025-12-18 20:38:10', NULL, NULL),
(17, 'ORD20251218204707', '1003', 'Kita', 'POS', NULL, 'PENDING', 'PAID', 'CASH', NULL, NULL, NULL, 55000.00, 1, '2025-12-18 20:47:07', '2025-12-18 20:47:24', NULL, NULL),
(18, 'ORD20251218210047', '', '', 'POS', NULL, 'PENDING', 'UNPAID', NULL, NULL, NULL, NULL, 30000.00, 1, '2025-12-18 21:00:47', '2025-12-18 21:00:47', NULL, NULL),
(26, 'ORD20251218222208', '1006', 'FPP', 'POS', NULL, 'PENDING', 'UNPAID', NULL, NULL, NULL, NULL, 55000.00, 1, '2025-12-18 22:22:08', '2025-12-18 22:22:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(150) DEFAULT NULL,
  `variant_name` varchar(100) DEFAULT NULL,
  `qty` int(11) DEFAULT 1,
  `price` decimal(12,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `status` enum('PENDING','COOKING','READY') DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `menu_name`, `variant_name`, `qty`, `price`, `subtotal`, `notes`, `status`) VALUES
(1, 1, 3, 'Nasi Goreng Spesial', NULL, 1, 35000.00, 35000.00, NULL, 'PENDING'),
(2, 1, 5, 'Es Teh Manis', NULL, 2, 10000.00, 20000.00, NULL, 'PENDING'),
(3, 1, 8, 'Ayam Goreng', NULL, 1, 30000.00, 30000.00, NULL, 'PENDING'),
(4, 2, 4, 'Mie Goreng', NULL, 1, 30000.00, 30000.00, NULL, 'PENDING'),
(5, 2, 6, 'Jus Alpukat', NULL, 1, 35000.00, 35000.00, NULL, 'PENDING'),
(6, 3, 2, 'Steak Ayam', NULL, 2, 45000.00, 90000.00, NULL, 'PENDING'),
(7, 3, 9, 'Mineral Water', NULL, 2, 15000.00, 30000.00, NULL, 'PENDING'),
(8, 4, 1, 'Breakfast Package', NULL, 1, 45000.00, 45000.00, NULL, 'PENDING'),
(9, 5, 7, 'Kentang Goreng', NULL, 1, 30000.00, 30000.00, NULL, 'PENDING'),
(10, 6, 13, 'Potato Chips', NULL, 1, 23000.00, 23000.00, NULL, 'PENDING'),
(11, 6, 9, 'Mineral Water', NULL, 1, 15000.00, 15000.00, NULL, 'PENDING'),
(12, 7, 9, 'Mineral Water', NULL, 1, 15000.00, 15000.00, NULL, 'PENDING'),
(13, 7, 2, 'Nasi Goreng Breakfast', NULL, 1, 65000.00, 65000.00, NULL, 'PENDING'),
(14, 8, 9, 'Mineral Water', NULL, 1, 15000.00, 15000.00, NULL, 'PENDING'),
(15, 8, 13, 'Potato Chips', NULL, 1, 23000.00, 23000.00, NULL, 'PENDING'),
(16, 9, 10, 'Fresh Orange Juice', NULL, 1, 30000.00, 30000.00, NULL, 'PENDING'),
(17, 10, 10, 'Fresh Orange Juice', NULL, 1, 30000.00, 30000.00, 'Hangat', 'PENDING'),
(18, 11, 3, 'French Fries', NULL, 2, 45000.00, 90000.00, 'Jgn pakai garam', 'PENDING'),
(19, 12, 5, 'Grilled Chicken Steak', NULL, 1, 120000.00, 120000.00, 'bagian dada', 'PENDING'),
(20, 12, 12, 'Kids Chicken Nugget', NULL, 1, 50000.00, 50000.00, 'Pakai saos tomat', 'PENDING'),
(21, 13, 2, 'Nasi Goreng Breakfast', NULL, 1, 65000.00, 65000.00, 'extra bawang', 'PENDING'),
(22, 14, 1, 'American Breakfast', NULL, 1, 80000.00, 80000.00, 'Extra Egg', 'PENDING'),
(23, 15, 10, 'Fresh Orange Juice', NULL, 1, 30000.00, 30000.00, '', 'PENDING'),
(24, 15, 9, 'Mineral Water', NULL, 1, 15000.00, 15000.00, 'dingin', 'PENDING'),
(25, 16, 1, 'American Breakfast', NULL, 1, 80000.00, 80000.00, '', 'PENDING'),
(26, 17, 7, 'Chocolate Lava Cake', NULL, 1, 55000.00, 55000.00, 'Extra coklat', 'PENDING'),
(27, 18, 10, 'Fresh Orange Juice', NULL, 1, 30000.00, 30000.00, 'Panas', 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@admin.com', '$2y$10$Xs3Qz2DIJ5HW2qKqZFNRsOt6z4X4SQGF7vs4h04azVtkE.FH78ESO', 'admin', '2025-12-14 17:13:15'),
(2, 'Febri', 'febri@admin.com', '$2y$10$tL.Pdocn7B8gcU3t1wfTuu.eXPaDRFaf0.wsPqO/tuycgS7s9HLpS', 'admin', '2025-12-14 20:51:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `menus2`
--
ALTER TABLE `menus2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_categories2`
--
ALTER TABLE `menu_categories2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_variants`
--
ALTER TABLE `menu_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `menu_variants2`
--
ALTER TABLE `menu_variants2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

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
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menus2`
--
ALTER TABLE `menus2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu_categories2`
--
ALTER TABLE `menu_categories2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu_variants`
--
ALTER TABLE `menu_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `menu_variants2`
--
ALTER TABLE `menu_variants2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 08:04 PM
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
-- Database: `arseniossilay`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `timestamp` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('465k4tptms09142hmisdnuismftufnre', '::1', 1793976566, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333937363536363b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('ikfu1qh8acai7l6qid4a11dva908l1mp', '::1', 1793976975, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333937363937353b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('r9mo268vcvumav7t0f7b7elhi5vccs9n', '::1', 1793977465, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333937373436343b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('olq8m8cl2p32qcugcsspkqldqulcf51t', '::1', 1793977769, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333937373736393b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('olab7ed5oumerolk37qqj28io9vbr93k', '::1', 1793982780, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333938323738303b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('u9ak3hbbvcim9md09c9thq0ka8lp1suu', '::1', 1793988372, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333938383337323b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('6s34m4nnqi594ker8ld3tt9gmn990qjj', '::1', 1793988753, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333938383735333b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('u3ksn5riipbvi3ch1os0gt01jrrptq4f', '::1', 1793989142, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333938393134323b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('ml1ii2f74bbrtdjkq1rtraijs10rp8ml', '::1', 1793989579, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333938393537393b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('qsdnughperfq35792osm98qp1ef1e4pm', '::1', 1793989196, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333938393138353b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a373a224b69746368656e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a373a226b69746368656e223b733a383a227572526f6c654944223b693a343b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('e7c7v3uhs6o6lljsdblas2r1u9oa8mv3', '::1', 1793989914, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333938393931343b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('ntr7c244h9k5tmlrq0g0t4l5bd40n1g3', '::1', 1793990354, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333939303335343b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('ftasgot6rf9ie8rmjutfqmcja204hpl8', '::1', 1793990796, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333939303739363b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('gtrk22gh1359fj6i64p4nd0drgvep68e', '::1', 1793991117, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333939313131373b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('40shcb2heqg7pbh2nq7smrn3uan54q0f', '::1', 1793991432, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333939313433323b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d),
('fqcp1i36el01jolajk72te4en1vm3dnh', '::1', 1793991802, 0x5f5f63695f6c6173745f726567656e65726174657c693a313739333939313433323b6c6f67696e7c4f3a383a22737464436c617373223a373a7b733a343a2275724944223b693a303b733a363a2275724e616d65223b733a353a2241646d696e223b733a373a2275724c6e616d65223b733a303a22223b733a31303a227572557365726e616d65223b733a353a2261646d696e223b733a383a227572526f6c654944223b693a313b733a383a227572537461747573223b693a313b733a31303a22757250617373776f7264223b733a33323a223161316463393163393037333235633639323731646466306339343462633732223b7d);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `ctID` int(10) NOT NULL,
  `ctTable` int(10) DEFAULT NULL,
  `ctProduct` int(10) DEFAULT NULL,
  `ctQty` int(10) DEFAULT NULL,
  `ctSales` int(10) DEFAULT NULL,
  `ctDiscount` int(10) DEFAULT NULL,
  `ctPrice` int(10) DEFAULT NULL,
  `ctWaiter` int(10) DEFAULT NULL,
  `ctDate_created` datetime DEFAULT NULL,
  `ctTimeOrder` datetime DEFAULT NULL,
  `ctTimeInprogress` datetime DEFAULT NULL,
  `ctTimeDeliver` datetime DEFAULT NULL,
  `ctTimeServe` datetime DEFAULT NULL,
  `ctStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `catID` int(10) NOT NULL,
  `catName` varchar(255) DEFAULT NULL,
  `catSortOrder` int(10) DEFAULT NULL,
  `catDate_created` datetime DEFAULT NULL,
  `catDate_modified` datetime DEFAULT NULL,
  `catStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`catID`, `catName`, `catSortOrder`, `catDate_created`, `catDate_modified`, `catStatus`) VALUES
(1, 'Meals', 1, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(2, 'Drinks', 2, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(3, 'Desserts', 3, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_checkin`
--

CREATE TABLE `tbl_checkin` (
  `ckID` int(10) NOT NULL,
  `ckRoomID` int(10) NOT NULL,
  `ckDate` date NOT NULL,
  `ckDateCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `ckPaymentStatus` int(1) DEFAULT NULL,
  `ckGuestName` varchar(255) DEFAULT NULL,
  `ckGuestEmail` varchar(255) NOT NULL,
  `ckGuestPhone` int(20) NOT NULL,
  `ckOtherCharges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ckFixDiscount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ckRoomDiscountID` int(10) DEFAULT NULL,
  `ckDays` int(10) NOT NULL,
  `ckPrice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ckDiscountAmount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ckCheckInType` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Check-in, 2=Reservation, 3=Completed',
  `ckReservationDate` datetime DEFAULT NULL,
  `ckPaymentType` varchar(20) DEFAULT NULL COMMENT 'cash or card',
  `ckMessage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_checkin`
--

INSERT INTO `tbl_checkin` (`ckID`, `ckRoomID`, `ckDate`, `ckDateCreated`, `ckPaymentStatus`, `ckGuestName`, `ckGuestEmail`, `ckGuestPhone`, `ckOtherCharges`, `ckFixDiscount`, `ckRoomDiscountID`, `ckDays`, `ckPrice`, `ckDiscountAmount`, `ckCheckInType`, `ckReservationDate`, `ckPaymentType`, `ckMessage`) VALUES
(1, 10, '2026-11-05', '2026-11-04 20:35:42', NULL, 'test', '', 0, 0.00, 0.00, NULL, 0, 1200.00, 0.00, 2, '2026-11-04 20:35:42', 'cash', ''),
(2, 11, '2026-11-04', '2026-11-04 23:19:26', NULL, 'test', '', 0, 0.00, 0.00, NULL, 4, 8000.00, 0.00, 1, NULL, 'cash', ''),
(3, 10, '2026-11-06', '2026-11-04 23:29:23', NULL, 'sASas', 'ASs@sdasda.com', 1123123, 0.00, 0.00, NULL, 3, 3600.00, 0.00, 2, '2026-11-04 23:29:23', 'cash', 'sample'),
(4, 10, '2026-11-11', '2026-11-04 23:39:26', NULL, 'asdas', 'asdasda@asdasd.com', 0, 0.00, 0.00, NULL, 2, 2400.00, 0.00, 1, NULL, 'cash', 'asdasdasd'),
(5, 10, '2026-11-16', '2026-11-05 00:00:24', NULL, 'TEST', 'TEST@ESDSD.COM', 2323, 0.00, 0.00, NULL, 5, 6000.00, 0.00, 2, '2026-11-05 00:00:24', NULL, 'TEST'),
(6, 10, '2026-11-23', '2026-11-07 02:07:11', NULL, 'test', 'test@test.com', 999, 0.00, 0.00, NULL, 3, 3600.00, 0.00, 2, '2026-11-07 02:07:11', NULL, 'testing message');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cms_homepage`
--

CREATE TABLE `tbl_cms_homepage` (
  `homeID` int(11) NOT NULL,
  `homeName` varchar(100) NOT NULL,
  `homeContent` longtext NOT NULL,
  `homeStatus` int(1) NOT NULL DEFAULT 1,
  `homeLastModified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_cms_homepage`
--

INSERT INTO `tbl_cms_homepage` (`homeID`, `homeName`, `homeContent`, `homeStatus`, `homeLastModified`) VALUES
(1, 'home', '<h1>Welcome</h1><p>Sample homepage content.</p>', 1, '2026-11-03 14:41:41'),
(2, 'homeBanner', '{\"slides\":[{\"eyebrow\":\"BOUTIQUE HOTEL • YOUR CITY\",\"number\":\"01\",\"h1\":\"Stays worth remembering.\",\"description\":\"Thoughtful spaces, warm service, and a stay designed around you.\",\"image\":\"f087d7d7899e949fa651e518b134451d.jpg\",\"status\":1},{\"eyebrow\":\"DESIGNED FOR SLOW MORNINGS\",\"number\":\"02\",\"h1\":\"Wake up somewhere special.\",\"description\":\"Make room for quiet moments and memorable city escapes.\",\"image\":\"1928bfda3df2c3321ffbb01f5fbf6df8.jpg\",\"status\":1},{\"eyebrow\":\"ROOM TO UNWIND\",\"number\":\"03\",\"h1\":\"Settle into your own rhythm.\",\"description\":\"Comfortable details create a stay that feels effortless.\",\"image\":\"d3fe35888c43fdf0a5a01346cfe72789.jpg\",\"status\":1},{\"eyebrow\":\"A STAY TO REMEMBER\",\"number\":\"04\",\"h1\":\"Come for the experience.\",\"description\":\"Explore the hotel, then make the city your own.\",\"image\":\"6ed376f4a12ecaacb46c63af5fcacd93.jpg\",\"status\":1}]}', 1, '2026-11-04 20:29:56'),
(3, 'homeIntro', '{\"top\":\"WELCOME 2\",\"title\":\"Your hotel story starts here.\",\"description\":\"Use this section for your hotel\'s introduction, location, history, or main selling point. Keep it short and easy to scan.\"}', 1, '2026-11-04 03:39:22'),
(4, 'homeBlock1', '{\"top\":\"THE HOTEL\",\"title\":\"Designed around the way you travel.\",\"description\":\"Replace this copy with information about rooms, location, design, service, or the hotel\'s unique character.\",\"link\":\"\",\"image\":\"74656c51d53fb63ddc8339112f9a636a.jpg\"}', 0, '2026-11-04 20:05:38'),
(5, 'homeBlock2', '{\"top\":\"FOOD & DRINK\",\"title\":\"Good food. Good company.\",\"description\":\"Describe your restaurant, bar, breakfast experience, chef, or signature dining concept here.\",\"link\":\"\",\"image\":\"2fc52de98e7905ae1b1a350ade14ae54.jpg\"}', 0, '2026-11-04 20:05:38'),
(6, 'homeAmenities', '{\"title\":\"Everything you need.\",\"items\":[{\"title\":\"Free Wi-Fi\",\"description\":\"Fast and reliable internet throughout the hotel.\"},{\"title\":\"Fitness\",\"description\":\"A dedicated space to keep your routine going.\"},{\"title\":\"Dining\",\"description\":\"Local flavors and relaxed dining throughout the day.\"},{\"title\":\"Parking\",\"description\":\"Convenient parking options for hotel guests.\"}]}', 0, '2026-11-04 20:04:51'),
(7, 'homeOffers', '{\"title\":\"Something extra for your next stay.\",\"items\":[{\"title\":\"Stay a little longer.\",\"description\":\"Offer description goes here. Add your rate, conditions and booking details.\"},{\"title\":\"Book direct and save.\",\"description\":\"Use this card for a member rate, package, seasonal offer or promotion.\"}]}', 0, '2026-11-04 20:05:09'),
(8, 'homeCTA', '{\"smalltitle\":\"MAKE IT YOURS\",\"bigtitle\":\"Ready for your next stay?\",\"link\":\"\"}', 0, '2026-11-04 20:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_discounts`
--

CREATE TABLE `tbl_discounts` (
  `disID` int(10) NOT NULL,
  `disName` varchar(255) DEFAULT NULL,
  `disPercent` int(1) DEFAULT NULL,
  `disDate_created` datetime DEFAULT NULL,
  `disDate_modified` datetime DEFAULT NULL,
  `disStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_discounts`
--

INSERT INTO `tbl_discounts` (`disID`, `disName`, `disPercent`, `disDate_created`, `disDate_modified`, `disStatus`) VALUES
(1, 'Senior Citizen', 20, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(2, 'PWD', 20, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(3, 'Sample Promo', 10, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hris`
--

CREATE TABLE `tbl_hris` (
  `userID` int(11) NOT NULL,
  `userTimeIn` datetime NOT NULL,
  `userTimeOut` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kitchen`
--

CREATE TABLE `tbl_kitchen` (
  `kitID` int(10) NOT NULL,
  `kitCart` int(10) DEFAULT NULL,
  `kitTable` int(10) DEFAULT NULL,
  `kitProduct` int(10) DEFAULT NULL,
  `kitQty` int(10) DEFAULT NULL,
  `kitStatus` int(1) DEFAULT NULL,
  `kitPrinted` int(1) NOT NULL DEFAULT 0,
  `kitTimeOrdered` datetime DEFAULT NULL,
  `kitTimeDelivered` datetime DEFAULT NULL,
  `kitTimePrinted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

CREATE TABLE `tbl_logs` (
  `logID` int(15) NOT NULL,
  `logSubject` varchar(255) DEFAULT NULL,
  `logUser` int(10) DEFAULT NULL,
  `logContent` longtext DEFAULT NULL,
  `logDateCreated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_logs`
--

INSERT INTO `tbl_logs` (`logID`, `logSubject`, `logUser`, `logContent`, `logDateCreated`) VALUES
(49, 'login', 0, 'login', '2026-11-03 14:42:05'),
(50, 'login', 0, 'login', '2026-11-03 22:39:46'),
(51, 'login', 0, 'login', '2026-11-03 22:48:09'),
(52, 'add product', 0, '{\"qty\":\"2\",\"table\":\"1\",\"product\":\"4\",\"price\":90,\"waiter\":0}', '2026-11-03 22:57:11'),
(53, 'add product', 0, '{\"qty\":\"5\",\"table\":\"1\",\"product\":\"6\",\"price\":350,\"waiter\":0}', '2026-11-04 00:32:48'),
(54, 'login', 0, 'login', '2026-11-04 06:00:48'),
(55, 'login', 0, 'login', '2026-11-04 20:04:12'),
(56, 'login', 0, 'login', '2026-11-05 02:10:32'),
(57, 'login', 0, 'login', '2026-11-06 22:26:29'),
(58, 'remove cart item', 0, '{\"table id\":\"1\",\"cart id\":\"48998\",\"product id\":null,\"data\":{\"prodQty\":20}}', '2026-11-06 22:51:09'),
(59, 'remove cart item', 0, '{\"table id\":\"1\",\"cart id\":\"48997\",\"product id\":null,\"data\":{\"prodQty\":100}}', '2026-11-06 22:51:12'),
(60, 'remove cart item', 0, '{\"table id\":\"1\",\"cart id\":\"2\",\"product id\":null,\"data\":{\"prodQty\":102}}', '2026-11-06 22:51:14'),
(61, 'remove cart item', 0, '{\"table id\":\"1\",\"cart id\":\"1\",\"product id\":null,\"data\":{\"prodQty\":52}}', '2026-11-06 22:51:15'),
(62, 'add product', 0, '{\"qty\":\"5\",\"table\":\"1\",\"product\":\"4\",\"price\":225,\"waiter\":0}', '2026-11-06 22:51:26'),
(63, 'cart checkout', 0, '{\"table id\":\"1\",\"order id\":2,\"data\":{\"customer_name\":\"test\",\"payment_date\":\"2026-11-07T00:34:49\",\"other_charges\":0,\"add_discount\":0,\"payment\":\"500.00\",\"change\":\"275.00\",\"cart_data\":\"[{\\\"ctID\\\":\\\"48999\\\",\\\"ctTable\\\":\\\"1\\\",\\\"ctProduct\\\":\\\"4\\\",\\\"ctQty\\\":\\\"5\\\",\\\"ctSales\\\":\\\"225\\\",\\\"ctDiscount\\\":\\\"0\\\",\\\"ctPrice\\\":\\\"225\\\",\\\"ctWaiter\\\":\\\"0\\\",\\\"ctDate_created\\\":\\\"2026-11-06 22:51:26\\\",\\\"ctTimeOrder\\\":\\\"2026-11-06 22:51:28\\\",\\\"ctTimeInprogress\\\":\\\"2026-11-07 00:34:38\\\",\\\"ctTimeDeliver\\\":null,\\\"ctTimeServe\\\":\\\"2026-11-07 00:34:39\\\",\\\"ctStatus\\\":\\\"3\\\",\\\"prodID\\\":\\\"4\\\",\\\"prodName\\\":\\\"Iced Tea\\\",\\\"prodCategory\\\":\\\"2\\\",\\\"prodPrice\\\":\\\"45\\\",\\\"prodSortOrder\\\":\\\"1\\\",\\\"prodQty\\\":\\\"97\\\",\\\"prodMinQty\\\":\\\"10\\\",\\\"prodImg\\\":\\\"\\\",\\\"prodDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodStatus\\\":\\\"1\\\",\\\"disID\\\":null,\\\"disName\\\":null,\\\"disPercent\\\":null,\\\"disDate_created\\\":null,\\\"disDate_modified\\\":null,\\\"disStatus\\\":null,\\\"tbID\\\":\\\"1\\\",\\\"tbName\\\":\\\"Table 1\\\",\\\"tbCategory\\\":\\\"1\\\",\\\"tbRoomID\\\":null,\\\"tbSortOrder\\\":\\\"1\\\",\\\"tbDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbType\\\":\\\"1\\\",\\\"tbStatus\\\":\\\"1\\\",\\\"urID\\\":null,\\\"urName\\\":null,\\\"urLname\\\":null,\\\"urUsername\\\":null,\\\"urPassword\\\":null,\\\"urDate_created\\\":null,\\\"urDate_modified\\\":null,\\\"urRoleID\\\":null,\\\"urStatus\\\":null}]\",\"discount\":0,\"price\":225,\"casher\":0,\"sales\":225,\"table\":\"1\"}}', '2026-11-07 00:35:02'),
(64, 'login', 0, 'login', '2026-11-07 02:19:54'),
(65, 'add product', 0, '{\"qty\":\"1\",\"table\":\"1\",\"product\":\"6\",\"price\":70,\"waiter\":0}', '2026-11-07 02:22:13'),
(66, 'change table', 0, '{\"table id from\":\"1\",\"table id to\":\"4\"}', '2026-11-07 02:22:34'),
(67, 'change table', 0, '{\"table id from\":\"4\",\"table id to\":\"1\"}', '2026-11-07 02:22:45'),
(68, 'add product', 0, '{\"qty\":\"5\",\"table\":\"1\",\"product\":\"4\",\"price\":225,\"waiter\":0}', '2026-11-07 02:26:33'),
(69, 'cart checkout', 0, '{\"table id\":\"1\",\"order id\":3,\"data\":{\"customer_name\":\"test\",\"payment_date\":\"2026-11-07T02:51:57\",\"other_charges\":0,\"add_discount\":0,\"payment\":\"500.00\",\"change\":\"205.00\",\"cart_data\":\"[{\\\"ctID\\\":\\\"49000\\\",\\\"ctTable\\\":\\\"1\\\",\\\"ctProduct\\\":\\\"6\\\",\\\"ctQty\\\":\\\"1\\\",\\\"ctSales\\\":\\\"70\\\",\\\"ctDiscount\\\":\\\"0\\\",\\\"ctPrice\\\":\\\"70\\\",\\\"ctWaiter\\\":\\\"0\\\",\\\"ctDate_created\\\":\\\"2026-11-07 02:22:13\\\",\\\"ctTimeOrder\\\":null,\\\"ctTimeInprogress\\\":null,\\\"ctTimeDeliver\\\":null,\\\"ctTimeServe\\\":null,\\\"ctStatus\\\":\\\"0\\\",\\\"prodID\\\":\\\"6\\\",\\\"prodName\\\":\\\"Leche Flan\\\",\\\"prodCategory\\\":\\\"3\\\",\\\"prodPrice\\\":\\\"70\\\",\\\"prodSortOrder\\\":\\\"1\\\",\\\"prodQty\\\":\\\"19\\\",\\\"prodMinQty\\\":\\\"3\\\",\\\"prodImg\\\":\\\"\\\",\\\"prodDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodStatus\\\":\\\"1\\\",\\\"disID\\\":null,\\\"disName\\\":null,\\\"disPercent\\\":null,\\\"disDate_created\\\":null,\\\"disDate_modified\\\":null,\\\"disStatus\\\":null,\\\"tbID\\\":\\\"1\\\",\\\"tbName\\\":\\\"Table 1\\\",\\\"tbCategory\\\":\\\"1\\\",\\\"tbRoomID\\\":null,\\\"tbSortOrder\\\":\\\"1\\\",\\\"tbDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbType\\\":\\\"1\\\",\\\"tbStatus\\\":\\\"1\\\",\\\"urID\\\":null,\\\"urName\\\":null,\\\"urLname\\\":null,\\\"urUsername\\\":null,\\\"urPassword\\\":null,\\\"urDate_created\\\":null,\\\"urDate_modified\\\":null,\\\"urRoleID\\\":null,\\\"urStatus\\\":null},{\\\"ctID\\\":\\\"49001\\\",\\\"ctTable\\\":\\\"1\\\",\\\"ctProduct\\\":\\\"4\\\",\\\"ctQty\\\":\\\"5\\\",\\\"ctSales\\\":\\\"225\\\",\\\"ctDiscount\\\":\\\"0\\\",\\\"ctPrice\\\":\\\"225\\\",\\\"ctWaiter\\\":\\\"0\\\",\\\"ctDate_created\\\":\\\"2026-11-07 02:26:33\\\",\\\"ctTimeOrder\\\":null,\\\"ctTimeInprogress\\\":null,\\\"ctTimeDeliver\\\":null,\\\"ctTimeServe\\\":null,\\\"ctStatus\\\":\\\"0\\\",\\\"prodID\\\":\\\"4\\\",\\\"prodName\\\":\\\"Iced Tea\\\",\\\"prodCategory\\\":\\\"2\\\",\\\"prodPrice\\\":\\\"45\\\",\\\"prodSortOrder\\\":\\\"1\\\",\\\"prodQty\\\":\\\"92\\\",\\\"prodMinQty\\\":\\\"10\\\",\\\"prodImg\\\":\\\"\\\",\\\"prodDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodStatus\\\":\\\"1\\\",\\\"disID\\\":null,\\\"disName\\\":null,\\\"disPercent\\\":null,\\\"disDate_created\\\":null,\\\"disDate_modified\\\":null,\\\"disStatus\\\":null,\\\"tbID\\\":\\\"1\\\",\\\"tbName\\\":\\\"Table 1\\\",\\\"tbCategory\\\":\\\"1\\\",\\\"tbRoomID\\\":null,\\\"tbSortOrder\\\":\\\"1\\\",\\\"tbDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbType\\\":\\\"1\\\",\\\"tbStatus\\\":\\\"1\\\",\\\"urID\\\":null,\\\"urName\\\":null,\\\"urLname\\\":null,\\\"urUsername\\\":null,\\\"urPassword\\\":null,\\\"urDate_created\\\":null,\\\"urDate_modified\\\":null,\\\"urRoleID\\\":null,\\\"urStatus\\\":null}]\",\"discount\":0,\"price\":295,\"casher\":0,\"sales\":295,\"table\":\"1\"}}', '2026-11-07 02:52:09'),
(70, 'cart checkout', 0, '{\"table id\":\"1\",\"order id\":4,\"data\":{\"customer_name\":\"test\",\"payment_date\":\"2026-11-07T02:51:57\",\"other_charges\":0,\"add_discount\":0,\"payment\":\"500.00\",\"change\":\"205.00\",\"cart_data\":\"[{\\\"ctID\\\":\\\"49000\\\",\\\"ctTable\\\":\\\"1\\\",\\\"ctProduct\\\":\\\"6\\\",\\\"ctQty\\\":\\\"1\\\",\\\"ctSales\\\":\\\"70\\\",\\\"ctDiscount\\\":\\\"0\\\",\\\"ctPrice\\\":\\\"70\\\",\\\"ctWaiter\\\":\\\"0\\\",\\\"ctDate_created\\\":\\\"2026-11-07 02:22:13\\\",\\\"ctTimeOrder\\\":null,\\\"ctTimeInprogress\\\":null,\\\"ctTimeDeliver\\\":null,\\\"ctTimeServe\\\":null,\\\"ctStatus\\\":\\\"0\\\",\\\"prodID\\\":\\\"6\\\",\\\"prodName\\\":\\\"Leche Flan\\\",\\\"prodCategory\\\":\\\"3\\\",\\\"prodPrice\\\":\\\"70\\\",\\\"prodSortOrder\\\":\\\"1\\\",\\\"prodQty\\\":\\\"19\\\",\\\"prodMinQty\\\":\\\"3\\\",\\\"prodImg\\\":\\\"\\\",\\\"prodDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodStatus\\\":\\\"1\\\",\\\"disID\\\":null,\\\"disName\\\":null,\\\"disPercent\\\":null,\\\"disDate_created\\\":null,\\\"disDate_modified\\\":null,\\\"disStatus\\\":null,\\\"tbID\\\":\\\"1\\\",\\\"tbName\\\":\\\"Table 1\\\",\\\"tbCategory\\\":\\\"1\\\",\\\"tbRoomID\\\":null,\\\"tbSortOrder\\\":\\\"1\\\",\\\"tbDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbType\\\":\\\"1\\\",\\\"tbStatus\\\":\\\"1\\\",\\\"urID\\\":null,\\\"urName\\\":null,\\\"urLname\\\":null,\\\"urUsername\\\":null,\\\"urPassword\\\":null,\\\"urDate_created\\\":null,\\\"urDate_modified\\\":null,\\\"urRoleID\\\":null,\\\"urStatus\\\":null},{\\\"ctID\\\":\\\"49001\\\",\\\"ctTable\\\":\\\"1\\\",\\\"ctProduct\\\":\\\"4\\\",\\\"ctQty\\\":\\\"5\\\",\\\"ctSales\\\":\\\"225\\\",\\\"ctDiscount\\\":\\\"0\\\",\\\"ctPrice\\\":\\\"225\\\",\\\"ctWaiter\\\":\\\"0\\\",\\\"ctDate_created\\\":\\\"2026-11-07 02:26:33\\\",\\\"ctTimeOrder\\\":null,\\\"ctTimeInprogress\\\":null,\\\"ctTimeDeliver\\\":null,\\\"ctTimeServe\\\":null,\\\"ctStatus\\\":\\\"0\\\",\\\"prodID\\\":\\\"4\\\",\\\"prodName\\\":\\\"Iced Tea\\\",\\\"prodCategory\\\":\\\"2\\\",\\\"prodPrice\\\":\\\"45\\\",\\\"prodSortOrder\\\":\\\"1\\\",\\\"prodQty\\\":\\\"92\\\",\\\"prodMinQty\\\":\\\"10\\\",\\\"prodImg\\\":\\\"\\\",\\\"prodDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodStatus\\\":\\\"1\\\",\\\"disID\\\":null,\\\"disName\\\":null,\\\"disPercent\\\":null,\\\"disDate_created\\\":null,\\\"disDate_modified\\\":null,\\\"disStatus\\\":null,\\\"tbID\\\":\\\"1\\\",\\\"tbName\\\":\\\"Table 1\\\",\\\"tbCategory\\\":\\\"1\\\",\\\"tbRoomID\\\":null,\\\"tbSortOrder\\\":\\\"1\\\",\\\"tbDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbType\\\":\\\"1\\\",\\\"tbStatus\\\":\\\"1\\\",\\\"urID\\\":null,\\\"urName\\\":null,\\\"urLname\\\":null,\\\"urUsername\\\":null,\\\"urPassword\\\":null,\\\"urDate_created\\\":null,\\\"urDate_modified\\\":null,\\\"urRoleID\\\":null,\\\"urStatus\\\":null}]\",\"discount\":0,\"price\":295,\"casher\":0,\"sales\":295,\"table\":\"1\"}}', '2026-11-07 02:52:25'),
(71, 'add product', 0, '{\"qty\":\"5\",\"table\":\"1\",\"product\":\"6\",\"price\":350,\"waiter\":0}', '2026-11-07 02:57:21'),
(72, 'cart checkout', 0, '{\"table id\":\"1\",\"order id\":5,\"data\":{\"customer_name\":\"Anonymous\",\"payment_date\":\"2026-11-07T02:57:22\",\"other_charges\":0,\"add_discount\":0,\"payment\":\"9000.00\",\"change\":\"8650.00\",\"cart_data\":\"[{\\\"ctID\\\":\\\"49002\\\",\\\"ctTable\\\":\\\"1\\\",\\\"ctProduct\\\":\\\"6\\\",\\\"ctQty\\\":\\\"5\\\",\\\"ctSales\\\":\\\"350\\\",\\\"ctDiscount\\\":\\\"0\\\",\\\"ctPrice\\\":\\\"350\\\",\\\"ctWaiter\\\":\\\"0\\\",\\\"ctDate_created\\\":\\\"2026-11-07 02:57:21\\\",\\\"ctTimeOrder\\\":null,\\\"ctTimeInprogress\\\":null,\\\"ctTimeDeliver\\\":null,\\\"ctTimeServe\\\":null,\\\"ctStatus\\\":\\\"0\\\",\\\"prodID\\\":\\\"6\\\",\\\"prodName\\\":\\\"Leche Flan\\\",\\\"prodCategory\\\":\\\"3\\\",\\\"prodPrice\\\":\\\"70\\\",\\\"prodSortOrder\\\":\\\"1\\\",\\\"prodQty\\\":\\\"14\\\",\\\"prodMinQty\\\":\\\"3\\\",\\\"prodImg\\\":\\\"\\\",\\\"prodDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"prodStatus\\\":\\\"1\\\",\\\"disID\\\":null,\\\"disName\\\":null,\\\"disPercent\\\":null,\\\"disDate_created\\\":null,\\\"disDate_modified\\\":null,\\\"disStatus\\\":null,\\\"tbID\\\":\\\"1\\\",\\\"tbName\\\":\\\"Table 1\\\",\\\"tbCategory\\\":\\\"1\\\",\\\"tbRoomID\\\":null,\\\"tbSortOrder\\\":\\\"1\\\",\\\"tbDate_created\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbDate_modified\\\":\\\"2026-11-03 14:41:41\\\",\\\"tbType\\\":\\\"1\\\",\\\"tbStatus\\\":\\\"1\\\",\\\"urID\\\":null,\\\"urName\\\":null,\\\"urLname\\\":null,\\\"urUsername\\\":null,\\\"urPassword\\\":null,\\\"urDate_created\\\":null,\\\"urDate_modified\\\":null,\\\"urRoleID\\\":null,\\\"urStatus\\\":null}]\",\"discount\":0,\"price\":350,\"casher\":0,\"sales\":350,\"table\":\"1\"}}', '2026-11-07 02:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `odrID` int(10) NOT NULL,
  `odrTable` int(10) DEFAULT NULL,
  `odrCustomer` varchar(255) DEFAULT NULL,
  `odrItems` int(10) DEFAULT NULL,
  `odrPrice` int(10) DEFAULT NULL,
  `odrSales` int(10) DEFAULT NULL,
  `odrOtherCharges` int(10) DEFAULT NULL,
  `odrAddDiscount` int(10) DEFAULT NULL,
  `odrPayment` int(10) DEFAULT NULL,
  `odrDiscount` int(10) DEFAULT NULL,
  `odrChange` int(10) DEFAULT NULL,
  `odrCasher` int(10) DEFAULT NULL,
  `odrStatus` int(1) DEFAULT NULL,
  `odrDate_created` datetime DEFAULT NULL,
  `odrJsonCartItems` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pages`
--

CREATE TABLE `tbl_pages` (
  `pageID` int(10) NOT NULL,
  `pageTitle` varchar(255) NOT NULL,
  `pageSlug` varchar(255) NOT NULL,
  `pageCSS` text NOT NULL,
  `pageJS` text NOT NULL,
  `pageContent` text NOT NULL,
  `pageStatus` int(1) NOT NULL,
  `pageDateCreated` datetime NOT NULL,
  `pageLastModified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pages`
--

INSERT INTO `tbl_pages` (`pageID`, `pageTitle`, `pageSlug`, `pageCSS`, `pageJS`, `pageContent`, `pageStatus`, `pageDateCreated`, `pageLastModified`) VALUES
(1, 'sample', 'test', '', '', 'sample page', 1, '2026-11-03 16:30:35', '2026-11-03 16:30:35'),
(2, 'sample', 'sample', '', '', 'this is sample page', 1, '2026-11-03 16:43:15', '2026-11-03 16:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prints`
--

CREATE TABLE `tbl_prints` (
  `prtID` int(10) NOT NULL,
  `prtType` int(2) DEFAULT NULL,
  `prtKitchenID` int(10) DEFAULT NULL,
  `prtCartID` int(10) NOT NULL,
  `prtStatus` int(1) DEFAULT NULL,
  `prtDateCreated` datetime DEFAULT NULL,
  `prtDatePrinted` datetime DEFAULT NULL,
  `prtData` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_prints`
--

INSERT INTO `tbl_prints` (`prtID`, `prtType`, `prtKitchenID`, `prtCartID`, `prtStatus`, `prtDateCreated`, `prtDatePrinted`, `prtData`) VALUES
(35, 2, NULL, 49000, 1, '2026-11-07 02:48:26', '2026-11-07 02:51:31', '{\"table_id\":1,\"cart\":[{\"ctID\":\"49000\",\"ctTable\":\"1\",\"ctProduct\":\"6\",\"ctQty\":\"1\",\"ctSales\":\"70\",\"ctDiscount\":\"0\",\"ctPrice\":\"70\",\"ctWaiter\":\"0\",\"ctDate_created\":\"2026-11-07 02:22:13\",\"ctTimeOrder\":null,\"ctTimeInprogress\":null,\"ctTimeDeliver\":null,\"ctTimeServe\":null,\"ctStatus\":\"0\",\"prodID\":\"6\",\"prodName\":\"Leche Flan\",\"prodCategory\":\"3\",\"prodPrice\":\"70\",\"prodSortOrder\":\"1\",\"prodQty\":\"19\",\"prodMinQty\":\"3\",\"prodImg\":\"\",\"prodDate_created\":\"2026-11-03 14:41:41\",\"prodDate_modified\":\"2026-11-03 14:41:41\",\"prodStatus\":\"1\",\"disID\":null,\"disName\":null,\"disPercent\":null,\"disDate_created\":null,\"disDate_modified\":null,\"disStatus\":null,\"tbID\":\"1\",\"tbName\":\"Table 1\",\"tbCategory\":\"1\",\"tbRoomID\":null,\"tbSortOrder\":\"1\",\"tbDate_created\":\"2026-11-03 14:41:41\",\"tbDate_modified\":\"2026-11-03 14:41:41\",\"tbType\":\"1\",\"tbStatus\":\"1\",\"urID\":null,\"urName\":null,\"urLname\":null,\"urUsername\":null,\"urPassword\":null,\"urDate_created\":null,\"urDate_modified\":null,\"urRoleID\":null,\"urStatus\":null},{\"ctID\":\"49001\",\"ctTable\":\"1\",\"ctProduct\":\"4\",\"ctQty\":\"5\",\"ctSales\":\"225\",\"ctDiscount\":\"0\",\"ctPrice\":\"225\",\"ctWaiter\":\"0\",\"ctDate_created\":\"2026-11-07 02:26:33\",\"ctTimeOrder\":null,\"ctTimeInprogress\":null,\"ctTimeDeliver\":null,\"ctTimeServe\":null,\"ctStatus\":\"0\",\"prodID\":\"4\",\"prodName\":\"Iced Tea\",\"prodCategory\":\"2\",\"prodPrice\":\"45\",\"prodSortOrder\":\"1\",\"prodQty\":\"92\",\"prodMinQty\":\"10\",\"prodImg\":\"\",\"prodDate_created\":\"2026-11-03 14:41:41\",\"prodDate_modified\":\"2026-11-03 14:41:41\",\"prodStatus\":\"1\",\"disID\":null,\"disName\":null,\"disPercent\":null,\"disDate_created\":null,\"disDate_modified\":null,\"disStatus\":null,\"tbID\":\"1\",\"tbName\":\"Table 1\",\"tbCategory\":\"1\",\"tbRoomID\":null,\"tbSortOrder\":\"1\",\"tbDate_created\":\"2026-11-03 14:41:41\",\"tbDate_modified\":\"2026-11-03 14:41:41\",\"tbType\":\"1\",\"tbStatus\":\"1\",\"urID\":null,\"urName\":null,\"urLname\":null,\"urUsername\":null,\"urPassword\":null,\"urDate_created\":null,\"urDate_modified\":null,\"urRoleID\":null,\"urStatus\":null}]}'),
(36, 1, NULL, 4, 1, '2026-11-07 02:52:25', '2026-11-07 02:52:26', NULL),
(37, 1, NULL, 5, 1, '2026-11-07 02:57:30', '2026-11-07 02:57:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `prodID` int(10) NOT NULL,
  `prodName` varchar(255) DEFAULT NULL,
  `prodCategory` int(10) DEFAULT NULL,
  `prodPrice` int(10) DEFAULT NULL,
  `prodSortOrder` int(10) DEFAULT NULL,
  `prodQty` int(10) DEFAULT NULL,
  `prodMinQty` int(10) DEFAULT NULL,
  `prodImg` varchar(255) DEFAULT NULL,
  `prodDate_created` datetime DEFAULT NULL,
  `prodDate_modified` datetime DEFAULT NULL,
  `prodStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`prodID`, `prodName`, `prodCategory`, `prodPrice`, `prodSortOrder`, `prodQty`, `prodMinQty`, `prodImg`, `prodDate_created`, `prodDate_modified`, `prodStatus`) VALUES
(1, 'Chicken Inasal', 1, 180, 1, 52, 5, '', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(2, 'Pork BBQ', 1, 150, 2, 50, 5, '', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(3, 'Pancit Canton', 1, 120, 3, 30, 5, '', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(4, 'Iced Tea', 2, 45, 1, 92, 10, '', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(5, 'Bottled Water', 2, 25, 2, 100, 10, '', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1),
(6, 'Leche Flan', 3, 70, 1, 14, 3, '', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_reports`
--

CREATE TABLE `tbl_product_reports` (
  `prID` int(10) NOT NULL,
  `prOrderID` int(10) DEFAULT NULL,
  `prTable` int(10) DEFAULT NULL,
  `prProduct` int(10) DEFAULT NULL,
  `prQty` int(10) DEFAULT NULL,
  `prDiscount` int(10) DEFAULT NULL,
  `prPrice` int(10) DEFAULT NULL,
  `prSales` int(10) DEFAULT NULL,
  `prWaiter` int(10) DEFAULT NULL,
  `prDateCreated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_product_reports`
--

INSERT INTO `tbl_product_reports` (`prID`, `prOrderID`, `prTable`, `prProduct`, `prQty`, `prDiscount`, `prPrice`, `prSales`, `prWaiter`, `prDateCreated`) VALUES
(2, 2, 1, 4, 5, 0, 225, 225, 0, '2026-11-07 00:35:02'),
(3, 3, 1, 6, 1, 0, 70, 70, 0, '2026-11-07 02:52:09'),
(4, 3, 1, 4, 5, 0, 225, 225, 0, '2026-11-07 02:52:09'),
(5, 4, 1, 6, 1, 0, 70, 70, 0, '2026-11-07 02:52:25'),
(6, 4, 1, 4, 5, 0, 225, 225, 0, '2026-11-07 02:52:25'),
(7, 5, 1, 6, 5, 0, 350, 350, 0, '2026-11-07 02:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reservations`
--

CREATE TABLE `tbl_reservations` (
  `resID` int(11) NOT NULL,
  `resRoomID` int(11) NOT NULL,
  `resDate` date NOT NULL,
  `resDate_Created` date NOT NULL,
  `resPaymentStatus` int(1) NOT NULL DEFAULT 1,
  `resGuestName` varchar(255) DEFAULT NULL,
  `resOtherCharges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `resRoomDiscountID` int(10) DEFAULT NULL,
  `resPrice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `resDiscountAmount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `resPayment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `resChange` decimal(10,2) NOT NULL DEFAULT 0.00,
  `resPaymentType` varchar(20) NOT NULL DEFAULT 'cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rooms`
--

CREATE TABLE `tbl_rooms` (
  `rmID` int(15) NOT NULL,
  `rmTitle` varchar(255) NOT NULL,
  `rmDesc` varchar(255) NOT NULL,
  `rmCapacity` varchar(255) NOT NULL,
  `rmInfo` varchar(255) NOT NULL,
  `rmPrice` int(10) NOT NULL,
  `rmDate_modified` date NOT NULL,
  `rmDate_Created` date NOT NULL,
  `rmStatus` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_rooms`
--

INSERT INTO `tbl_rooms` (`rmID`, `rmTitle`, `rmDesc`, `rmCapacity`, `rmInfo`, `rmPrice`, `rmDate_modified`, `rmDate_Created`, `rmStatus`) VALUES
(10, 'Sample Room 1', 'Standard sample room', '1', 'Development sample room', 1200, '2026-11-04', '2026-11-03', 1),
(11, 'Sample Room 2', 'Family sample room', '5', 'Development sample room', 2000, '2026-11-04', '2026-11-03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rooms_discount`
--

CREATE TABLE `tbl_rooms_discount` (
  `disID` int(10) NOT NULL,
  `disName` varchar(255) DEFAULT NULL,
  `disPercent` int(1) DEFAULT NULL,
  `disDate_created` datetime DEFAULT NULL,
  `disDate_modified` datetime DEFAULT NULL,
  `disStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_rooms_discount`
--

INSERT INTO `tbl_rooms_discount` (`disID`, `disName`, `disPercent`, `disDate_created`, `disDate_modified`, `disStatus`) VALUES
(1, 'Room Senior Citizen', 20, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_room_discounts`
--

CREATE TABLE `tbl_room_discounts` (
  `disrID` int(10) NOT NULL,
  `disrName` varchar(255) DEFAULT NULL,
  `disrPercent` int(3) DEFAULT NULL,
  `disrDate_created` datetime DEFAULT NULL,
  `disrDate_modified` datetime DEFAULT NULL,
  `disrStatus` int(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_room_discounts`
--

INSERT INTO `tbl_room_discounts` (`disrID`, `disrName`, `disrPercent`, `disrDate_created`, `disrDate_modified`, `disrStatus`) VALUES
(1, 'Room Senior Citizen', 20, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_room_images`
--

CREATE TABLE `tbl_room_images` (
  `rmiID` int(11) NOT NULL,
  `rmiRoomID` int(11) NOT NULL,
  `rmiPath` varchar(500) NOT NULL,
  `rmiStatus` tinyint(1) NOT NULL DEFAULT 1,
  `rmiDateCreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_room_images`
--

INSERT INTO `tbl_room_images` (`rmiID`, `rmiRoomID`, `rmiPath`, `rmiStatus`, `rmiDateCreated`) VALUES
(1, 10, 'assets/uploads/rooms/images/room_10_20261104060200_115587bd2d.jpg', 1, '2026-11-04 06:02:00'),
(2, 10, 'assets/uploads/rooms/images/room_10_20261104060200_322ab75664.jpg', 1, '2026-11-04 06:02:00'),
(3, 10, 'assets/uploads/rooms/images/room_10_20261104060200_4fe0add003.jpg', 1, '2026-11-04 06:02:00'),
(4, 10, 'assets/uploads/rooms/images/room_10_20261104060200_5aea45e0a1.jpg', 1, '2026-11-04 06:02:00'),
(5, 10, 'assets/uploads/rooms/images/room_10_20261104060200_e0bc077acf.jpg', 1, '2026-11-04 06:02:00'),
(6, 11, 'assets/uploads/rooms/images/room_11_20261104060213_2ed7499b01.jpg', 1, '2026-11-04 06:02:13'),
(7, 11, 'assets/uploads/rooms/images/room_11_20261104060213_074e0f52dd.jpg', 1, '2026-11-04 06:02:13'),
(8, 11, 'assets/uploads/rooms/images/room_11_20261104060213_34eeaa04f2.jpg', 1, '2026-11-04 06:02:13'),
(9, 11, 'assets/uploads/rooms/images/room_11_20261104060213_02a25873ce.jpg', 1, '2026-11-04 06:02:13'),
(10, 11, 'assets/uploads/rooms/images/room_11_20261104060213_a1db6b8cce.jpg', 1, '2026-11-04 06:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_room_stays`
--

CREATE TABLE `tbl_room_stays` (
  `stayID` int(11) NOT NULL,
  `stayRoomID` int(11) NOT NULL,
  `stayReservationID` int(11) DEFAULT NULL,
  `stayGuestName` varchar(255) NOT NULL,
  `stayDate` date NOT NULL,
  `stayTimeIn` datetime NOT NULL,
  `stayTimeOut` datetime DEFAULT NULL,
  `stayRoomPrice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayRoomDiscount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayOtherCharges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayRoomPayment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayRoomChange` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayPaymentType` varchar(20) NOT NULL DEFAULT 'cash',
  `stayStatus` enum('active','checked_out') NOT NULL DEFAULT 'active',
  `stayCheckoutTotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayCheckoutPayment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayCheckoutChange` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stayCreatedBy` int(11) DEFAULT NULL,
  `stayCheckoutBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_room_stay_orders`
--

CREATE TABLE `tbl_room_stay_orders` (
  `rsoID` int(11) NOT NULL,
  `rsoStayID` int(11) NOT NULL,
  `rsoProductID` int(11) NOT NULL,
  `rsoProductName` varchar(255) NOT NULL,
  `rsoQty` int(11) NOT NULL DEFAULT 1,
  `rsoUnitPrice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `rsoLineTotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `rsoCreatedBy` int(11) DEFAULT NULL,
  `rsoCreatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `SeID` int(10) NOT NULL,
  `SeName` varchar(255) DEFAULT NULL,
  `SeSettings` varchar(255) DEFAULT NULL,
  `SeLastModified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`SeID`, `SeName`, `SeSettings`, `SeLastModified`) VALUES
(1, 'userAdmin', '1a1dc91c907325c69271ddf0c944bc72', NULL),
(2, 'userKitchen', '1a1dc91c907325c69271ddf0c944bc72', NULL),
(5, 'kitchen_ip', '192.168.1.12', '2026-11-06 22:52:14'),
(6, 'navMenu', '[{\"label\":\"Rooms\",\"link\":\"/rooms\",\"enabled\":1}]', '2026-11-04 20:31:51'),
(7, 'cashier_ip', '192.168.1.12', '2026-11-07 02:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tables`
--

CREATE TABLE `tbl_tables` (
  `tbID` int(10) NOT NULL,
  `tbName` varchar(50) DEFAULT NULL,
  `tbCategory` int(11) NOT NULL DEFAULT 1,
  `tbRoomID` int(15) DEFAULT NULL,
  `tbSortOrder` int(10) DEFAULT NULL,
  `tbDate_created` datetime DEFAULT NULL,
  `tbDate_modified` datetime DEFAULT NULL,
  `tbType` int(1) NOT NULL DEFAULT 1,
  `tbStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_tables`
--

INSERT INTO `tbl_tables` (`tbID`, `tbName`, `tbCategory`, `tbRoomID`, `tbSortOrder`, `tbDate_created`, `tbDate_modified`, `tbType`, `tbStatus`) VALUES
(1, 'Table 1', 1, NULL, 1, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1, 1),
(2, 'Table 2', 1, NULL, 2, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1, 1),
(3, 'Table 3', 1, NULL, 3, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 1, 1),
(4, 'Takeout', 1, NULL, 4, '2026-11-03 14:41:41', '2026-11-03 14:41:41', 2, 1),
(24, 'Sample Room 1', 3, 10, 6, '2026-11-03 14:43:39', '2026-11-04 06:02:02', 1, 1),
(25, 'Sample Room 2', 3, 11, 7, '2026-11-03 14:44:09', '2026-11-04 06:02:15', 1, 1),
(26, 'Cafe 1', 2, NULL, 10, '2026-11-03 14:44:55', '2026-11-03 14:44:55', 1, 1),
(27, 'Cafe 2', 2, NULL, 11, '2026-11-03 14:45:04', '2026-11-03 14:45:04', 1, 1),
(28, 'Cafe 3', 2, NULL, 12, '2026-11-03 14:45:12', '2026-11-03 14:45:12', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `urID` int(10) NOT NULL,
  `urName` varchar(255) DEFAULT NULL,
  `urLname` varchar(255) DEFAULT NULL,
  `urUsername` varchar(255) DEFAULT NULL,
  `urPassword` varchar(255) DEFAULT NULL,
  `urDate_created` datetime DEFAULT NULL,
  `urDate_modified` datetime DEFAULT NULL,
  `urRoleID` int(10) DEFAULT NULL,
  `urStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`urID`, `urName`, `urLname`, `urUsername`, `urPassword`, `urDate_created`, `urDate_modified`, `urRoleID`, `urStatus`) VALUES
(1, 'Cashier', 'Sample', 'cashier', '1a1dc91c907325c69271ddf0c944bc72', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 3, 1),
(2, 'Waiter', 'Sample', 'waiter', '1a1dc91c907325c69271ddf0c944bc72', '2026-11-03 14:41:41', '2026-11-03 14:41:41', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_website_settings`
--

CREATE TABLE `tbl_website_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`ctID`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`catID`);

--
-- Indexes for table `tbl_checkin`
--
ALTER TABLE `tbl_checkin`
  ADD PRIMARY KEY (`ckID`),
  ADD KEY `idx_ck_room_date_type` (`ckRoomID`,`ckDate`,`ckCheckInType`),
  ADD KEY `idx_ck_date_type` (`ckDate`,`ckCheckInType`),
  ADD KEY `idx_ck_room_discount` (`ckRoomDiscountID`);

--
-- Indexes for table `tbl_cms_homepage`
--
ALTER TABLE `tbl_cms_homepage`
  ADD PRIMARY KEY (`homeID`),
  ADD UNIQUE KEY `uq_tbl_cms_homepage_homeName` (`homeName`);

--
-- Indexes for table `tbl_discounts`
--
ALTER TABLE `tbl_discounts`
  ADD PRIMARY KEY (`disID`);

--
-- Indexes for table `tbl_hris`
--
ALTER TABLE `tbl_hris`
  ADD PRIMARY KEY (`userID`,`userTimeIn`),
  ADD KEY `idx_user_checkin_time` (`userTimeIn`),
  ADD KEY `idx_user_checkout_time` (`userTimeOut`);

--
-- Indexes for table `tbl_kitchen`
--
ALTER TABLE `tbl_kitchen`
  ADD PRIMARY KEY (`kitID`);

--
-- Indexes for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`odrID`);

--
-- Indexes for table `tbl_pages`
--
ALTER TABLE `tbl_pages`
  ADD PRIMARY KEY (`pageID`);

--
-- Indexes for table `tbl_prints`
--
ALTER TABLE `tbl_prints`
  ADD PRIMARY KEY (`prtID`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`prodID`);

--
-- Indexes for table `tbl_product_reports`
--
ALTER TABLE `tbl_product_reports`
  ADD PRIMARY KEY (`prID`);

--
-- Indexes for table `tbl_reservations`
--
ALTER TABLE `tbl_reservations`
  ADD PRIMARY KEY (`resID`);

--
-- Indexes for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  ADD PRIMARY KEY (`rmID`);

--
-- Indexes for table `tbl_rooms_discount`
--
ALTER TABLE `tbl_rooms_discount`
  ADD PRIMARY KEY (`disID`);

--
-- Indexes for table `tbl_room_discounts`
--
ALTER TABLE `tbl_room_discounts`
  ADD PRIMARY KEY (`disrID`);

--
-- Indexes for table `tbl_room_images`
--
ALTER TABLE `tbl_room_images`
  ADD PRIMARY KEY (`rmiID`),
  ADD KEY `idx_rmiRoomID` (`rmiRoomID`),
  ADD KEY `idx_rmiRoomStatus` (`rmiRoomID`,`rmiStatus`);

--
-- Indexes for table `tbl_room_stays`
--
ALTER TABLE `tbl_room_stays`
  ADD PRIMARY KEY (`stayID`),
  ADD KEY `idx_stay_room_date_status` (`stayRoomID`,`stayDate`,`stayStatus`),
  ADD KEY `idx_stay_date` (`stayDate`);

--
-- Indexes for table `tbl_room_stay_orders`
--
ALTER TABLE `tbl_room_stay_orders`
  ADD PRIMARY KEY (`rsoID`),
  ADD KEY `idx_room_stay_orders_stay` (`rsoStayID`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`SeID`);

--
-- Indexes for table `tbl_tables`
--
ALTER TABLE `tbl_tables`
  ADD PRIMARY KEY (`tbID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`urID`);

--
-- Indexes for table `tbl_website_settings`
--
ALTER TABLE `tbl_website_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `ctID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49003;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `catID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_checkin`
--
ALTER TABLE `tbl_checkin`
  MODIFY `ckID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_cms_homepage`
--
ALTER TABLE `tbl_cms_homepage`
  MODIFY `homeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_discounts`
--
ALTER TABLE `tbl_discounts`
  MODIFY `disID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_kitchen`
--
ALTER TABLE `tbl_kitchen`
  MODIFY `kitID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36377;

--
-- AUTO_INCREMENT for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  MODIFY `logID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `odrID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_pages`
--
ALTER TABLE `tbl_pages`
  MODIFY `pageID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_prints`
--
ALTER TABLE `tbl_prints`
  MODIFY `prtID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `prodID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `tbl_product_reports`
--
ALTER TABLE `tbl_product_reports`
  MODIFY `prID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_reservations`
--
ALTER TABLE `tbl_reservations`
  MODIFY `resID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  MODIFY `rmID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_rooms_discount`
--
ALTER TABLE `tbl_rooms_discount`
  MODIFY `disID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_room_discounts`
--
ALTER TABLE `tbl_room_discounts`
  MODIFY `disrID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_room_images`
--
ALTER TABLE `tbl_room_images`
  MODIFY `rmiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_room_stays`
--
ALTER TABLE `tbl_room_stays`
  MODIFY `stayID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_room_stay_orders`
--
ALTER TABLE `tbl_room_stay_orders`
  MODIFY `rsoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `SeID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_tables`
--
ALTER TABLE `tbl_tables`
  MODIFY `tbID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `urID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

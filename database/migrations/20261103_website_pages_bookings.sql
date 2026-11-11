-- Website CMS pages and public booking requests. Safe to run once; creates tables if missing.
-- The base POS dump already defines tbl_pages using snake_case columns.
-- Create that legacy shape only if the table is absent, then migrate it to the requested fields.
CREATE TABLE IF NOT EXISTS `tbl_pages` (
 `page_id` int(11) NOT NULL AUTO_INCREMENT,
 `page_title` varchar(255) NOT NULL,
 `page_slug` varchar(190) NOT NULL,
 `page_content` longtext DEFAULT NULL,
 `page_status` tinyint(1) NOT NULL DEFAULT 0,
 `created_at` datetime NOT NULL,
 `updated_at` datetime NOT NULL,
 PRIMARY KEY (`page_id`), UNIQUE KEY `uq_tbl_pages_slug` (`page_slug`), KEY `idx_tbl_pages_status` (`page_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Run this migration once. Existing rows and slugs are preserved.
ALTER TABLE `tbl_pages`
 CHANGE `page_id` `pageID` int(11) NOT NULL AUTO_INCREMENT,
 CHANGE `page_title` `pageTitle` varchar(255) NOT NULL,
 CHANGE `page_slug` `pageSlug` varchar(190) NOT NULL,
 CHANGE `page_content` `pageContent` longtext DEFAULT NULL,
 CHANGE `page_status` `pageStatus` tinyint(1) NOT NULL DEFAULT 0,
 CHANGE `created_at` `pageDateCreated` datetime NOT NULL,
 CHANGE `updated_at` `pageLastModified` datetime NOT NULL,
 ADD COLUMN `pageCSS` longtext DEFAULT NULL AFTER `pageContent`,
 ADD COLUMN `pageJS` longtext DEFAULT NULL AFTER `pageCSS`;


CREATE TABLE IF NOT EXISTS `tbl_website_bookings` (
 `bookingID` int(11) NOT NULL AUTO_INCREMENT,
 `bookingRoomID` int(11) NOT NULL,
 `bookingGuestName` varchar(255) NOT NULL,
 `bookingGuestEmail` varchar(255) NOT NULL,
 `bookingGuestPhone` varchar(80) DEFAULT NULL,
 `bookingGuestCount` int(11) NOT NULL DEFAULT 1,
 `bookingCheckIn` date NOT NULL,
 `bookingCheckOut` date NOT NULL,
 `bookingMessage` text DEFAULT NULL,
 `bookingStatus` enum('pending','confirmed','rejected','cancelled') NOT NULL DEFAULT 'pending',
 `bookingCreatedAt` datetime NOT NULL,
 PRIMARY KEY (`bookingID`), KEY `idx_booking_room_dates` (`bookingRoomID`,`bookingCheckIn`,`bookingCheckOut`), KEY `idx_booking_status` (`bookingStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

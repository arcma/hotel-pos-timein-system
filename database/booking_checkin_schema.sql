-- Booking/check-in unified tables for the CodeIgniter POS.
-- Run once against the existing arseniossilay database.

CREATE TABLE IF NOT EXISTS `tbl_room_discounts` (
  `disrID` INT(10) NOT NULL AUTO_INCREMENT,
  `disrName` VARCHAR(255) DEFAULT NULL,
  `disrPercent` INT(3) DEFAULT NULL,
  `disrDate_created` DATETIME DEFAULT NULL,
  `disrDate_modified` DATETIME DEFAULT NULL,
  `disrStatus` INT(1) DEFAULT 1,
  PRIMARY KEY (`disrID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_checkin` (
  `ckID` INT(10) NOT NULL AUTO_INCREMENT,
  `ckRoomID` INT(10) NOT NULL,
  `ckDate` DATE NOT NULL,
  `ckDays` INT NOT NULL DEFAULT 1,
  `ckDateCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ckPaymentStatus` INT(1) DEFAULT NULL,
  `ckGuestName` VARCHAR(255) DEFAULT NULL,
  `ckGuestEmail` VARCHAR(255) DEFAULT NULL,
  `ckGuestPhone` VARCHAR(50) DEFAULT NULL,
  `ckMessage` TEXT DEFAULT NULL,
  `ckOtherCharges` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `ckFixDiscount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `ckRoomDiscountID` INT(10) DEFAULT NULL,
  `ckPrice` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `ckDiscountAmount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `ckCheckInType` TINYINT NOT NULL DEFAULT 1 COMMENT '1=Check-in, 2=Reservation, 3=Completed',
  `ckReservationDate` DATETIME DEFAULT NULL,
  `ckPaymentType` VARCHAR(20) DEFAULT NULL COMMENT 'cash or card',
  PRIMARY KEY (`ckID`),
  KEY `idx_ck_room_date_type` (`ckRoomID`,`ckDate`,`ckCheckInType`),
  KEY `idx_ck_date_type` (`ckDate`,`ckCheckInType`),
  KEY `idx_ck_room_discount` (`ckRoomDiscountID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copy legacy room discounts into the new table without duplicating IDs.
INSERT IGNORE INTO `tbl_room_discounts`
  (`disrID`,`disrName`,`disrPercent`,`disrDate_created`,`disrDate_modified`,`disrStatus`)
SELECT `disID`,`disName`,`disPercent`,`disDate_created`,`disDate_modified`,`disStatus`
FROM `tbl_rooms_discount`;

-- Migrate existing reservations to the unified table. Safe to rerun: existing
-- room/date reservations are skipped.
INSERT INTO `tbl_checkin`
  (`ckRoomID`,`ckDate`,`ckDateCreated`,`ckPaymentStatus`,`ckGuestName`,`ckOtherCharges`,
   `ckFixDiscount`,`ckRoomDiscountID`,`ckPrice`,`ckDiscountAmount`,`ckCheckInType`,
   `ckReservationDate`,`ckPaymentType`)
SELECT r.`resRoomID`,r.`resDate`,TIMESTAMP(r.`resDate_Created`),NULL,r.`resGuestName`,
       r.`resOtherCharges`,0,rd.`disrID`,
       ROUND(r.`resPrice`-r.`resDiscountAmount`+r.`resOtherCharges`,2),r.`resDiscountAmount`,2,
       TIMESTAMP(r.`resDate_Created`),r.`resPaymentType`
FROM `tbl_reservations` r
LEFT JOIN `tbl_room_discounts` rd ON rd.`disrID`=r.`resRoomDiscountID`
WHERE NOT EXISTS (
  SELECT 1 FROM `tbl_checkin` c
  WHERE c.`ckRoomID`=r.`resRoomID` AND c.`ckDate`=r.`resDate` AND c.`ckCheckInType`=2
);

-- Run once against the existing database.
ALTER TABLE `tbl_checkin`
  ADD COLUMN `ckDays` INT NOT NULL DEFAULT 1 AFTER `ckDate`,
  ADD COLUMN `ckGuestEmail` VARCHAR(255) DEFAULT NULL AFTER `ckGuestName`,
  ADD COLUMN `ckGuestPhone` VARCHAR(50) DEFAULT NULL AFTER `ckGuestEmail`,
  ADD COLUMN `ckMessage` TEXT DEFAULT NULL AFTER `ckGuestPhone`;

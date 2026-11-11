-- Rename the old reservation timestamp column to the requested ckReservationDate.
-- Run once against the existing database if tbl_checkin currently has chReservationDate.
ALTER TABLE `tbl_checkin` CHANGE COLUMN `chReservationDate` `ckReservationDate` DATETIME DEFAULT NULL;

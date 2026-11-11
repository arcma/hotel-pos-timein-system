-- Run once on an existing POS database.
ALTER TABLE `tbl_kitchen`
  ADD COLUMN `kitPrinted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `kitTimeDelivered`,
  ADD COLUMN `kitTimePrinted` DATETIME NULL DEFAULT NULL AFTER `kitPrinted`;

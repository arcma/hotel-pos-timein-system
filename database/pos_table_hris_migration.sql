-- Run this migration once against the existing POS database.
-- Rename the HRIS attendance table while preserving its data.
RENAME TABLE `tbl_user_check_in` TO `tbl_hris`;

-- Add table category: 1 = Restaurant, 2 = Cafe Shop, 3 = Hotel.
ALTER TABLE `tbl_tables`
  ADD COLUMN `tbCategory` INT(11) NOT NULL DEFAULT 1 AFTER `tbName`;

-- Existing tables default to Restaurant. Update categories manually as needed.

-- Remove the obsolete table-number column now that the table name is the identifier shown to staff.
ALTER TABLE `tbl_tables` DROP COLUMN `tbNumber`;

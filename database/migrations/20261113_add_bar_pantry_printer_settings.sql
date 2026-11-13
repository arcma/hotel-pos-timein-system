-- Add bar and pantry printer IP settings to the existing key/value settings table.
-- Safe to run more than once.
INSERT INTO `tbl_settings` (`SeName`, `SeSettings`, `SeLastModified`)
SELECT 'bar_ip', '', NULL
WHERE NOT EXISTS (SELECT 1 FROM `tbl_settings` WHERE `SeName` = 'bar_ip');

INSERT INTO `tbl_settings` (`SeName`, `SeSettings`, `SeLastModified`)
SELECT 'pantry_ip', '', NULL
WHERE NOT EXISTS (SELECT 1 FROM `tbl_settings` WHERE `SeName` = 'pantry_ip');

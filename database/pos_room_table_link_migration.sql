-- Schema prerequisite for existing installations:
-- If tbl_tables.tbRoomID does not already exist, run this statement once:
-- ALTER TABLE `tbl_tables` ADD COLUMN `tbRoomID` INT(15) NULL DEFAULT NULL AFTER `tbCategory`;

-- Create linked Hotel tables for existing rooms that do not have one yet.
-- Safe to re-run; rows with an existing tbRoomID link are skipped.
INSERT INTO `tbl_tables` (`tbName`, `tbCategory`, `tbRoomID`, `tbSortOrder`, `tbDate_created`, `tbDate_modified`, `tbType`, `tbStatus`)
SELECT r.`rmTitle`, 3, r.`rmID`,
       (SELECT COALESCE(MAX(t2.`tbSortOrder`), 0) FROM `tbl_tables` t2) +
       (SELECT COUNT(*) FROM `tbl_rooms` r2
        WHERE r2.`rmID` <= r.`rmID`
          AND NOT EXISTS (SELECT 1 FROM `tbl_tables` t3 WHERE t3.`tbRoomID` = r2.`rmID`)),
       NOW(), NOW(), 1, r.`rmStatus`
FROM `tbl_rooms` r
WHERE NOT EXISTS (SELECT 1 FROM `tbl_tables` t WHERE t.`tbRoomID` = r.`rmID`);

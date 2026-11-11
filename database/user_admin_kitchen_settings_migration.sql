-- Run once on an existing database. Set the actual passwords in SeSettings after inserting these keys.
INSERT INTO tbl_settings (SeName, SeSettings)
SELECT 'userAdmin', '' WHERE NOT EXISTS (SELECT 1 FROM tbl_settings WHERE SeName = 'userAdmin');

INSERT INTO tbl_settings (SeName, SeSettings)
SELECT 'userKitchen', '' WHERE NOT EXISTS (SELECT 1 FROM tbl_settings WHERE SeName = 'userKitchen');

-- Example after choosing passwords (replace the values before running):
-- UPDATE tbl_settings SET SeSettings = 'REPLACE_WITH_ADMIN_PASSWORD' WHERE SeName = 'userAdmin';
-- UPDATE tbl_settings SET SeSettings = 'REPLACE_WITH_KITCHEN_PASSWORD' WHERE SeName = 'userKitchen';

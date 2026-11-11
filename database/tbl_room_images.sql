CREATE TABLE IF NOT EXISTS `tbl_room_images` (
  `rmiID` int(11) NOT NULL AUTO_INCREMENT,
  `rmiRoomID` int(11) NOT NULL,
  `rmiPath` varchar(500) NOT NULL,
  `rmiStatus` tinyint(1) NOT NULL DEFAULT 1,
  `rmiDateCreated` datetime NOT NULL,
  PRIMARY KEY (`rmiID`),
  KEY `idx_rmiRoomID` (`rmiRoomID`),
  KEY `idx_rmiRoomStatus` (`rmiRoomID`,`rmiStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

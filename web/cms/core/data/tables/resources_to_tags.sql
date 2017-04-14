CREATE TABLE `__PREFIX__resources_to_tags` (
  `resourceID` int(10) NOT NULL DEFAULT '0',
  `tagID` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`resourceID`,`tagID`)
) DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED
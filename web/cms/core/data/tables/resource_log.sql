CREATE TABLE `__PREFIX__resource_log` (
  `logID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appID` char(32) NOT NULL DEFAULT 'content',
  `itemFK` char(32) NOT NULL DEFAULT 'itemRowID',
  `itemRowID` int(10) unsigned NOT NULL DEFAULT '0',
  `resourceID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`logID`),
  UNIQUE KEY `idx_uni` (`appID`,`itemFK`,`itemRowID`,`resourceID`),
  KEY `idx_resource` (`resourceID`),
  KEY `idx_fk` (`itemFK`,`itemRowID`)
) DEFAULT CHARSET=utf8
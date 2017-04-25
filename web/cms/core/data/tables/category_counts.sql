CREATE TABLE `__PREFIX__category_counts` (
  `countID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catID` int(10) unsigned NOT NULL,
  `countType` char(64) NOT NULL DEFAULT '',
  `countValue` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`countID`),
  KEY `idx_cat` (`catID`),
  KEY `idx_cat_type` (`countType`,`catID`)
) DEFAULT CHARSET=utf8
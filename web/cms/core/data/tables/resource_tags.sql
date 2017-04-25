CREATE TABLE `__PREFIX__resource_tags` (
  `tagID` int(10) NOT NULL AUTO_INCREMENT,
  `tagTitle` varchar(255) NOT NULL DEFAULT '',
  `tagSlug` varchar(255) NOT NULL DEFAULT '',
  `tagCount` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagID`)
) DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC
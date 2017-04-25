CREATE TABLE `__PREFIX__navigation` (
  `groupID` int(10) NOT NULL AUTO_INCREMENT,
  `groupTitle` varchar(255) NOT NULL DEFAULT '',
  `groupSlug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`groupID`)
) DEFAULT CHARSET=utf8
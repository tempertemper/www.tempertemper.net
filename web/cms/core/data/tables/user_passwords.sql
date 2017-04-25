CREATE TABLE `__PREFIX__user_passwords` (
  `passwordID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `userPassword` varchar(255) NOT NULL DEFAULT '',
  `passwordLastUsed` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  PRIMARY KEY (`passwordID`),
  KEY `idx_user` (`userID`)
) DEFAULT CHARSET=utf8
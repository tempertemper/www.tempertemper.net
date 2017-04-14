CREATE TABLE `__PREFIX__categories` (
  `catID` int(10) NOT NULL AUTO_INCREMENT,
  `setID` int(10) unsigned NOT NULL,
  `catParentID` int(10) unsigned NOT NULL DEFAULT '0',
  `catTitle` char(64) NOT NULL DEFAULT '',
  `catSlug` char(64) NOT NULL DEFAULT '',
  `catPath` char(255) NOT NULL DEFAULT '',
  `catDisplayPath` char(255) NOT NULL DEFAULT '',
  `catOrder` int(10) unsigned NOT NULL DEFAULT '0',
  `catTreePosition` char(255) NOT NULL DEFAULT '000',
  `catDynamicFields` text NOT NULL,
  PRIMARY KEY (`catID`),
  KEY `idx_set` (`setID`)
) DEFAULT CHARSET=utf8
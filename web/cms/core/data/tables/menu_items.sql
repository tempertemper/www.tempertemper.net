CREATE TABLE `__PREFIX__menu_items` (
  `itemID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentID` int(10) unsigned NOT NULL DEFAULT '0',
  `itemType` enum('menu','app','link') NOT NULL DEFAULT 'app',
  `itemOrder` int(10) unsigned NOT NULL DEFAULT '1',
  `itemTitle` char(64) NOT NULL DEFAULT 'Unnamed item',
  `itemValue` char(255) DEFAULT NULL,
  `itemPersists` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `itemActive` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `privID` int(10) DEFAULT NULL,
  `userID` int(10) unsigned NOT NULL DEFAULT '0',
  `itemInternal` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemID`)
) DEFAULT CHARSET=utf8
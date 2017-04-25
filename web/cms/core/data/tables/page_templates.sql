CREATE TABLE `__PREFIX__page_templates` (
  `templateID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templateTitle` varchar(255) NOT NULL DEFAULT '',
  `templatePath` varchar(255) NOT NULL DEFAULT '',
  `optionsPageID` int(10) unsigned NOT NULL DEFAULT '0',
  `templateReference` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `templateNavGroups` varchar(255) DEFAULT '',
  PRIMARY KEY (`templateID`)
) DEFAULT CHARSET=utf8
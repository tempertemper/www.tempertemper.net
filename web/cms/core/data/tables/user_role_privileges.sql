CREATE TABLE `__PREFIX__user_role_privileges` (
  `roleID` int(10) unsigned NOT NULL,
  `privID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`roleID`,`privID`)
) DEFAULT CHARSET=utf8
CREATE TABLE `__PREFIX__scheduled_tasks` (
  `taskID` int(10) NOT NULL AUTO_INCREMENT,
  `taskStartTime` datetime NOT NULL,
  `taskEndTime` datetime DEFAULT NULL,
  `taskApp` varchar(64) NOT NULL DEFAULT '',
  `taskKey` varchar(64) DEFAULT NULL,
  `taskResult` enum('OK','WARNING','FAILED') NOT NULL DEFAULT 'FAILED',
  `taskMessage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`taskID`),
  KEY `idx_app` (`taskApp`)
) DEFAULT CHARSET=utf8
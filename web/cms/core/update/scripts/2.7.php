<?php
	$sql = "
      CREATE TABLE IF NOT EXISTS `__PREFIX__resource_log` (
        `logID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `appID` char(32) NOT NULL DEFAULT 'content',
        `itemFK` char(32) NOT NULL DEFAULT 'itemRowID',
        `itemRowID` int(10) unsigned NOT NULL DEFAULT '0',
        `resourceID` int(10) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`logID`),
        KEY `idx_resource` (`resourceID`),
        KEY `idx_fk` (`itemFK`,`itemRowID`),
        UNIQUE KEY `idx_uni` (`appID`,`itemFK`,`itemRowID`,`resourceID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      ALTER TABLE `__PREFIX__content_regions` ADD `regionUpdated` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP  AFTER `regionEditRoles`;

      ALTER TABLE `__PREFIX__content_items` ADD `itemUpdated` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP  AFTER `itemSearch`;

      ALTER TABLE `__PREFIX__content_items` ADD `itemUpdatedBy` CHAR(32)  NOT NULL  DEFAULT ''  AFTER `itemUpdated`;
     
      ALTER TABLE `__PREFIX__pages` ADD `pageTemplate` CHAR(255)  NOT NULL  DEFAULT ''  AFTER `pageAttributeTemplate`;

      ALTER TABLE `__PREFIX__pages` ADD `templateID` INT(10)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `pageTemplate`;

      ALTER TABLE `__PREFIX__pages` ADD `pageSubpageTemplates` VARCHAR(255)  NOT NULL  DEFAULT ''  AFTER `templateID`;

      ALTER TABLE `__PREFIX__pages` ADD `pageCollections` VARCHAR(255)  NOT NULL  DEFAULT ''  AFTER `pageSubpageTemplates`;

      CREATE TABLE IF NOT EXISTS `__PREFIX__category_counts` (
        `countID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `catID` int(10) unsigned NOT NULL,
        `countType` char(64) NOT NULL DEFAULT '',
        `countValue` int(10) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`countID`),
        KEY `idx_cat` (`catID`),
        KEY `idx_cat_type` (`countType`,`catID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      ALTER TABLE `__PREFIX__categories` CHANGE `catDisplayPath` `catDisplayPath` CHAR(255) NOT NULL DEFAULT '';

      -- DROP INDEX `idx_uni` ON `__PREFIX__resource_log`;
      
      ALTER TABLE `__PREFIX__resource_log` ADD UNIQUE INDEX `idx_uni` (`appID`, `itemFK`, `itemRowID`, `resourceID`);

      ALTER TABLE `__PREFIX__users` ADD `userPasswordToken` CHAR(255)  NOT NULL  DEFAULT 'expired'  AFTER `userMasterAdmin`;

      ALTER TABLE `__PREFIX__users` ADD `userPasswordTokenExpires` DATETIME  NOT NULL  DEFAULT '2015-01-01 00:00:00'  AFTER `userPasswordToken`;

      CREATE TABLE IF NOT EXISTS `__PREFIX__user_passwords` (
        `passwordID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `userID` int(10) unsigned NOT NULL,
        `userPassword` varchar(255) NOT NULL DEFAULT '',
        `passwordLastUsed` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
        PRIMARY KEY (`passwordID`),
        KEY `idx_user` (`userID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      ALTER TABLE `__PREFIX__users` ADD `userLastFailedLogin` DATETIME  NULL  AFTER `userPasswordTokenExpires`;

      ALTER TABLE `__PREFIX__users` ADD `userFailedLoginAttempts` INT(0)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `userLastFailedLogin`;


      ALTER TABLE `__PREFIX__resources` ADD INDEX `idx_list` (`resourceParentID`, `resourceKey`, `resourceAWOL`);


    ";

    if (PERCH_RUNWAY) {
      $sql .= "

      CREATE TABLE IF NOT EXISTS `__PREFIX__collections` (
        `collectionID` int(10) NOT NULL AUTO_INCREMENT,
        `collectionKey` char(64) NOT NULL DEFAULT '',
        `collectionOrder` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `collectionTemplate` char(255) NOT NULL DEFAULT '',
        `collectionOptions` text NOT NULL,
        `collectionSearchable` tinyint(1) unsigned NOT NULL DEFAULT '1',
        `collectionEditRoles` char(255) NOT NULL DEFAULT '*',
        `collectionUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `collectionInAppMenu` tinyint(1) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`collectionID`),
        KEY `idx_key` (`collectionKey`),
        KEY `idx_appmenu` (`collectionInAppMenu`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__collection_revisions` (
        `itemID` int(10) unsigned NOT NULL,
        `collectionID` int(10) unsigned NOT NULL,
        `itemOrder` int(10) unsigned DEFAULT '1000',
        `itemRev` int(10) unsigned NOT NULL,
        `itemLatestRev` int(10) unsigned NOT NULL,
        `itemCreated` datetime NOT NULL DEFAULT '2014-02-21 06:53:00',
        `itemCreatedBy` char(32) NOT NULL DEFAULT '',
        PRIMARY KEY (`itemID`),
        KEY `idx_order` (`itemOrder`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__collection_items` (
        `itemRowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `itemID` int(10) unsigned NOT NULL,
        `itemRev` int(10) unsigned NOT NULL DEFAULT '0',
        `collectionID` int(10) unsigned NOT NULL,
        `itemJSON` mediumtext NOT NULL,
        `itemSearch` mediumtext NOT NULL,
        `itemUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `itemUpdatedBy` char(32) NOT NULL DEFAULT '',
        PRIMARY KEY (`itemRowID`),
        KEY `idx_item` (`itemID`),
        KEY `idx_rev` (`itemRev`),
        KEY `idx_collection` (`collectionID`),
        KEY `idx_regrev` (`itemID`,`collectionID`,`itemRev`),
        FULLTEXT KEY `idx_search` (`itemSearch`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__collection_index` (
        `indexID` int(10) NOT NULL AUTO_INCREMENT,
        `itemID` int(10) NOT NULL DEFAULT '0',
        `collectionID` int(10) NOT NULL DEFAULT '0',
        `itemRev` int(10) NOT NULL DEFAULT '0',
        `indexKey` char(64) NOT NULL DEFAULT '-',
        `indexValue` char(255) NOT NULL DEFAULT '',
        PRIMARY KEY (`indexID`),
        KEY `idx_key` (`indexKey`),
        KEY `idx_val` (`indexValue`),
        KEY `idx_rev` (`itemRev`),
        KEY `idx_item` (`itemID`),
        KEY `idx_keyval` (`indexKey`,`indexValue`),
        KEY `idx_colrev` (`collectionID`,`itemRev`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__page_routes` (
        `routeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `pageID` int(10) unsigned NOT NULL DEFAULT '0',
        `routePattern` char(255) NOT NULL DEFAULT '',
        `routeRegExp` char(255) NOT NULL DEFAULT '',
        `routeOrder` int(10) unsigned NOT NULL,
        PRIMARY KEY (`routeID`),
        KEY `idx_page` (`pageID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__backup_runs` (
        `runID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `planID` int(10) unsigned NOT NULL,
        `runDateTime` datetime NOT NULL,
        `runType` enum('db','resources') NOT NULL DEFAULT 'resources',
        `runResult` enum('OK','FAILED','IN PROGRESS') NOT NULL DEFAULT 'OK',
        `runMessage` char(255) NOT NULL DEFAULT '',
        `runDbFile` char(255) NOT NULL,
        PRIMARY KEY (`runID`),
        KEY `idx_plan` (`planID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__backup_resources` (
        `planID` int(10) unsigned NOT NULL,
        `resourceID` int(10) unsigned NOT NULL,
        `runID` int(10) unsigned NOT NULL,
        PRIMARY KEY (`planID`,`resourceID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__backup_plans` (
        `planID` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `planTitle` char(255) NOT NULL DEFAULT '',
        `planRole` enum('all','db') NOT NULL DEFAULT 'all',
        `planCreated` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
        `planCreatedBy` char(32) NOT NULL DEFAULT '',
        `planUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `planUpdatedBy` char(32) NOT NULL DEFAULT '',
        `planActive` tinyint(1) unsigned NOT NULL DEFAULT '1',
        `planDynamicFields` text NOT NULL,
        `planFrequency` int(10) unsigned NOT NULL DEFAULT '24',
        `planBucket` char(16) NOT NULL DEFAULT '',
        PRIMARY KEY (`planID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
      VALUES ('content.regions.revert','Roll back regions',3);

      CREATE TABLE IF NOT EXISTS `__PREFIX__page_routes` (
        `routeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `pageID` int(10) unsigned NOT NULL DEFAULT '0',
        `routePattern` char(255) NOT NULL DEFAULT '',
        `routeRegExp` char(255) NOT NULL DEFAULT '',
        `routeOrder` int(10) unsigned NOT NULL,
        PRIMARY KEY (`routeID`),
        KEY `idx_page` (`pageID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      ALTER TABLE `__PREFIX__collections` ADD INDEX `idx_appmenu` (`collectionInAppMenu`);

      CREATE TABLE IF NOT EXISTS `__PREFIX__backup_runs` (
        `runID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `planID` int(10) unsigned NOT NULL,
        `runDateTime` datetime NOT NULL,
        `runType` enum('db','resources') NOT NULL DEFAULT 'resources',
        `runResult` enum('OK','FAILED','IN PROGRESS') NOT NULL DEFAULT 'OK',
        `runMessage` char(255) NOT NULL DEFAULT '',
        `runDbFile` char(255) NOT NULL,
        PRIMARY KEY (`runID`),
        KEY `idx_plan` (`planID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__backup_resources` (
        `planID` int(10) unsigned NOT NULL,
        `resourceID` int(10) unsigned NOT NULL,
        `runID` int(10) unsigned NOT NULL,
        PRIMARY KEY (`planID`,`resourceID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `__PREFIX__backup_plans` (
        `planID` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `planTitle` char(255) NOT NULL DEFAULT '',
        `planRole` enum('all','db') NOT NULL DEFAULT 'all',
        `planCreated` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
        `planCreatedBy` char(32) NOT NULL DEFAULT '',
        `planUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `planUpdatedBy` char(32) NOT NULL DEFAULT '',
        `planActive` tinyint(1) unsigned NOT NULL DEFAULT '1',
        `planDynamicFields` text NOT NULL,
        `planFrequency` int(10) unsigned NOT NULL DEFAULT '24',
        `planBucket` char(16) NOT NULL DEFAULT '',
        PRIMARY KEY (`planID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    ALTER TABLE `__PREFIX__collection_revisions` ADD `itemSearchable` TINYINT(1)  UNSIGNED  NOT NULL  DEFAULT '1'  AFTER `itemCreatedBy`;

    ALTER TABLE `__PREFIX__collection_revisions` ADD INDEX `idx_searchable` (`itemSearchable`);
    
    ALTER TABLE `__PREFIX__collection_revisions` ADD INDEX `idx_collection` (`collectionID`);


      ";

      /*
      UPDATE `__PREFIX__settings` SET `settingValue` = 'rgb(54,54,54)' WHERE `settingID` = 'headerColour' LIMIT 1;
      UPDATE `__PREFIX__settings` SET `settingValue` = 'dark' WHERE `settingID` = 'headerScheme';
       */
    }





	$sql = str_replace('__PREFIX__', PERCH_DB_PREFIX, $sql);
	$queries = explode(';', $sql);
  if (PerchUtil::count($queries) > 0) {
      foreach($queries as $query) {
          $query = trim($query);
          if ($query != '') {
              $DB->execute($query);
              if ($DB->errored && strpos($DB->error_msg, 'Duplicate')===false) { 
                 echo '<li class="progress-item progress-alert">'.PerchUI::icon('core/face-pain').' '.PerchUtil::html(PerchLang::get('The following error occurred:')) .'</li>';
                  echo '<li class="failure"><code class="sql">'.PerchUtil::html($query).'</code></li>';
                  echo '<li class="failure"><code>'.PerchUtil::html($DB->error_msg).'</code></p></li>';
                  $errors = true;
              }
          }
      }
  }


  // Resource logging - failsafes
  if (PerchUtil::count($DB->get_rows('SHOW TABLES LIKE \''.PERCH_DB_PREFIX.'resource_log\''))==0) {
      $sql = "CREATE TABLE IF NOT EXISTS `__PREFIX__resource_log` (
              `logID` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `appID` char(32) NOT NULL DEFAULT 'content',
              `itemFK` char(32) NOT NULL DEFAULT 'itemRowID',
              `itemRowID` int(10) unsigned NOT NULL DEFAULT '0',
              `resourceID` int(10) unsigned NOT NULL DEFAULT '0',
              PRIMARY KEY (`logID`),
              KEY `idx_resource` (`resourceID`),
              KEY `idx_fk` (`itemFK`,`itemRowID`),
              UNIQUE KEY `idx_uni` (`appID`,`itemFK`,`itemRowID`,`resourceID`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
      $DB->execute($query);
  }

  // Is there anything in the resource log table?
  if ($DB->get_count('SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'resource_log')==0) {
      $Resources = new PerchResources();
      $Resources->log_unlogged_resources_for_safety();
  }
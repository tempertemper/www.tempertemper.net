<?php
	$sql = "
      CREATE TABLE IF NOT EXISTS `__PREFIX__menu_items` (
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
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    ";

    if (PERCH_RUNWAY) {
      $sql .= "
        ALTER TABLE `__PREFIX__page_routes` ADD `templateID` INT(10)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `pageID`;
        
        ALTER TABLE `__PREFIX__page_routes` ADD `templatePath` CHAR(255)  NOT NULL  DEFAULT ''  AFTER `routeOrder`;
        
        ALTER TABLE `__PREFIX__page_routes` ADD INDEX `idx_template` (`templateID`);
      
        CREATE TABLE IF NOT EXISTS `__PREFIX__content_locks` (
          `lockID` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `contentKey` char(64) NOT NULL DEFAULT '',
          `userID` int(10) unsigned DEFAULT NULL,
          `lockTime` datetime DEFAULT NULL,
          PRIMARY KEY (`lockID`),
          KEY `idx_key` (`contentKey`),
          KEY `idx_ku` (`contentKey`,`userID`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

        CREATE TABLE IF NOT EXISTS `__PREFIX__user_role_buckets` (
          `urbID` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `roleID` int(10) unsigned NOT NULL DEFAULT '0',
          `bucket` char(64) NOT NULL DEFAULT '',
          `roleSelect` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `roleInsert` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `roleUpdate` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `roleDelete` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `roleDefault` tinyint(1) unsigned NOT NULL DEFAULT '1',
          PRIMARY KEY (`urbID`),
          KEY `idx_rolebucket` (`roleID`,`bucket`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      ";


    }

    if ($DB->get_count('SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'menu_items')==0) {
      $sql .= "
            INSERT INTO `__PREFIX__menu_items` (`itemID`, `parentID`, `itemType`, `itemOrder`, `itemTitle`, `itemValue`, `itemPersists`, `itemActive`, `privID`, `userID`, `itemInternal`)
            VALUES
              (1,0,'menu',1,'My Site',NULL,1,1,NULL,0,0),
              (2,0,'menu',2,'Organise',NULL,1,1,NULL,0,0),
              (3,1,'app',1,'Pages','content',0,1,NULL,0,0),
              (4,2,'app',1,'Categories','categories',0,1,22,0,0),
              (5,2,'app',2,'Assets','assets',0,1,NULL,0,0),
              (7,0,'app',1,'Settings','settings',1,0,NULL,0,1),
              (8,0,'app',1,'Users','users',1,0,NULL,0,1),
              (9,0,'app',1,'Help','help',1,0,NULL,0,1); ";
    }


    if ($DB->get_count('SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'user_privileges WGERE privKey="assets.delete"')==0) {
        $sql .= "INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
                  VALUES ('assets.delete','Delete assets',3);";
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


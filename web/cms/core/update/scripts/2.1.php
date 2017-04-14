<?php

	$republish = false;

	$sql = "SHOW TABLES LIKE '__PREFIX__content_index'";
	$sql = str_replace('__PREFIX__', PERCH_DB_PREFIX, $sql);
	$row = $DB->get_row($sql);
	if ($row==false) {	
		$republish = true;
	}

	// CREATE NEW TABLES
	$sql = "CREATE TABLE IF NOT EXISTS `__PREFIX__content_index` (
			  `indexID` int(10) NOT NULL AUTO_INCREMENT,
			  `itemID` int(10) NOT NULL DEFAULT '0',
			  `regionID` int(10) NOT NULL DEFAULT '0',
			  `pageID` int(10) NOT NULL DEFAULT '0',
			  `itemRev` int(10) NOT NULL DEFAULT '0',
			  `indexKey` char(64) NOT NULL DEFAULT '-',
			  `indexValue` char(255) NOT NULL DEFAULT '',
			  PRIMARY KEY (`indexID`),
			  KEY `idx_key` (`indexKey`),
			  KEY `idx_val` (`indexValue`),
			  KEY `idx_rev` (`itemRev`),
			  KEY `idx_item` (`itemID`),
			  KEY `idx_keyval` (`indexKey`,`indexValue`),
			  KEY `idx_regrev` (`regionID`,`itemRev`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;

		CREATE TABLE IF NOT EXISTS `__PREFIX__resources` (
		  `resourceID` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `resourceApp` char(32) NOT NULL DEFAULT 'content',
		  `resourceBucket` char(16) NOT NULL DEFAULT 'default',
		  `resourceFile` char(255) NOT NULL DEFAULT '',
		  `resourceKey` enum('orig','thumb') DEFAULT NULL,
		  `resourceParentID` int(10) NOT NULL DEFAULT '0',
		  `resourceType` char(4) NOT NULL DEFAULT '',
		  PRIMARY KEY (`resourceID`),
		  UNIQUE KEY `idx_file` (`resourceBucket`,`resourceFile`),
		  KEY `idx_app` (`resourceApp`),
		  KEY `idx_key` (`resourceKey`),
		  KEY `idx_type` (`resourceType`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;

		
		ALTER TABLE `__PREFIX__content_items` ADD INDEX `idx_regrev` USING BTREE (itemID, regionID, itemRev);

		ALTER TABLE `__PREFIX__content_items` ADD INDEX `idx_order` USING BTREE (itemOrder);
		
		ALTER TABLE `__PREFIX__content_regions` ADD INDEX `idx_key` USING BTREE (regionKey);
		
		ALTER TABLE `__PREFIX__content_regions` ADD INDEX `idx_path` USING BTREE (regionPage);

		ALTER TABLE `__PREFIX__page_templates` ADD `templateNavGroups` VARCHAR(255)  NULL  DEFAULT ''  AFTER `templateReference`;

		CREATE TABLE IF NOT EXISTS `__PREFIX__navigation` (
		  `groupID` int(10) NOT NULL AUTO_INCREMENT,
		  `groupTitle` varchar(255) NOT NULL DEFAULT '',
		  `groupSlug` varchar(255) NOT NULL DEFAULT '',
		  PRIMARY KEY (`groupID`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;

		CREATE TABLE IF NOT EXISTS `__PREFIX__navigation_pages` (
		  `navpageID` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `pageID` int(10) unsigned NOT NULL DEFAULT '0',
		  `groupID` int(10) unsigned NOT NULL DEFAULT '0',
		  `pageParentID` int(10) unsigned NOT NULL DEFAULT '0',
		  `pageOrder` int(10) unsigned NOT NULL DEFAULT '1',
		  `pageDepth` tinyint(10) unsigned NOT NULL,
		  `pageTreePosition` varchar(64) NOT NULL DEFAULT '',
		  PRIMARY KEY (`navpageID`),
		  KEY `idx_group` (`groupID`),
		  KEY `idx_page_group` (`pageID`,`groupID`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


		ALTER TABLE `__PREFIX__user_privileges` ADD UNIQUE INDEX `idx_key` (`privKey`);

		INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
		VALUES 
			('content.navgroups.configure','Configure navigation groups',7),
			('content.navgroups.create','Create navigation groups',8),
			('content.navgroups.delete','Delete navigation groups',9);

		INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
		VALUES ('content.pages.create.toplevel','Add new top-level pages',3);

		INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
		VALUES ('content.pages.delete.own','Delete pages they created themselves',4);
		
		INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
		VALUES ('content.templates.configure','Configure master pages',6);


		INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
		VALUES 
			('content.pages.republish','Republish pages', 12);

		ALTER TABLE `__PREFIX__pages` ADD `pageAccessTags` VARCHAR(255)  NOT NULL  DEFAULT ''  AFTER `pageNavOnly`;

		ALTER TABLE `__PREFIX__pages` ADD `pageCreatorID` INT(10)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `pageAccessTags`;

		ALTER TABLE `__PREFIX__pages` ADD `pageModified` DATETIME  NOT NULL DEFAULT '2014-01-01 00:00:00' AFTER `pageCreatorID`;

		ALTER TABLE `__PREFIX__pages` ADD `pageAttributes` TEXT  NOT NULL  AFTER `pageModified`;

		ALTER TABLE `__PREFIX__pages` ADD `pageAttributeTemplate` VARCHAR(255)  NOT NULL  DEFAULT 'default.html'  AFTER `pageAttributes`;

		INSERT INTO `__PREFIX__user_privileges` (`privKey`, `privTitle`, `privOrder`)
		VALUES ('content.pages.attributes','Edit page titles and attributes',6);
		

		CREATE TABLE IF NOT EXISTS `__PREFIX__user_role_privileges` (
		  `roleID` int(10) unsigned NOT NULL,
		  `privID` int(10) unsigned NOT NULL,
		  PRIMARY KEY (`roleID`,`privID`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;

	";

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

    if (!$errors && $republish) {
    	PerchUtil::debug('Republishing');
    	$Regions = new PerchContent_Regions();
    	$Regions->republish_all();
    }


<?php
	$sql = "

      ALTER TABLE `__PREFIX__content_regions` ADD `regionUpdated` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP  AFTER `regionEditRoles`;

      ALTER TABLE `__PREFIX__content_items` ADD `itemUpdated` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP  AFTER `itemSearch`;

      ALTER TABLE `__PREFIX__content_items` ADD `itemUpdatedBy` CHAR(32)  NOT NULL  DEFAULT ''  AFTER `itemUpdated`;
     
      ALTER TABLE `__PREFIX__pages` ADD `pageTemplate` CHAR(255)  NOT NULL  DEFAULT ''  AFTER `pageAttributeTemplate`;


      CREATE TABLE IF NOT EXISTS `__PREFIX__category_counts` (
        `countID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `catID` int(10) unsigned NOT NULL,
        `countType` char(64) NOT NULL DEFAULT '',
        `countValue` int(10) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`countID`),
        KEY `idx_cat` (`catID`),
        KEY `idx_cat_type` (`countType`,`catID`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;


    ";

    if (PERCH_RUNWAY) {
      $sql .= "

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


      ";
    }




	$sql = str_replace('__PREFIX__', PERCH_DB_PREFIX, $sql);
	$queries = explode(';', $sql);
    if (PerchUtil::count($queries) > 0) {
        foreach($queries as $query) {
            $query = trim($query);
            if ($query != '') {
                $DB->execute($query);
                if ($DB->errored && strpos($DB->error_msg, 'Duplicate')===false) { 
                    echo '<li class="icon failure error">'.PerchUtil::html(PerchLang::get('The following error occurred:')) .'</li>';
                    echo '<li class="failure"><code class="sql">'.PerchUtil::html($query).'</code></li>';
                    echo '<li class="failure"><code>'.PerchUtil::html($DB->error_msg).'</code></p></li>';
                    $errors = true;
                }
            }
        }
    }

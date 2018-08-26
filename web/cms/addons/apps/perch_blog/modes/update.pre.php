<?php

	$Posts = new PerchBlog_Posts($API);
	$posts = $Posts->all();
	if (PerchUtil::count($posts)==false) {
		$Settings->set('perch_blog_update', '5.6');
		PerchUtil::redirect($API->app_path());
	}


	$Paging = $API->get('Paging');
	$Paging->set_per_page(10);

	if ($Paging->is_first_page()) {

	    $UserPrivileges = $API->get('UserPrivileges');
	    $UserPrivileges->create_privilege('perch_blog', 'Access the blog');
	    $UserPrivileges->create_privilege('perch_blog.post.create', 'Create posts');
	    $UserPrivileges->create_privilege('perch_blog.post.delete', 'Delete posts');
	    $UserPrivileges->create_privilege('perch_blog.post.publish', 'Publish posts');
	    $UserPrivileges->create_privilege('perch_blog.comments.moderate', 'Moderate comments');
	    $UserPrivileges->create_privilege('perch_blog.comments.enable', 'Enable comments on a post');
	    $UserPrivileges->create_privilege('perch_blog.categories.manage', 'Manage categories');
	    $UserPrivileges->create_privilege('perch_blog.import', 'Import data');
	    $UserPrivileges->create_privilege('perch_blog.authors.manage', 'Manage authors');
	    $UserPrivileges->create_privilege('perch_blog.sections.manage', 'Manage sections');
	    if (PERCH_RUNWAY) $UserPrivileges->create_privilege('perch_blog.blogs.manage', 'Manage blogs');

	    
	    $db = $API->get('DB');
	    
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD `postImportID` VARCHAR(64)  NULL  DEFAULT NULL  AFTER `postCommentCount`";
	    $db->execute($sql);
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD `postCommentCount` INT(10)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `authorID`";
	    $db->execute($sql);
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD `postLegacyURL` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `postImportID`";
	    $db->execute($sql);
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD `postAllowComments` TINYINT(1)  UNSIGNED  NOT NULL  DEFAULT '1'  AFTER `postLegacyURL`";
	    $db->execute($sql);
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_authors` ADD `authorImportRef` VARCHAR(64)  NULL  DEFAULT NULL  AFTER `authorSlug`";
	    $db->execute($sql);
	    $sql = "INSERT INTO `".PERCH_DB_PREFIX."settings` (`settingID`, `userID`, `settingValue`) VALUES ('perch_blog_post_url', 0, '/blog/post.php?s={postSlug}')";
	    $db->execute($sql);

	    // 3.7
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_categories` ADD `categoryPostCount` INT(10)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `categorySlug`";
	    $db->execute($sql);
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_categories` ADD `categoryDynamicFields` TEXT  NULL  AFTER `categoryPostCount`";
	    $db->execute($sql);
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD `postTemplate` VARCHAR(255)  NOT NULL  DEFAULT 'post.html'  AFTER `postAllowComments`";
	    $db->execute($sql);
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_authors` ADD `authorDynamicFields` TEXT  NULL  AFTER `authorImportRef`";
	    $db->execute($sql);


	    // 3.8.2
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_authors` ADD `authorPostCount` INT(10)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `authorEmail`";
	    $db->execute($sql);


	    // 4.0
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD `sectionID` INT(10)  UNSIGNED  NOT NULL  DEFAULT '1'  AFTER `authorID`";
	    $db->execute($sql);

	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD INDEX `idx_status` (`postStatus`)`";
	    $db->execute($sql);

	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD INDEX `idx_section` (`sectionID`)`";
	    $db->execute($sql);

	    $sql = "CREATE TABLE `".PERCH_DB_PREFIX."blog_sections` (
	          `sectionID` int(11) NOT NULL AUTO_INCREMENT,
	          `sectionTitle` varchar(255) NOT NULL DEFAULT '',
	          `sectionSlug` varchar(255) NOT NULL DEFAULT '',
	          `sectionPostCount` int(10) unsigned NOT NULL DEFAULT '0',
	          `sectionDynamicFields` text,
	          PRIMARY KEY (`sectionID`),
	          KEY `idx_slug` (`sectionSlug`)
	        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
	    $db->execute($sql);

	    $sql = "INSERT INTO `".PERCH_DB_PREFIX."blog_sections` (sectionID, sectionTitle, sectionSlug, sectionPostCount, sectionDynamicFields) VALUES ('1', 'Posts', 'posts', 0, '')";
	    $db->execute($sql);


	    // 5.0
	    
	    $sql = "CREATE TABLE IF NOT EXISTS `".PERCH_DB_PREFIX."blog_index` (
		      `indexID` int(10) NOT NULL AUTO_INCREMENT,
		      `itemKey` char(64) NOT NULL DEFAULT '-',
		      `itemID` int(10) NOT NULL DEFAULT '0',
		      `indexKey` char(64) NOT NULL DEFAULT '-',
		      `indexValue` char(255) NOT NULL DEFAULT '',
		      PRIMARY KEY (`indexID`),
		      KEY `idx_fk` (`itemKey`,`itemID`),
		      KEY `idx_key` (`indexKey`),
		      KEY `idx_key_val` (`indexKey`,`indexValue`),
		      KEY `idx_keys` (`itemKey`,`indexKey`)
		    ) ENGINE=MyISAM DEFAULT CHARSET=utf8"; 
		$db->execute($sql);     
	    
	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_categories` ADD `categoryCoreID` INT(10)  NULL  DEFAULT NULL  AFTER `categoryDynamicFields`";
	    $db->execute($sql);

	    $Core_CategorySets = new PerchCategories_Sets();
	    $Core_Categories   = new PerchCategories_Categories();
	    $Set = $Core_CategorySets->get_by('setSlug', 'blog');
	    if (!$Set) {
	        $Set = $Core_CategorySets->create(array(
	                'setTitle'         => PerchLang::get('Blog'),
	                'setSlug'          => 'blog',
	                'setTemplate'      => '~/perch_blog/templates/blog/category_set.html',
	                'setCatTemplate'   => '~/perch_blog/templates/blog/category.html',
	                'setDynamicFields' => '[]'
	            ));
	        
	        $cats = $db->get_rows('SELECT * FROM '.PERCH_DB_PREFIX.'blog_categories');
	        if (PerchUtil::count($cats)) {
	            foreach($cats as $cat) {
	                $dynfields = '[]';

	                if ($cat['categoryDynamicFields']) {
	                    $dynfields = $cat['categoryDynamicFields'];
	                }

	                $NewCat = $Core_Categories->create(array(
	                                'setID'            => $Set->id(),
	                                'catParentID'      => 0,
	                                'catTitle'         => $cat['categoryTitle'],
	                                'catSlug'          => $cat['categorySlug'],
	                                'catPath'          => '/blog/'.$cat['categorySlug'].'/',
	                                'catDynamicFields' => $dynfields,
	                            ));
	                if (is_object($NewCat)) {
	                	$db->update(PERCH_DB_PREFIX.'blog_categories', array(
	                        'categoryCoreID' => $NewCat->id()
	                        ), 'categoryID', $cat['categoryID']);
	                }
	                
	            }
	        }
	    }


	    // Real 5.0 (5.0.1)

	    $sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD COLUMN `blogID` int(10) UNSIGNED DEFAULT 1 after `postID`";
	    $db->execute($sql);

		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD COLUMN `postMetaTemplate` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'post_meta.html' after `postTemplate`";
		$db->execute($sql);

		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD INDEX `idx_status` USING BTREE (`postStatus`) comment ''";
		$db->execute($sql);

		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD INDEX `idx_blog` USING BTREE (`blogID`) comment ''";
		$db->execute($sql);

		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_sections` ADD COLUMN `blogID` int(10) UNSIGNED NOT NULL DEFAULT 1 after `sectionID`";
		$db->execute($sql);

		$sql = "CREATE TABLE IF NOT EXISTS `".PERCH_DB_PREFIX."blogs` (
			`blogID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`blogTitle` char(255) NOT NULL DEFAULT 'Blog',
			`blogSlug` char(255) DEFAULT 'blog',
			`setSlug` char(255) DEFAULT 'blog',
			`postTemplate` char(255) DEFAULT 'post.html',
			`blogDynamicFields` mediumtext DEFAULT NULL,
			PRIMARY KEY (`blogID`)) CHARSET=utf8";
		$db->execute($sql);

		$sql = 'SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'blogs';
		if ($db->get_count($sql)==0) {
			$sql = "INSERT INTO `".PERCH_DB_PREFIX."blogs` (`blogID`, `blogTitle`, `blogSlug`, `setSlug`, `postTemplate`, `blogDynamicFields`)
					VALUES (1,'Blog','blog','blog','post.html','[]')";
			$db->execute($sql);
		}



		// 5.6
		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` ADD `postIsPublished` TINYINT(1)  NOT NULL  DEFAULT '1'  AFTER `postMetaTemplate`";
		$db->execute($sql);		


		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_posts` CHANGE `postIsPublished` `postIsPublished` TINYINT(1) NOT NULL DEFAULT '0'";
		$db->execute($sql);

		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_comments` ADD `webmention` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `commentDynamicFields`";
		$db->execute($sql);

		$sql = "ALTER TABLE `".PERCH_DB_PREFIX."blog_comments` ADD `webmentionType` ENUM('comment', 'like', 'repost') NULL DEFAULT NULL AFTER `webmention`";
		$db->execute($sql);

		$sql = "CREATE TABLE IF NOT EXISTS `".PERCH_DB_PREFIX."blog_webmention_queue` (
			      `entryID` int(10) unsigned NOT NULL AUTO_INCREMENT,
			      `entryCreated` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
			      `entrySource` char(255) NOT NULL DEFAULT '',
			      `entryTarget` char(255) NOT NULL DEFAULT '',
			      `entryType` enum('post','comment') NOT NULL DEFAULT 'post',
			      `entryFK` int(10) NOT NULL DEFAULT '0',
			      PRIMARY KEY (`entryID`)
			    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->execute($sql);

	}



    $Posts = new PerchBlog_Posts($API);
    $posts = $Posts->all($Paging);
    if (PerchUtil::count($posts)) {
        foreach($posts as $Post) {
            $Post->import_legacy_categories();
            $Post->index();
        }
    }

    if ($Paging->is_last_page()) {

    	$Sections = new PerchBlog_Sections($API);
    	$Sections->update_post_counts();

    	$Posts->update_category_counts();

    	$Authors = new PerchBlog_Authors($API);
    	$Authors->update_post_counts();

    	$Settings->set('perch_blog_update', '5.6');
    }

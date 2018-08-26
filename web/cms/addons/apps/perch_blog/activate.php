<?php
    // Prevent running directly:
    if (!defined('PERCH_DB_PREFIX')) exit;

    // Let's go
    $sql = "
    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_authors` (
      `authorID` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `authorGivenName` varchar(255) NOT NULL DEFAULT '',
      `authorFamilyName` varchar(255) NOT NULL DEFAULT '',
      `authorEmail` varchar(255) NOT NULL DEFAULT '',
      `authorPostCount` int(10) unsigned NOT NULL DEFAULT '0',
      `authorSlug` varchar(255) NOT NULL DEFAULT '',
      `authorImportRef` varchar(64) DEFAULT NULL,
      `authorDynamicFields` text,
      PRIMARY KEY (`authorID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_comments` (
      `commentID` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `postID` int(10) unsigned NOT NULL,
      `commentName` varchar(255) NOT NULL DEFAULT '',
      `commentEmail` varchar(255) NOT NULL DEFAULT '',
      `commentURL` varchar(255) NOT NULL DEFAULT '',
      `commentIP` int(10) unsigned NOT NULL DEFAULT 0,
      `commentDateTime` datetime NOT NULL,
      `commentHTML` text NOT NULL,
      `commentStatus` enum('LIVE','PENDING','SPAM','REJECTED') NOT NULL DEFAULT 'PENDING',
      `commentSpamData` text NOT NULL,
      `commentDynamicFields` text NOT NULL,
      `webmention` tinyint(1) unsigned NOT NULL DEFAULT '0',
      `webmentionType` enum('comment','like','repost') DEFAULT NULL,
      PRIMARY KEY (`commentID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_posts` (
      `postID` int(11) NOT NULL AUTO_INCREMENT,
      `blogID` int(10) unsigned DEFAULT '1',
      `postTitle` varchar(255) NOT NULL DEFAULT '',
      `postSlug` varchar(255) NOT NULL DEFAULT '',
      `postDateTime` datetime DEFAULT NULL,
      `postDescRaw` text,
      `postDescHTML` text,
      `postDynamicFields` mediumtext,
      `postTags` varchar(255) NOT NULL DEFAULT '',
      `postStatus` enum('Published','Draft') NOT NULL DEFAULT 'Published',
      `authorID` int(10) unsigned NOT NULL DEFAULT '0',
      `sectionID` int(10) unsigned NOT NULL DEFAULT '1',
      `postCommentCount` int(10) unsigned NOT NULL DEFAULT '0',
      `postImportID` varchar(64) DEFAULT NULL,
      `postLegacyURL` varchar(255) DEFAULT NULL,
      `postAllowComments` tinyint(1) unsigned NOT NULL DEFAULT '1',
      `postTemplate` varchar(255) NOT NULL DEFAULT 'post.html',
      `postMetaTemplate` varchar(255) NOT NULL DEFAULT 'post_meta.html',
      `postIsPublished` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`postID`),
      KEY `idx_date` (`postDateTime`),
      KEY `idx_status` (`postStatus`),
      KEY `idx_blog` (`blogID`),
      FULLTEXT KEY `idx_search` (`postTitle`,`postDescRaw`,`postTags`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_posts_to_tags` (
      `postID` int(11) NOT NULL DEFAULT '0',
      `tagID` int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (`postID`,`tagID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_tags` (
      `tagID` int(11) NOT NULL AUTO_INCREMENT,
      `tagTitle` varchar(255) NOT NULL DEFAULT '',
      `tagSlug` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`tagID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

    INSERT INTO `__PREFIX__settings` (`settingID`, `userID`, `settingValue`)
      VALUES ('perch_blog_post_url', 0, '/blog/post.php?s={postSlug}');

    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_sections` (
      `sectionID` int(11) NOT NULL AUTO_INCREMENT,
      `blogID` int(10) unsigned NOT NULL DEFAULT '1',
      `sectionTitle` varchar(255) NOT NULL DEFAULT '',
      `sectionSlug` varchar(255) NOT NULL DEFAULT '',
      `sectionPostCount` int(10) unsigned NOT NULL DEFAULT '0',
      `sectionDynamicFields` text,
      PRIMARY KEY (`sectionID`),
      KEY `idx_slug` (`sectionSlug`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_index` (
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
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `__PREFIX__blogs` (
      `blogID` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `blogTitle` char(255) NOT NULL DEFAULT 'Blog',
      `blogSlug` char(255) DEFAULT 'blog',
      `setSlug` char(255) DEFAULT 'blog',
      `postTemplate` char(255) DEFAULT 'post.html',
      `blogDynamicFields` mediumtext,
      PRIMARY KEY (`blogID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    INSERT INTO `__PREFIX__blogs` (`blogID`, `blogTitle`, `blogSlug`, `setSlug`, `postTemplate`, `blogDynamicFields`)
    VALUES (1,'Blog','blog','blog','post.html','[]');


    CREATE TABLE IF NOT EXISTS `__PREFIX__blog_webmention_queue` (
      `entryID` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `entryCreated` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
      `entrySource` char(255) NOT NULL DEFAULT '',
      `entryTarget` char(255) NOT NULL DEFAULT '',
      `entryType` enum('post','comment') NOT NULL DEFAULT 'post',
      `entryFK` int(10) NOT NULL DEFAULT '0',
      PRIMARY KEY (`entryID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;


    ";

    $sql = str_replace('__PREFIX__', PERCH_DB_PREFIX, $sql);

    $statements = explode(';', $sql);
    foreach($statements as $statement) {
        $statement = trim($statement);
        if ($statement!='') $this->db->execute($statement);
    }


    $API = new PerchAPI(1.0, 'perch_blog');
    $UserPrivileges = $API->get('UserPrivileges');
    $UserPrivileges->create_privilege('perch_blog', 'Access the blog');
    $UserPrivileges->create_privilege('perch_blog.post.create', 'Create posts');
    $UserPrivileges->create_privilege('perch_blog.post.delete', 'Delete posts');
    $UserPrivileges->create_privilege('perch_blog.post.publish', 'Publish posts');
    $UserPrivileges->create_privilege('perch_blog.comments.moderate', 'Moderate comments');
    $UserPrivileges->create_privilege('perch_blog.comments.enable', 'Enable comments on a post');
    $UserPrivileges->create_privilege('perch_blog.import', 'Import data');
    $UserPrivileges->create_privilege('perch_blog.authors.manage', 'Manage authors');

    $sql = "INSERT INTO `".PERCH_DB_PREFIX."blog_sections` (sectionID, sectionTitle, sectionSlug, sectionPostCount, sectionDynamicFields) VALUES ('1', 'Posts', 'posts', 0, '')";
        $this->db->execute($sql);

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
    }

    $sql = 'SHOW TABLES LIKE "'.$this->table.'"';
    $result = $this->db->get_value($sql);

    return $result;

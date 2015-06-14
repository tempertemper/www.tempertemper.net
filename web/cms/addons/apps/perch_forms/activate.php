<?php
    // Prevent running directly:
    if (!defined('PERCH_DB_PREFIX')) exit;

    // Let's go
    $sql = "
    
    CREATE TABLE `__PREFIX__forms` (
      `formID` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `formKey` varchar(64) NOT NULL DEFAULT '',
      `formTitle` varchar(255) NOT NULL DEFAULT '',
      `formTemplate` varchar(255) NOT NULL DEFAULT '',
      `formOptions` text,
      PRIMARY KEY (`formID`),
      KEY `idx_formKey` (`formKey`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

    CREATE TABLE `__PREFIX__forms_responses` (
      `responseID` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `formID` int(10) unsigned NOT NULL,
      `responseCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `responseJSON` mediumtext,
      `responseIP` varchar(16) NOT NULL DEFAULT '',
      `responseSpam` tinyint(1) unsigned NOT NULL DEFAULT '0',
      `responseSpamData` text,
      PRIMARY KEY (`responseID`),
      KEY `idx_formID` (`formID`),
      KEY `idx_spam` (`responseSpam`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

    ";
    
    $sql = str_replace('__PREFIX__', PERCH_DB_PREFIX, $sql);
    
    $statements = explode(';', $sql);
    foreach($statements as $statement) {
        $statement = trim($statement);
        if ($statement!='') $this->db->execute($statement);
    }
        
    $API = new PerchAPI(1.0, 'perch_forms');
    $UserPrivileges = $API->get('UserPrivileges');
    $UserPrivileges->create_privilege('perch_forms', 'Access forms');
    $UserPrivileges->create_privilege('perch_forms.configure', 'Configure forms');
    $UserPrivileges->create_privilege('perch_forms.delete', 'Delete forms');


    $sql = 'SHOW TABLES LIKE "'.$this->table.'"';
    $result = $this->db->get_value($sql);
    
    return $result;

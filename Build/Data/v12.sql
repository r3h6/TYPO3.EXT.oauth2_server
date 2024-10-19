DROP TABLE IF EXISTS `be_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `non_exclude_fields` text DEFAULT NULL,
  `explicit_allowdeny` text DEFAULT NULL,
  `allowed_languages` varchar(255) NOT NULL DEFAULT '',
  `custom_options` text DEFAULT NULL,
  `db_mountpoints` text DEFAULT NULL,
  `pagetypes_select` text DEFAULT NULL,
  `tables_select` text DEFAULT NULL,
  `tables_modify` text DEFAULT NULL,
  `groupMods` text DEFAULT NULL,
  `mfa_providers` text DEFAULT NULL,
  `file_mountpoints` text DEFAULT NULL,
  `file_permissions` text DEFAULT NULL,
  `TSconfig` text DEFAULT NULL,
  `subgroup` text DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `category_perms` longtext DEFAULT NULL,
  `availableWidgets` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `be_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `avatar` int(10) unsigned NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL DEFAULT '',
  `admin` smallint(5) unsigned NOT NULL DEFAULT 0,
  `usergroup` text DEFAULT NULL,
  `lang` varchar(10) NOT NULL DEFAULT 'default',
  `email` varchar(255) NOT NULL DEFAULT '',
  `db_mountpoints` text DEFAULT NULL,
  `options` smallint(5) unsigned NOT NULL DEFAULT 0,
  `realName` varchar(80) NOT NULL DEFAULT '',
  `userMods` text DEFAULT NULL,
  `allowed_languages` varchar(255) NOT NULL DEFAULT '',
  `uc` mediumblob DEFAULT NULL,
  `file_mountpoints` text DEFAULT NULL,
  `file_permissions` text DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `TSconfig` text DEFAULT NULL,
  `workspace_id` int(11) NOT NULL DEFAULT 0,
  `mfa` mediumblob DEFAULT NULL,
  `category_perms` longtext DEFAULT NULL,
  `lastlogin` int(11) NOT NULL DEFAULT 0,
  `password_reset_token` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `parent` (`pid`,`deleted`,`disable`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `be_users` VALUES (1,0,1716110907,1716110907,0,0,0,0,NULL,'admin',0,'$argon2i$v=19$m=65536,t=16,p=1$Q2EvVmxaM2NPVVJJSHluaA$y+1mlztIdakNPIFPwsAQna9Am70GYn1/X964tolkvFA',1,NULL,'default','',NULL,0,'',NULL,'','a:7:{s:10:\"moduleData\";a:10:{s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";a:0:{}s:10:\"FormEngine\";a:2:{i:0;a:2:{s:32:\"c312013d83c1a6ad7fec8b36a37ba3c8\";a:5:{i:0;s:5:\"Login\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:1;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:98:\"/typo3/module/web/layout?token=f1187dcbfd65e4bd438406e6786597a62ec44a1e&id=1&#element-tt_content-1\";}s:32:\"fb1ea7bbfdc65adae2f59a7feb072a68\";a:5:{i:0;s:42:\"Test, fc17757d-aa0c-481d-96d7-c2504ce7199f\";i:1;a:5:{s:4:\"edit\";a:1:{s:35:\"tx_oauth2server_domain_model_client\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:58:\"&edit%5Btx_oauth2server_domain_model_client%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:35:\"tx_oauth2server_domain_model_client\";s:3:\"uid\";i:1;s:3:\"pid\";i:2;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=d36667b31e507b16b120b19abcd62d3d12df867c&id=2&table=&pointer=1\";}}i:1;s:32:\"fb1ea7bbfdc65adae2f59a7feb072a68\";}s:13:\"system_config\";a:1:{s:4:\"tree\";s:8:\"services\";}s:16:\"opendocs::recent\";a:8:{s:32:\"831786fd4612b658a2e11ed01c306877\";a:5:{i:0;s:6:\"jondoe\";i:1;a:5:{s:4:\"edit\";a:1:{s:8:\"fe_users\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:31:\"&edit%5Bfe_users%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:8:\"fe_users\";s:3:\"uid\";i:1;s:3:\"pid\";i:3;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=9ff330ca8b1a87e26b42e576cac81f47762822e9&id=3&table=&pointer=1\";}s:32:\"3d8e630a05d2c34601bfa40a2bc5ee63\";a:5:{i:0;s:5:\"Login\";i:1;a:5:{s:4:\"edit\";a:1:{s:8:\"sys_news\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:31:\"&edit%5Bsys_news%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:8:\"sys_news\";s:3:\"uid\";i:1;s:3:\"pid\";i:0;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=d8f3502412e1274a3b1e1ad5c384d0710255ba17&id=0&table=&pointer=1\";}s:32:\"5c1b878c87d518a051ef05bd38d2cccf\";a:5:{i:0;s:3:\"CUG\";i:1;a:5:{s:4:\"edit\";a:1:{s:9:\"fe_groups\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:32:\"&edit%5Bfe_groups%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:9:\"fe_groups\";s:3:\"uid\";i:1;s:3:\"pid\";i:3;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=9ff330ca8b1a87e26b42e576cac81f47762822e9&id=3&table=&pointer=1\";}s:32:\"deac478137dd48a97e299bd046412e21\";a:5:{i:0;s:0:\"\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:2;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B2%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:2;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:76:\"/typo3/module/web/layout?token=9cc8ff3459fe2df9bb89be21819e41edfe4a566f&id=4\";}s:32:\"7d9e144e24486a6668d151356ea4c9d4\";a:5:{i:0;s:7:\"Consent\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:4;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B4%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:4;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:76:\"/typo3/module/web/layout?token=9cc8ff3459fe2df9bb89be21819e41edfe4a566f&id=4\";}s:32:\"c312013d83c1a6ad7fec8b36a37ba3c8\";a:5:{i:0;s:5:\"Login\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:1;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:98:\"/typo3/module/web/layout?token=9cc8ff3459fe2df9bb89be21819e41edfe4a566f&id=1&#element-tt_content-1\";}s:32:\"20ed475662b97ac33d3aa853a74f9c9c\";a:5:{i:0;s:7:\"Clients\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:2;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B2%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:2;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=ef2a4df00d41ac6bb2ec3ffa380bc6fb756ed0ad&id=1&table=&pointer=1\";}s:32:\"2258d9587529a9ea12f96ce7d90372b3\";a:5:{i:0;s:5:\"Users\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:3;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B3%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:3;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:76:\"/typo3/module/web/layout?token=9cc8ff3459fe2df9bb89be21819e41edfe4a566f&id=3\";}}s:6:\"web_ts\";a:1:{s:6:\"action\";s:17:\"typoscript_active\";}s:29:\"web_typoscript_constanteditor\";a:2:{s:23:\"selectedTemplatePerPage\";a:1:{i:1;i:1;}s:16:\"selectedCategory\";s:5:\"basic\";}s:15:\"system_BelogLog\";a:1:{s:10:\"constraint\";s:329:\"O:39:\"TYPO3\\CMS\\Belog\\Domain\\Model\\Constraint\":11:{s:14:\"\0*\0userOrGroup\";s:1:\"0\";s:9:\"\0*\0number\";i:50;s:15:\"\0*\0workspaceUid\";i:-99;s:10:\"\0*\0channel\";s:0:\"\";s:8:\"\0*\0level\";s:0:\"\";s:17:\"\0*\0startTimestamp\";i:0;s:15:\"\0*\0endTimestamp\";i:0;s:18:\"\0*\0manualDateStart\";N;s:17:\"\0*\0manualDateStop\";N;s:9:\"\0*\0pageId\";i:0;s:8:\"\0*\0depth\";i:0;}\";}s:17:\"typoscript_active\";a:6:{s:18:\"sortAlphabetically\";b:1;s:28:\"displayConstantSubstitutions\";b:1;s:15:\"displayComments\";b:1;s:23:\"selectedTemplatePerPage\";a:1:{i:1;i:1;}s:18:\"constantConditions\";a:0:{}s:15:\"setupConditions\";a:0:{}}s:23:\"web_typoscript_analyzer\";a:3:{s:23:\"selectedTemplatePerPage\";a:1:{i:1;i:1;}s:18:\"constantConditions\";a:0:{}s:15:\"setupConditions\";a:0:{}}s:25:\"web_typoscript_infomodify\";a:1:{s:23:\"selectedTemplatePerPage\";a:1:{i:1;i:1;}}}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";i:50;s:20:\"edit_docModuleUpload\";s:1:\"1\";s:17:\"BackendComponents\";a:1:{s:6:\"States\";a:1:{s:8:\"Pagetree\";a:1:{s:9:\"stateHash\";a:1:{s:3:\"0_1\";s:1:\"1\";}}}}s:15:\"moduleSessionID\";a:10:{s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";s:40:\"5c688785c376dc258e1840613d57056f941f3f34\";s:10:\"FormEngine\";s:40:\"5c688785c376dc258e1840613d57056f941f3f34\";s:13:\"system_config\";s:40:\"060c587d22a62a66943cc5f30484f6fa20f52942\";s:16:\"opendocs::recent\";s:40:\"5c688785c376dc258e1840613d57056f941f3f34\";s:6:\"web_ts\";s:40:\"98dd0c7056fc7d176ec6691821c3419aec4c62d5\";s:29:\"web_typoscript_constanteditor\";s:40:\"7abcb49236a8e986622b768845bd2dac26031b14\";s:15:\"system_BelogLog\";s:40:\"b0c4100cd0b5d4ebd2d2b68cbdee64e333c97d1b\";s:17:\"typoscript_active\";s:40:\"98dd0c7056fc7d176ec6691821c3419aec4c62d5\";s:23:\"web_typoscript_analyzer\";s:40:\"98dd0c7056fc7d176ec6691821c3419aec4c62d5\";s:25:\"web_typoscript_infomodify\";s:40:\"98dd0c7056fc7d176ec6691821c3419aec4c62d5\";}s:7:\"reports\";a:1:{s:9:\"selection\";a:2:{s:6:\"action\";s:6:\"detail\";s:6:\"report\";s:6:\"status\";}}}',NULL,NULL,1,NULL,0,NULL,NULL,1718733933,'');
INSERT INTO `be_users` VALUES (2,0,1717089287,1717089287,0,0,0,0,NULL,'_cli_',0,'$argon2i$v=19$m=65536,t=16,p=1$c3pDTy9rQkgvNTV5Y0Z0Ng$3akO0hPz6d1zpAYms9w7LCzaJZHWNwxk1zRZ4VS2URs',1,NULL,'default','',NULL,0,'',NULL,'','a:4:{s:10:\"moduleData\";a:0:{}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";i:50;s:20:\"edit_docModuleUpload\";s:1:\"1\";}',NULL,NULL,1,NULL,0,NULL,NULL,0,'');
DROP TABLE IF EXISTS `fe_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `subgroup` tinytext DEFAULT NULL,
  `felogin_redirectPid` tinytext DEFAULT NULL,
  `tx_extbase_type` varchar(255) NOT NULL DEFAULT '0',
  `TSconfig` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `fe_groups` VALUES (1,3,1718400195,1718400195,0,0,'','CUG','','','0',NULL);
DROP TABLE IF EXISTS `fe_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `tx_extbase_type` varchar(255) NOT NULL DEFAULT '0',
  `lastlogin` int(11) NOT NULL DEFAULT 0,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `usergroup` text DEFAULT NULL,
  `name` varchar(160) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `middle_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `uc` blob DEFAULT NULL,
  `title` varchar(40) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(40) NOT NULL DEFAULT '',
  `www` varchar(80) NOT NULL DEFAULT '',
  `company` varchar(80) NOT NULL DEFAULT '',
  `image` tinytext DEFAULT NULL,
  `is_online` int(10) unsigned NOT NULL DEFAULT 0,
  `mfa` mediumblob DEFAULT NULL,
  `felogin_redirectPid` tinytext DEFAULT NULL,
  `felogin_forgotHash` varchar(80) DEFAULT '',
  `TSconfig` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`username`(100)),
  KEY `username` (`username`(100)),
  KEY `is_online` (`is_online`),
  KEY `felogin_forgotHash` (`felogin_forgotHash`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `fe_users` VALUES (1,3,1718561940,1717089603,0,0,0,0,'','0',1718658117,'johndoe','$argon2i$v=19$m=65536,t=16,p=1$Q05aeEtUM29EYll3Y0s3Vw$pWaw11UNerbj4hppKdd3AMN7onwdHBIsp2yRBlJVA84','1','','John','','Doe','','','','johndoe@domain.tld',NULL,'','','','','','','0',1718658699,NULL,'','',NULL);
DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `rowDescription` text DEFAULT NULL,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `perms_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_groupid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_user` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_group` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_everybody` smallint(5) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `doktype` int(10) unsigned NOT NULL DEFAULT 0,
  `TSconfig` text DEFAULT NULL,
  `is_siteroot` smallint(6) NOT NULL DEFAULT 0,
  `php_tree_stop` smallint(6) NOT NULL DEFAULT 0,
  `url` varchar(255) NOT NULL DEFAULT '',
  `shortcut` int(10) unsigned NOT NULL DEFAULT 0,
  `shortcut_mode` int(10) unsigned NOT NULL DEFAULT 0,
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `target` varchar(80) NOT NULL DEFAULT '',
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `keywords` text DEFAULT NULL,
  `cache_timeout` int(10) unsigned NOT NULL DEFAULT 0,
  `cache_tags` varchar(255) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `no_search` smallint(5) unsigned NOT NULL DEFAULT 0,
  `SYS_LASTCHANGED` int(10) unsigned NOT NULL DEFAULT 0,
  `abstract` text DEFAULT NULL,
  `module` varchar(255) NOT NULL DEFAULT '',
  `extendToSubpages` smallint(5) unsigned NOT NULL DEFAULT 0,
  `author` varchar(255) NOT NULL DEFAULT '',
  `author_email` varchar(255) NOT NULL DEFAULT '',
  `nav_title` varchar(255) NOT NULL DEFAULT '',
  `nav_hide` smallint(6) NOT NULL DEFAULT 0,
  `content_from_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid_ol` smallint(6) NOT NULL DEFAULT 0,
  `l18n_cfg` smallint(6) NOT NULL DEFAULT 0,
  `backend_layout` varchar(64) NOT NULL DEFAULT '',
  `backend_layout_next_level` varchar(64) NOT NULL DEFAULT '',
  `tsconfig_includes` text DEFAULT NULL,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  `lastUpdated` int(11) NOT NULL DEFAULT 0,
  `newUntil` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(2048) DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `determineSiteRoot` (`is_siteroot`),
  KEY `language_identifier` (`l10n_parent`,`sys_language_uid`),
  KEY `slug` (`slug`(127)),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `translation_source` (`l10n_source`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `pages` VALUES (1,0,1717090001,1716110915,0,0,0,0,'',0,NULL,0,0,0,0,NULL,'{\"title\":\"\"}',0,0,0,0,1,1,31,31,1,'Login',1,NULL,1,0,'',0,0,'',0,'',0,NULL,0,'',NULL,0,1718733994,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,0,0,'/',0);
INSERT INTO `pages` VALUES (2,1,1716111212,1716111209,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,'{\"hidden\":\"\"}',0,0,0,0,1,0,31,27,0,'Clients',254,NULL,0,0,'',0,0,'',0,'',0,NULL,0,'',NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,0,0,'/clients',0);
INSERT INTO `pages` VALUES (3,1,1717089490,1717089432,0,0,0,0,'0',128,NULL,0,0,0,0,NULL,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"hidden\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,1,0,31,27,0,'Users',254,NULL,0,0,'',0,0,'',0,'',0,NULL,0,'',NULL,0,0,NULL,'fe_users',0,'','','',0,0,0,0,0,'','',NULL,0,0,0,'/users',0);
INSERT INTO `pages` VALUES (4,1,1718399757,1717089959,0,0,0,0,'-2',64,NULL,0,0,0,0,NULL,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"description\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,1,0,31,27,0,'Consent',1,NULL,0,0,'',0,0,'',0,'',0,NULL,0,'',NULL,0,1718400067,NULL,'',0,'','','',1,0,0,0,0,'','',NULL,0,0,0,'/consent',0);
DROP TABLE IF EXISTS `sys_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_news` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` mediumtext DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `sys_news` VALUES (1,0,1718733920,1718733904,0,0,0,0,'Login','<p>Username: \"admin\"<br />Password: \"Password1!\"</p>');
DROP TABLE IF EXISTS `sys_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_template` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `root` smallint(5) unsigned NOT NULL DEFAULT 0,
  `clear` smallint(5) unsigned NOT NULL DEFAULT 0,
  `include_static_file` text DEFAULT NULL,
  `constants` text DEFAULT NULL,
  `config` text DEFAULT NULL,
  `basedOn` tinytext DEFAULT NULL,
  `includeStaticAfterBasedOn` smallint(5) unsigned NOT NULL DEFAULT 0,
  `static_file_mode` smallint(5) unsigned NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `roottemplate` (`deleted`,`hidden`,`root`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `sys_template` VALUES (1,1,1717089827,1716110915,0,0,0,0,0,'This is an Empty Site Package TypoScript record.\r\n\r\nFor each website you need a TypoScript record on the main page of your website (on the top level). For better maintenance all TypoScript should be extracted into external files via @import \'EXT:site_myproject/Configuration/TypoScript/setup.typoscript\'','Main TypoScript Rendering',1,3,'EXT:oauth2_server/Configuration/TypoScript,EXT:fluid_styled_content/Configuration/TypoScript/,EXT:fluid_styled_content/Configuration/TypoScript/Styling/','','',NULL,0,0,0);
DROP TABLE IF EXISTS `tt_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tt_content` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rowDescription` text DEFAULT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l18n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `l18n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `CType` varchar(255) NOT NULL DEFAULT '',
  `header` varchar(255) NOT NULL DEFAULT '',
  `header_position` varchar(255) NOT NULL DEFAULT '',
  `bodytext` mediumtext DEFAULT NULL,
  `bullets_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_description` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `assets` int(10) unsigned NOT NULL DEFAULT 0,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `imagewidth` int(10) unsigned NOT NULL DEFAULT 0,
  `imageorient` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imagecols` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageborder` smallint(5) unsigned NOT NULL DEFAULT 0,
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `frame_class` varchar(60) NOT NULL DEFAULT 'default',
  `cols` int(10) unsigned NOT NULL DEFAULT 0,
  `space_before_class` varchar(60) NOT NULL DEFAULT '',
  `space_after_class` varchar(60) NOT NULL DEFAULT '',
  `records` text DEFAULT NULL,
  `pages` text DEFAULT NULL,
  `colPos` int(10) unsigned NOT NULL DEFAULT 0,
  `subheader` varchar(255) NOT NULL DEFAULT '',
  `header_link` varchar(1024) NOT NULL DEFAULT '',
  `image_zoom` smallint(5) unsigned NOT NULL DEFAULT 0,
  `header_layout` varchar(30) NOT NULL DEFAULT '0',
  `list_type` varchar(255) NOT NULL DEFAULT '',
  `sectionIndex` smallint(5) unsigned NOT NULL DEFAULT 0,
  `linkToTop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `file_collections` text DEFAULT NULL,
  `filelink_size` smallint(5) unsigned NOT NULL DEFAULT 0,
  `filelink_sorting` varchar(64) NOT NULL DEFAULT '',
  `filelink_sorting_direction` varchar(4) NOT NULL DEFAULT '',
  `target` varchar(30) NOT NULL DEFAULT '',
  `recursive` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageheight` int(10) unsigned NOT NULL DEFAULT 0,
  `pi_flexform` mediumtext DEFAULT NULL,
  `accessibility_title` varchar(30) NOT NULL DEFAULT '',
  `accessibility_bypass` smallint(5) unsigned NOT NULL DEFAULT 0,
  `accessibility_bypass_text` varchar(30) NOT NULL DEFAULT '',
  `category_field` varchar(64) NOT NULL DEFAULT '',
  `table_class` varchar(60) NOT NULL DEFAULT '',
  `table_caption` varchar(255) DEFAULT NULL,
  `table_delimiter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_enclosure` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_header_position` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_tfoot` smallint(5) unsigned NOT NULL DEFAULT 0,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  `selected_categories` longtext DEFAULT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`sorting`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `language` (`l18n_parent`,`sys_language_uid`),
  KEY `translation_source` (`l10n_source`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tt_content` VALUES (1,'',1,1718733994,1717089440,0,0,0,0,'',256,0,0,0,0,NULL,'{\"CType\":\"\",\"colPos\":\"\",\"header\":\"\",\"header_layout\":\"\",\"header_position\":\"\",\"date\":\"\",\"header_link\":\"\",\"subheader\":\"\",\"pi_flexform\":\"\",\"layout\":\"\",\"frame_class\":\"\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":\"\",\"linkToTop\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,'felogin_login','Login','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'100','',1,0,NULL,0,'','','',0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.showForgotPassword\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.showPermaLogin\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"settings.showLogoutFormAfterLogin\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.pages\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_redirect\">\n            <language index=\"lDEF\">\n                <field index=\"settings.redirectMode\">\n                    <value index=\"vDEF\">getpost</value>\n                </field>\n                <field index=\"settings.redirectFirstMethod\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"settings.redirectPageLogin\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectPageLoginError\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectPageLogout\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectDisable\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_messages\">\n            <language index=\"lDEF\">\n                <field index=\"settings.welcome_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.welcome_message\">\n                    <value index=\"vDEF\">Username: &quot;johndoe&quot;,\nPassword: &quot;Password1!&quot;</value>\n                </field>\n                <field index=\"settings.success_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_reset_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','',NULL,124,0,0,0,0,NULL,0,0);
INSERT INTO `tt_content` VALUES (2,'',4,1718400067,1717090028,0,0,0,0,'',256,0,0,0,0,NULL,'{\"CType\":\"\",\"colPos\":\"\",\"header\":\"\",\"header_layout\":\"\",\"header_position\":\"\",\"date\":\"\",\"header_link\":\"\",\"subheader\":\"\",\"layout\":\"\",\"frame_class\":\"\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":\"\",\"linkToTop\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,'oauth2server_consent','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,'',0,'','',0,'0','oauth2server_consent',1,0,NULL,0,'','','',0,0,NULL,'',0,'','','',NULL,124,0,0,0,0,NULL,0,0);
DROP TABLE IF EXISTS `tx_oauth2server_domain_model_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_oauth2server_domain_model_client` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rowDescription` text DEFAULT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `identifier` varchar(36) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `secret` varchar(255) NOT NULL DEFAULT '',
  `grant_type` varchar(255) NOT NULL DEFAULT '',
  `redirect_uri` text DEFAULT NULL,
  `is_confidential` smallint(5) unsigned NOT NULL DEFAULT 0,
  `skip_consent` smallint(5) unsigned NOT NULL DEFAULT 0,
  `allowed_scopes` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `tx_oauth2server_domain_model_client` VALUES (1,'Secret: ae1d9c2eaf57be0bde90d771b16a01aacaa2fbe8',2,1718734515,1716752971,0,0,0,0,0,'fc17757d-aa0c-481d-96d7-c2504ce7199f','Test','$argon2i$v=19$m=65536,t=16,p=1$Ri42Sy8zZmVRb2ZYTjZpQw$4zob27Yrs1mlMjiDD0Z/KdjiZ2e8j+hQBvrzCe/krnk','authorization_code,client_credentials,implicit,password,refresh_token','https://oauthdebugger.com/debug\r\nhttps://oauthdebugger.com/debug',1,0,'');

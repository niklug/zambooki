CREATE TABLE IF NOT EXISTS `#__rbid_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(255) NOT NULL,
  `fileExt` varchar(10) NOT NULL,
  `fileType` enum('nda','attachment','image') NOT NULL,
  `auctionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auctionId` (`auctionId`),
  KEY `userid` (`userId`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_auctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `shortdescription` text,
  `description` text NOT NULL,
  `picture` varchar(50) NOT NULL DEFAULT '',
  `link_extern` text NOT NULL,
  `max_price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(40) NOT NULL DEFAULT '0',
  `max_price_default_currency` DECIMAL(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Max Price converted to default currency',
  `auction_type` int(11) NOT NULL DEFAULT '0',
  `automatic` int(1) DEFAULT '0',
  `payment` int(11) NOT NULL DEFAULT '0',
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed_date` date NOT NULL DEFAULT '0000-00-00',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `close_offer` int(11) NOT NULL DEFAULT '0',
  `close_by_admin` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  `cat` int(11) NOT NULL DEFAULT '0',
  `has_file` int(11) NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL,
  `job_deadline` int(11) DEFAULT NULL,
  `show_bidder_nr` tinyint(2) DEFAULT NULL,
  `show_best_bid` tinyint(2) DEFAULT NULL,
  `NDA_file` varchar(255) NOT NULL,
  `NDA` tinyint(1) NOT NULL DEFAULT '0',
  `featured` enum('featured','none') DEFAULT 'none',
  `auction_nr` varchar(12) NOT NULL,
  `cancel_reason` text NOT NULL,
  `googlex` varchar(255) NOT NULL,
  `googley` varchar(255) NOT NULL,
  `approved` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iuserid` (`userid`),
  KEY `ititle` (`title`),
  KEY `icat` (`cat`),
  KEY `idate1` (`start_date`),
  KEY `idate2` (`end_date`),
  KEY `ipublished` (`published`),
  KEY `icloseoffer` (`close_offer`),
  KEY `icloseadmin` (`close_by_admin`),
  KEY `iwinnerid` (`winner_id`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(250) NOT NULL,
  `parent` int(11) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `iparent` (`parent`),
  KEY `ihash` (`hash`),
  KEY `istatus` (`status`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `simbol` char(3) NOT NULL DEFAULT '',
  `active` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iname` (`name`),
  KEY `iactive` (`active`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `convert` decimal(15,5) DEFAULT NULL,
  `default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `db_name` varchar(50) NOT NULL,
  `page` varchar(100) NOT NULL,
  `ftype` varchar(150) NOT NULL,
  `compulsory` tinyint(1) NOT NULL,
  `categoryfilter` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `own_table` varchar(100) NOT NULL,
  `validate_type` varchar(100) NOT NULL,
  `css_class` varchar(100) NOT NULL,
  `style_attr` text NOT NULL,
  `search` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `help` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `istatus` (`status`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_fields_assoc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(50) DEFAULT NULL,
  `assoc_field` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_fields_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`),
  KEY `cid` (`cid`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_fields_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ixfid` (`fid`),
  KEY `ixordering` (`ordering`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_fields_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldid` int(11) NOT NULL,
  `templatepage` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `ixfieldid` (`fieldid`),
  KEY `ixpage` (`templatepage`),
  KEY `ixpageposition` (`templatepage`,`position`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_invites` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`auctionId` INT(11) NOT NULL,
	`guestId` INT(11) NOT NULL,
	`guestType` ENUM('user','group') NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `auctionId` (`auctionId`),
	INDEX `guestId` (`guestId`),
	INDEX `guestType` (`guestType`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority` enum('log','notice','warning','error','system') DEFAULT 'log',
  `event` varchar(50) DEFAULT NULL,
  `logtime` datetime DEFAULT NULL,
  `log` text,
  PRIMARY KEY (`id`),
  KEY `ixeventtype` (`event`),
  KEY `ixdate` (`logtime`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_type` varchar(250) DEFAULT NULL,
  `content` text,
  `subject` varchar(250) DEFAULT NULL,
  `enabled` int(1) unsigned zerofill DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ixmailtype` (`mail_type`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `userid1` int(11) NOT NULL DEFAULT '0',
  `userid2` int(11) NOT NULL DEFAULT '0',
  `parent_message` int(11) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `bid_id` int(11) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wasread` int(1) DEFAULT '0',
  `private` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Public | 0, Private | 1',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `iuserid1` (`userid1`),
  KEY `iuserid2` (`userid2`),
  KEY `iparent` (`parent_message`),
  KEY `ibid` (`bid_id`)
);

CREATE TABLE `#__rbid_payment_balance` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`userid` INT(11) NULL DEFAULT NULL,
	`balance` DECIMAL(11,2) NULL DEFAULT NULL,
	`req_withdraw` DECIMAL(11,2) NULL DEFAULT '0.00' COMMENT 'Requested withdraw amount',
	`last_withdraw_date` DATE NULL DEFAULT NULL,
	`paid_withdraw_date` DATE NULL DEFAULT NULL COMMENT 'Date when an admin paid withdraw amount requested',
	`withdrawn_until_now` DECIMAL(11,2) NULL DEFAULT '0.00' COMMENT 'Withdraw until now',
	`currency` VARCHAR(20) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `ixuserid` (`userid`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_payment_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `amount` decimal(9,2) DEFAULT NULL,
  `currency` varchar(11) DEFAULT NULL,
  `refnumber` varchar(100) DEFAULT NULL,
  `invoice` varchar(50) DEFAULT NULL,
  `ipn_response` text,
  `ipn_ip` varchar(100) DEFAULT NULL,
  `status` enum('ok','error','manual_check','cancelled','refunded') DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `orderid` int(11) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ixdate` (`date`),
  KEY `ixuserid` (`userid`),
  KEY `ixstatus` (`status`),
  KEY `ixref` (`refnumber`),
  KEY `ixinvoice` (`invoice`),
  KEY `ixobjectid` (`orderid`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_payment_orderitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) DEFAULT NULL,
  `itemname` varchar(30) DEFAULT NULL,
  `itemdetails` varchar(250) DEFAULT NULL,
  `iteminfo` varchar(150) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `quantity` int(11) DEFAULT '1',
  `params` text,
  PRIMARY KEY (`id`),
  KEY `ixorderid` (`orderid`),
  KEY `ixiteminfo` (`iteminfo`),
  KEY `ixitemname` (`itemname`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_payment_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderdate` datetime DEFAULT NULL,
  `modifydate` datetime DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `order_total` decimal(11,2) DEFAULT NULL,
  `order_currency` varchar(10) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `paylogid` int(11) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_paysystems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paysystem` varchar(50) DEFAULT NULL,
  `classname` varchar(50) DEFAULT NULL,
  `enabled` int(1) DEFAULT '1',
  `params` text,
  `ordering` int(11) DEFAULT NULL,
  `isdefault` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `picture` varchar(100) NOT NULL DEFAULT '',
  `modified` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `ixauctionid` (`auction_id`),
  KEY `ixuserid` (`userid`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(50) DEFAULT NULL,
  `pricetype` enum('percent','fixed') DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `currency` varchar(11) DEFAULT NULL COMMENT 'Will be updated by cron to default currency',
  `enabled` int(1) DEFAULT NULL,
  `params` text,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ixitemname` (`itemname`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_pricing_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `icat` (`category`),
  KEY `ipriceitem` (`itemname`)
);

CREATE TABLE IF NOT EXISTS  `#__rbid_pricing_comissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `auction_id` int(11) DEFAULT NULL,
  `bid_id` int(11) NOT NULL,
  `comission_date` datetime DEFAULT NULL,
  `amount` decimal(9,2) DEFAULT NULL,
  `currency` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ixuserid` (`userid`),
  KEY `ixauctionid` (`auction_id`),
  KEY `ixbidis` (`bid_id`)
);

CREATE TABLE IF NOT EXISTS  `#__rbid_pricing_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ixuserid` (`userid`),
  KEY `ixcontactid` (`contact_id`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter` int(11) NOT NULL DEFAULT '0',
  `user_rated` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) DEFAULT '0',
  `modified` date NOT NULL DEFAULT '0000-00-00',
  `message` text,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `rate_type` enum('bidder','auctioneer') DEFAULT NULL,
  `rate_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ixuser` (`user_rated`),
  KEY `ixvoter` (`voter`),
  KEY `ixauctionid` (`auction_id`)
);

CREATE TABLE IF NOT EXISTS  `#__rbid_report_auctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `message` varchar(200) NOT NULL DEFAULT '',
  `processing` int(11) NOT NULL DEFAULT '0',
  `solved` int(11) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `ixuserid` (`userid`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `tagname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ixauctions` (`auction_id`),
  KEY `ixtags` (`tagname`)
);

CREATE TABLE `#__rbid_users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`userid` INT(11) NOT NULL DEFAULT '0',
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`surname` VARCHAR(50) NOT NULL DEFAULT '',
	`address` VARCHAR(150) NOT NULL DEFAULT '',
	`city` VARCHAR(50) NOT NULL DEFAULT '',
	`country` VARCHAR(150) NOT NULL DEFAULT '',
	`phone` TEXT NOT NULL,
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`rating` INT(11) NOT NULL DEFAULT '0',
	`paypalemail` VARCHAR(255) NULL DEFAULT NULL,
	`paypalemail_is_visible` TINYINT(1) NULL DEFAULT '1',
	`activity_domains` TEXT NULL,
	`about_me` TEXT NULL,
	`YM` VARCHAR(255) NULL DEFAULT NULL,
	`YM_is_visible` TINYINT(1) NULL DEFAULT '1',
	`Hotmail` VARCHAR(255) NULL DEFAULT NULL,
	`Hotmail_is_visible` TINYINT(1) NULL DEFAULT '1',
	`Skype` VARCHAR(255) NULL DEFAULT NULL,
	`Skype_is_visible` TINYINT(1) NULL DEFAULT '1',
	`Linkedin` VARCHAR(255) NULL DEFAULT NULL,
	`Linkedin_is_visible` TINYINT(1) NULL DEFAULT '1',
	`Facebook` VARCHAR(255) NULL DEFAULT NULL,
	`Facebook_is_visible` TINYINT(1) NULL DEFAULT '1',
	`googleMaps_x` VARCHAR(255) NULL DEFAULT NULL,
	`googleMaps_y` VARCHAR(255) NULL DEFAULT NULL,
	`verified` INT(1) NULL DEFAULT '0',
	`powerseller` INT(1) NULL DEFAULT '0',
	`user_test` VARCHAR(250) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `userid` (`userid`),
	INDEX `ixname` (`name`),
	INDEX `ixsurname` (`surname`),
	INDEX `ixcity` (`city`),
	INDEX `ixcountry` (`country`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_watchlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `auction_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ixauctionid` (`auction_id`),
  KEY `ixuserid` (`userid`)
);

CREATE TABLE IF NOT EXISTS `#__rbid_watchlist_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ixcatid` (`catid`),
  KEY `ixuserid` (`userid`)
);

CREATE TABLE IF NOT EXISTS `#__rbids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `bid_price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `comments` text NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `cancel` int(11) DEFAULT NULL,
  `accept` tinyint(4) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_auctionid` (`auction_id`),
  KEY `ix_userid` (`userid`),
  KEY `ixaccept` (`accept`)
);

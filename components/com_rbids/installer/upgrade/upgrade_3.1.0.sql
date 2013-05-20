ALTER TABLE `#__rbid_users`
	CHANGE COLUMN `AreasOfExpertise` `activity_domains` TEXT NULL AFTER `paypalemail`,
	CHANGE COLUMN `Resume` `about_me` TEXT NULL AFTER `activity_domains`,
	ADD COLUMN `Linkedin` VARCHAR(255) NULL DEFAULT '1' AFTER `Skype`,
	ADD COLUMN `Facebook` VARCHAR(255) NULL DEFAULT '1' AFTER `Linkedin`,
	ADD COLUMN `YM_is_visible` TINYINT(1) NULL DEFAULT '1' AFTER `YM`,
	ADD COLUMN `Skype_is_visible` TINYINT(1) NULL DEFAULT '1' AFTER `Skype`,
	ADD COLUMN `Hotmail_is_visible` TINYINT(1) NULL DEFAULT '1' AFTER `Hotmail`,
	ADD COLUMN `Linkedin_is_visible` TINYINT(1) NULL DEFAULT '1' AFTER `Linkedin`,
	ADD COLUMN `Facebook_is_visible` TINYINT(1) NULL DEFAULT '1' AFTER `Facebook`,
	ADD COLUMN `paypalemail_is_visible` TINYINT(1) NULL DEFAULT '1' AFTER `paypalemail`;

UPDATE `#__rbid_fields_assoc` SET `field` = 'activity_domains' WHERE `field` = 'AreasOfExpertise';
UPDATE `#__rbid_fields_assoc` SET `field` = 'about_me'	WHERE `field` = 'Resume';

INSERT INTO `#__rbid_fields_assoc` VALUES ('19', 'Linkedin', '');
INSERT INTO `#__rbid_fields_assoc` VALUES ('20', 'Facebook', '');

INSERT INTO `#__rbid_mails` (`mail_type`,`content`) VALUES (
	'bid_new_invite','Dear %NAME% %SURNAME%,  You have an invitation for new auction - %AUCTIONTITLE% .');

CREATE TABLE `#__rbid_invites` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`auctionId` INT(11) NOT NULL,
	`guestId` INT(11) NOT NULL,
	`guestType` ENUM('user','group') NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `auctionId` (`auctionId`),
	INDEX `guestId` (`guestId`),
	INDEX `guestType` (`guestType`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

ALTER TABLE `#__rbid_auctions`
	ADD COLUMN `max_price_default_currency` DECIMAL(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Max Price converted to default currency' AFTER `currency`;
ALTER TABLE `#__rbid_pricing`
	CHANGE COLUMN `currency` `currency` VARCHAR(11) NULL DEFAULT NULL COMMENT 'Will be updated by cron to default currency' AFTER `price`;
ALTER TABLE `#__rbids`
	ADD COLUMN `file_name` VARCHAR(255) NOT NULL AFTER `comments`;

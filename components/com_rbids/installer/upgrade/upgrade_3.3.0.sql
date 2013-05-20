ALTER TABLE `#__rbid_payment_balance`
	ADD COLUMN `req_withdraw` DECIMAL(11,2) NULL DEFAULT '0.00' COMMENT 'Requested withdraw amount' AFTER `balance`,
	ADD COLUMN `withdraw_until_now` DECIMAL(11,2) NULL DEFAULT '0.00' COMMENT 'Withdraw until now' AFTER `req_withdraw`,
	ADD COLUMN `last_withdraw_date` DATE NULL AFTER `req_withdraw`,
	ADD COLUMN `paid_withdraw_date` DATE NULL DEFAULT NULL COMMENT 'Date when an admin paid withdraw amount requested' AFTER `last_withdraw_date`;

INSERT INTO `#__rbid_mails` (`mail_type`, `content`, `subject`, `enabled`) VALUES
('user_withdraw_funds', "Dear %NAME% %SURNAME%, \r\n\n Your request for withdrawal has been sent to an admin.", 'Your request for withdrawal has been sent', 1),
('admin_pay_requested_withdraw', "<p>Dear %NAME% %SURNAME%, \r\n\n  You have a new request for withdraw funds.</p>", 'You have a new request for withdraw funds', 1);

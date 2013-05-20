<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class RbidConfig
	{
		public $select_winner_automatic = '0';
		public $auctiontype_enable = '1';
		public $auctiontype_val = '2';
		public $auctionpublish_enable = '1';
		public $auctionpublish_val = '0';
		public $date_format = 'Y-m-d';
		public $date_time_format = 'h:iA';
		public $nr_items_per_page = '10';
		public $inner_categories = '1';
		public $enable_countdown = '1';
		public $availability = '3';
		public $archive = '3';
		public $max_nr_tags = '5';
		public $allow_messages = '1';
		public $allow_guest_messaging = '1';
		public $enable_captcha = '0';
		public $hide_contact = '0';
		public $nda_option = '1';
		public $nda_compulsory = '0';
		public $nda_extensions = 'txt,doc,pdf';
		public $enable_attach = '1';
		public $attach_compulsory = '0';
		public $attach_max_size = '1024';
		public $attach_extensions = 'zip,rar,txt';
		public $workflow = 'quick';
		public $disable_images = '0';
		public $max_picture_size = '2048';
		public $maxnr_images = '3';
		public $main_picture_require = '0';
		public $gallery = 'scrollgallery';
		public $thumb_width = '150';
		public $thumb_height = '150';
		public $medium_width = '500';
		public $medium_height = '500';
		public $bidder_groups = array("0" => "6", "1" => "7", "2" => "2", "3" => "3", "4" => "4", "5" => "5");
		public $seller_groups = array("0" => "2", "1" => "3", "2" => "4", "3" => "5", "4" => "8");
		public $google_key = '';
		public $googlemap_defx = '';
		public $googlemap_defy = '';
		public $googlemap_default_zoom = '7';
		public $googlemap_distance = '1';
		public $googlemap_unit_available = '5,25,60,100,150';
		public $googlemap_gx = '550';
		public $googlemap_gy = '450';
		public $googlemap_allowed_maps = array("0" => "Map", "1" => "Hybrid");
		public $recaptcha_public_key = '';
		public $recaptcha_private_key = '';
		public $recaptcha_theme = 'red';
		public $choose_antispam_bot = 'joomla';
		public $mailcaptcha_public_key = '';
		public $mailcaptcha_private_key = '';
		public $terms_and_conditions = '<p>Terms and Conditions</p>';
		public $enable_hour = '1';
		public $admin_approval = '0';
		public $enable_acl = '0';
		public $map_in_auction_details = '0';
		public $enable_antispam_bot = '0';
		public $profile_mode = 'component';
		public $theme = 'default';
		public $allow_messenger = '1';
		public $show_paypalemail = '1';
		public $allow_paypal = '1';
		public $cron_password = 'pass';
		public $googlemap_maptype = 'ROADMAP';
		public $enable_bid_attach = '1';
		public $bid_attach_compulsory = '0';
		public $allow_user_set_messenger_fields_visibility = '1';
		public $allow_sellers_edit = '1';
		public $allow_only_invited_users = '1';
		public $aucttype_invite_interface = 'both';
		public $enable_auctiontype_public = '1';
		public $enable_auctiontype_private = '1';
		public $enable_auctiontype_invite = '1';
		public $bid_accept_user_commision = '1';
	}

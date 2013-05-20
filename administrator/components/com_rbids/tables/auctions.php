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

	/**
	 * TableAuctions Class
	 */
	class TableAuctions extends FactoryFieldsTbl
	{

		var $id;
		var $userid;
		var $title;
		var $shortdescription;
		var $description;
		var $picture;
		var $link_extern;
		var $max_price;
		var $currency;
		var $max_price_default_currency;
		var $auction_type;
		var $automatic;
		var $payment;
		var $start_date;
		var $end_date;
		var $closed_date;
		var $published;
		var $close_offer;
		var $close_by_admin;
		var $hits;
		var $modified;
		var $winner_id;
		var $cat;
		var $has_file;
		var $file_name;
		var $job_deadline;
		var $show_bidder_nr;
		var $show_best_bid;
		var $NDA_file;
		var $NDA;
		var $featured;
		var $auction_nr;
		var $cancel_reason;
		var $googlex;
		var $googley;
		var $approved;

		/**
		 * @param string $db
		 */
		function __construct(&$db)
		{
			parent::__construct('#__rbid_auctions', 'id', $db, null, 'cat');
		}

		/**
		 * @param string $property
		 * @param null   $default
		 *
		 * @return mixed
		 */
		public function get($property, $default = null)
		{
			if (method_exists($this, 'get' . ucfirst($property))) { //Getter
				if (isset($this->_cache) && is_object($this->_cache) && isset($this->_cache->$property)) {
					return $this->_cache->$property;
				}
				$method = 'get' . ucfirst($property);
				$res = self::$method($default);
				$this->setCache($property, $res);
				return $res;
			}
			if (strpos($property, '.')) { //External object !
				$p = explode('.', $property);
				$class = 'RBidsHelper' . ucfirst($p[0]) . 'Auction';
				array_shift($p);
				if (count($p) == 1) $p = $p[0];
				if (class_exists($class))
					return call_user_func(array($class, 'get'), $this, $p);
			}
			return parent::get($property, $default);
		}

		/**
		 * @param $property
		 * @param $value
		 */
		function setCache($property, $value)
		{
			if (!isset($this->_cache) || !is_object($this->_cache)) {
				$this->_cache = new StdClass();
			}
			$this->_cache->$property = $value;
		}

		/**
		 * @param $auction_object
		 */
		function setCacheObject($auction_object)
		{
			//used to store extended properties got by model
			$this->_cache = $auction_object;
		}

		/**
		 * @return int
		 */
		function getFavorite()
		{
			$result = 0;
			$user = & JFactory::getUser();
			if ($user->id) {
				$db = $this->getDbo();
				$db->setQuery("select count(*) from `#__rbid_watchlist`  where `auction_id`={$this->id} and `userid`={$user->id}");
				$result = $db->loadResult() ? 1 : 0;
			}
			return $result;
		}

		/**
		 * @return null|string
		 */
		function getUsername()
		{
			$user =& JFactory::getUser($this->userid);
			return $user ? $user->username : "";
		}

		/**
		 * @return mixed
		 */
		function getCatname()
		{

			// Set the categories table directory
			JTheFactoryHelper::tableIncludePath('category');

			$cattable = JTable::getInstance('category', 'JTheFactoryTable');
			$cattable->load($this->cat);
			return $cattable->catname;
		}

		/**
		 * @return string
		 */
		function getStartDate_Text()
		{
			$cfg =& JTheFactoryHelper::getConfig();

			return JHtml::date($this->start_date, $cfg->date_format);
		}

		/**
		 * @return string
		 */
		function getEndDate_Text()
		{
			if ($this->end_date && $this->end_date != '0000-00-00 00:00:00') {
				$cfg =& JTheFactoryHelper::getConfig();
				$dateformat = ($cfg->enable_hour) ? ($cfg->date_format . ", " . $cfg->date_time_format) : ($cfg->date_format);
				return JHtml::_('date', $this->end_date, $dateformat);
			} else return "";
		}

		/**
		 * @return string
		 */
		function getCountdown()
		{
			return RBidsHelperDateTime::dateToCountdown($this->end_date);
		}

		/**
		 * @return bool
		 */
		function getExpired()
		{
			$diff = RBidsHelperDateTime::dateDiff($this->end_date);
			return ($diff > 0);
		}

		/**
		 * @return string
		 */
		function getTags()
		{
			if (!$this->id) return "";
			$tag_obj =& JTable::getInstance('tags', 'Table');
			return $tag_obj->getTagsAsString($this->id);
		}

		/**
		 * @return mixed
		 */
		function getGallery()
		{
			$images = array($this->picture);
			$ilist = $this->getImages();
			foreach ($ilist as $img)
				$images[] = $img->picture;

			$gallery = RBidsHelperGallery::getGalleryPlugin();
			$gallery->clearImages();
			$gallery->addImageList($images);

			return $gallery->getGallery();
		}

		/**
		 * @return mixed
		 */
		function getThumbnail()
		{
			$images = array($this->picture);
			$gallery = RBidsHelperGallery::getGalleryPlugin();
			$gallery->clearImages();
			$gallery->addImageList($images);

			return $gallery->getThumbImage();
		}

		/**
		 * @return mixed
		 */
		function getMust_Rate()
		{
			$user =& JFactory::getUser();
			$rateModel =& JModel::getInstance('ratings', 'rbidsModel');
			return $rateModel->canRate($user->id, $this->id);
		}

		/**
		 * @return int
		 */
		function getOwnerRating()
		{
			$user =& JFactory::getUser();
			$rateModel =& JModel::getInstance('ratings', 'rbidsModel');
			$rating = $rateModel->getUserRatings($this->userid);
			if (isset($rating['rating_overall'])) return $rating['rating_overall'];
			else return 0;
		}

		/**
		 * @return mixed|string
		 */
		function getLowest_Bid()
		{
			if ($this->show_best_bid || $this->isMyAuction()) {
				$db = $this->getDbo();
				$db->setQuery("SELECT MIN(bid_price) minprice FROM `#__rbids` WHERE `auction_id` = '{$this->id}' and cancel=0 ");
				return $db->loadResult();
			} else
				return "";
		}

		/**
		 * @return mixed|string
		 */
		function getNr_Bidders()
		{
			if ($this->show_bidder_nr || $this->isMyAuction()) {
				$db = $this->getDbo();
				$db->setQuery("SELECT count(distinct b.userid) FROM `#__rbids` b WHERE `auction_id` = '{$this->id}' and cancel=0 ");
				return $db->loadResult();
			} else
				return "-";
		}

		/**
		 * @return bool
		 */
		function getI_am_winner()
		{
			$user =& JFactory::getUser();

			if ($user->guest) return null;
			return ($user->id == $this->winner_id);
		}

		/**
		 * @return mixed|null
		 */
		function getWinning_bid()
		{
			if ($this->winner_id) {
				$db = $this->getDbo();
				$db->setQuery("SELECT bid_price FROM `#__rbids` WHERE `auction_id` = '{$this->id}' AND `userid` = '{$this->winner_id}' and `accept`=1 and cancel=0");
				return $db->loadResult();
			} else return null;
		}

		/**
		 * @return mixed|string
		 */
		function getNr_Bids()
		{
			if ($this->show_bidder_nr || $this->isMyAuction()) {
				$db = $this->getDbo();
				$db->setQuery("SELECT count(*) FROM `#__rbids` b WHERE `auction_id` = '{$this->id}' and cancel=0 ");
				return $db->loadResult();
			} else
				return "-";

		}

		/**
		 * @return JTheFactoryUserProfile|null
		 */
		function getWinner()
		{
			$winner = null;
			if ($this->winner_id) {
				$winner = clone RBidsHelperTools::getUserProfileObject();
				$winner->getUserProfile($this->winner_id);
			}
			return $winner;
		}

		/**
		 * @param $ext
		 *
		 * @return bool
		 */
		function isAllowedImage($ext)
		{
			return in_array(strtoupper($ext), array('JPEG', 'JPG', 'GIF', 'PNG'));
		}

		/**
		 * @return mixed
		 */
		function getImages()
		{
			$db = $this->getDbo();
			$db->setQuery("SELECT * FROM #__rbid_pictures WHERE auction_id = '{$this->id}'");
			return $db->loadObjectList();
		}

		/**
		 * @return mixed
		 */
		function getImageCount()
		{
			$db = $this->getDbo();
			$db->setQuery("SELECT COUNT(*) FROM `#__rbid_pictures` WHERE `auction_id` = '{$this->id}'");
			$nr = $db->loadResult();
			if ($this->picture) $nr++;
			return $nr;
		}


		/**
		 * @return bool
		 */
		function isMyAuction()
		{
			$my = & JFactory::getUser();
			if ($my->id && $my->id == $this->userid) return true;
			else return false;
		}

		/**
		 * isBidsPlaced - Class Method
		 *
		 *  Check if are bids placed for current auction
		 *
		 * @return bool
		 */
		public function isBidsPlaced()
		{
			$db = JFactory::getDbo();
			$query = "SELECT COUNT(*)
					  FROM `#__rbids`
					  WHERE `auction_id` = '{$this->id}'";

			$db->setQuery($query);
			if ($db->loadResult()) {
				return true;
			}
			return false;
		}

		/**
		 * Check custom fields availability
		 *
		 * @return bool
		 */
		public function isCustomFields()
		{
			return count($this->_custom_fields) ? true : false;
		}

		/**
		 * @return mixed
		 */
		function getMessages()
		{
			$user_sql = "";
			if ($this->auction_type == AUCTION_TYPE_PRIVATE && !$this->isMyAuction()) {
				$user =& JFactory::getUser();
				$userid = (int)$user->id;
				$user_sql = " AND (`m`.`userid1` IN (0, '" . $userid . "') OR `m`.`userid2` IN (0 ,'" . $userid . "'))"; //Only Mine and Guest messages
			}

			$sql = "SELECT `m`.*,
					     `u`.`username` AS `fromuser`,
					     `p`.`username` AS `touser`
				     FROM `#__rbid_messages` AS `m`
				     LEFT JOIN `#__users` AS `u` ON `m`.`userid1` = `u`.`id`
				     LEFT JOIN `#__users` AS `p` ON `m`.`userid2` = `p`.`id`
				     WHERE `m`.`auction_id` = '{$this->id}'
				     AND `published`=1
				     {$user_sql}
				     ORDER BY `m`.`modified` DESC
				 ";

			$db = $this->getDbo();
			$db->setQuery($sql);
			return $db->loadObjectList();
		}

		/**
		 * @return mixed
		 */
		function getBidList()
		{

			$my = & JFactory::getUser();
			$where = " WHERE `auction_id` = '{$this->id}' AND `b`.`cancel` = 0";

			if ($this->auction_type == AUCTION_TYPE_PRIVATE && !$this->isMyAuction())
				//Private  Auction
				$where .= "  AND `userid` = '{$my->id}'";

			$query = "SELECT `b`.*,
						`u`.`name`,
						`u`.`username`,
						(SELECT AVG(`r`.`rating`)
						     FROM `#__rbid_rate` AS `r`
						     WHERE `b`.`userid` = `r`.`user_rated`
						     AND `rate_type` = 'bidder'
						     GROUP BY `r`.`user_rated`) AS `rating`
					 FROM `#__rbids` AS `b`
					 LEFT JOIN `#__users` AS `u` ON `b`.`userid` = `u`.`id`
					 {$where}
				";
			$db =& $this->getDbo();
			$db->setQuery($query);
			return $db->loadObjectList();
		}

		/**
		 *
		 */
		function delete()
		{
			$db = $this->getDbo();
			$db->setQuery("SELECT * FROM #__rbid_pictures WHERE auction_id='$this->id'");
			$images = $db->loadObjectList();
			if (count($images)) {
				foreach ($images as $image) {
					if (file_exists(AUCTION_PICTURES_PATH . $image->picture)) {
						@unlink(AUCTION_PICTURES_PATH . $image->picture);
						@unlink(AUCTION_PICTURES_PATH . "middle_" . $image->picture);
						@unlink(AUCTION_PICTURES_PATH . "resize_" . $image->picture);
					}
				}
			}

			if (file_exists(AUCTION_PICTURES_PATH . $this->picture)) {
				@unlink(AUCTION_PICTURES_PATH . $this->picture);
				@unlink(AUCTION_PICTURES_PATH . "middle_" . $this->picture);
				@unlink(AUCTION_PICTURES_PATH . "resize_" . $this->picture);

			}
			if (file_exists(AUCTION_UPLOAD_FOLDER . "{$this->id}.nda"))
				@unlink(AUCTION_UPLOAD_FOLDER . "{$this->id}.nda");

			if (file_exists(AUCTION_UPLOAD_FOLDER . "{$this->id}.attach"))
				@unlink(AUCTION_UPLOAD_FOLDER . "{$this->id}.attach");

			$db->setQuery("DELETE FROM #__rbids WHERE auction_id='$this->id'"); //remove bids
			$db->query();
			$db->setQuery("DELETE FROM #__rbid_tags WHERE auction_id='$this->id'"); //remove tags
			$db->query();
			$db->setQuery("DELETE FROM #__rbid_pictures WHERE auction_id='$this->id'"); //remove pictures
			$db->query();
			$db->setQuery("DELETE FROM #__rbid_report_auctions WHERE auction_id='$this->id'"); //remove reports
			$db->query();
			$db->setQuery("DELETE FROM #__rbid_messages WHERE auction_id='$this->id'"); //remove messages
			$db->query();
			$db->setQuery("DELETE FROM #__rbid_auctions WHERE id='$this->id'"); //remove the auction
			$db->query();
		}

		/**
		 * sendNewMessage Class Method
		 *
		 * @param      $message
		 * @param null $message_to
		 * @param null $reply_to_message
		 * @param int  $is_private
		 *
		 * @return void
		 */
		function sendNewMessage($message, $message_to = null, $reply_to_message = null, $is_private = 0)
		{
			$my = & JFactory::getUser();
			$m =& JTable::getInstance('messages', 'Table');
			if (!$reply_to_message) {
				$m->parent_message = 0;
				if ($message_to)
					$m->userid2 = $message_to;
				else
					$m->userid2 = $this->userid;
			} else {
				$replytom =& JTable::getInstance('messages', 'Table');
				$replytom->load($reply_to_message);
				$replytom->wasread = 1;
				$replytom->store();

				$m->parent_message = $reply_to_message;
				$m->userid2 = $replytom->userid1;
			}

			$m->auction_id = $this->id;
			$m->userid1 = $my->id;
			$m->modified = gmdate('Y-m-d H:i:s');
			$m->message = $message;
			$m->private = $is_private;

			JTheFactoryEventsHelper::triggerEvent('onBeforeSendMessage', array($this, $m));
			$m->store();
			JTheFactoryEventsHelper::triggerEvent('onAfterSendMessage', array($this, $m));
		}

		/**
		 *
		 * @param $userlist
		 * @param $mailtype
		 */
		function SendMails($userlist, $mailtype)
		{

			$config = & JFactory::getConfig(); //joomla config
			$cfg = & JTheFactoryHelper::getConfig(); //rbids config
			$mail_from = $config->getValue("mailfrom");
			$sitename = $config->getValue("sitename");

			set_time_limit(0);
			ignore_user_abort();
			JTheFactoryHelper::tableIncludePath('mailman');
			$mail_body =& JTable::getInstance('MailmanTable', 'JTheFactory');
			if (!$mail_body->load($mailtype)) return;
			if (!$mail_body->enabled) return;
			if (count($userlist) <= 0) return;
			// single query
			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$catname = $catModel->getCategoryPathString($this->cat);

			$user = clone RBidsHelperTools::getUserProfileObject();

			//query bidder email
			if ($this->winner_id > 0) {
				$query = "SELECT email FROM #__users WHERE id= '{$this->winner_id}'";
			} else {
				$query = "SELECT email FROM #__rbids b LEFT JOIN #__users u ON u.id = b.userid WHERE auction_id = '{$this->id}' AND cancel=0 ORDER BY b.bid_price ASC";
			}

			$db = $this->getDbo();
			$db->setQuery($query);
			$winnerEMAIL = $db->LoadResult();

			//query bidder phone
			if ($this->winner_id > 0) {
				$query = "SELECT phone FROM #__rbid_users WHERE userid= '{$this->winner_id}'";
			} else {
				$query = "SELECT phone FROM #__rbids b LEFT JOIN #__rbid_users u ON b.userid = u.userid WHERE b.auction_id= '{$this->id}' AND b.cancel=0 ORDER BY b.bid_price ASC";
			}

			$db->setQuery($query);
			$winnerPHONE = $db->LoadResult();

			foreach ($userlist as $u) {

				$user->getUserProfile($u->id);

				if (!$user->email) {
					continue;
				}

				$dateformat = ($cfg->enable_hour) ? ($cfg->date_format . " " . $cfg->date_time_format) : ($cfg->date_format);

				$url = JRoute::_(JURI::root() . 'index.php?option=com_rbids&task=viewbids&id=' . $this->id);

				$patterns = array('%NAME%', '%SURNAME%', '%CATTITLE%', '%AUCTIONTITLE%', '%AUCTIONDESCR%', '%STARTDATE%', '%ENDDATE%', '%AUCTIONLINK%', '%MAXPRICE%', '%AUCTIONEEREMAIL%', '%WINNEREMAIL%', '%AUCTIONEERPHONE%', '%WINNERPHONE%', '%LOWESTBID%', '%WINNINGBID%', '%CURRENCY%');
				$replacements = array($u->name, $u->surname, $catname, $this->title, $this->description, JHtml::date($this->start_date, $cfg->date_format, false), JHtml::date($this->end_date, $dateformat, false), $url, $this->max_price, $user->email, $winnerEMAIL, $user->phone, $winnerPHONE, $this->get('lowest_bid'), $this->get('winning_bid'), $this->currency);

				$subj = str_replace($patterns, $replacements, $mail_body->subject);
				$mess = str_replace($patterns, $replacements, $mail_body->content);

				JUTility::sendMail($mail_from, $sitename, $user->email, $subj, $mess, true);
			}
		}

		/**
		 *
		 */
		function ChooseWinner()
		{
			//Chooses the Winner in the current auction

			if (!$this->id) return; // must be loaded

			$usr = & JTable::getInstance("user");
			$bid = & JTable::getInstance("RBids", "Table");

			$query = "SELECT b.*
					 FROM #__rbids b
					 WHERE b.cancel=0
					 AND b.auction_id = $this->id
					 AND bid_price > 0
					 ORDER BY bid_price+0 ASC, modified DESC limit 1
				";

			$db = $this->getDbo();
			$db->setQuery($query);
			$winner = $db->LoadObject();

			if ($winner) {
				$bid->id = $winner->id;
				$bid->accept = 1;
				JTheFactoryEventsHelper::triggerEvent('onBeforeAcceptBid', array($this, $bid));
				$bid->store();

				$this->winner_id = $winner->userid;
				$this->store(true);
				JTheFactoryEventsHelper::triggerEvent('onAfterAcceptBid', array($this, $bid));

			} else {
				$usr->load($this->userid);
				$this->SendMails(array($usr), 'bid_choose_winner');
			}

		}

		/**
		 * @return bool
		 */
		function loadFromSession()
		{
			$session = & JFactory::getSession();
			if (!$session->has("temp_auction", "rbids"))
				return false;

			$c = $session->get("temp_auction", null, "rbids");
			$c = unserialize($c);
			if (is_object($c)) {
				$fields = get_object_vars($c);
				foreach ($fields as $k => $v)
					if (!is_object($c->$k) && !(substr($k, 0, 1) == '_'))
						$this->$k = $c->$k;
			}
			unset($c);
			$session->clear("temp_auction", "rbids");

		}

		/**
		 *
		 */
		function saveToSession()
		{
			$session = & JFactory::getSession();
			$c = new stdClass(); //due to weird errors the object must be dumbed down
			$fields = get_object_vars($this);
			foreach ($fields as $k => $v)
				if (!is_object($this->$k) && !(substr($k, 0, 1) == '_'))
					$c->$k = $this->$k;
			$session->set("temp_auction", serialize($c), "rbids");

		}

		/**
		 *
		 */
		function clearSavedSession()
		{
			$session = & JFactory::getSession();
			$session->clear("temp_auction", "rbids");
		}

		/**
		 * isEditable
		 *
		 * @return bool
		 */
		function isEditable()
		{
			$cfg = & JTheFactoryHelper::getConfig(); //rbids config
			$my = JFactory::getUser();
			$isSuperAdmin = $my->authorise('core.admin');

			if ($isSuperAdmin) {
				return true;
			}

			return (boolean)$cfg->allow_sellers_edit;
		}

		/**
		 * isInvited
		 *
		 * @internal param $auction
		 *
		 * @return bool
		 */
		public function isInvited()
		{
			$my = JFactory::getUser();

			// Guest not allowed by default
			if (!$my->id) {
				return false;
			}


			// Get user groups
			$userGroups = implode(',', $my->groups);

			// Filter by auction id if present
			$aucId = '';
			if ($this->id) $aucId = "AND `a`.`id` = '{$this->id}'";

			$db = JFactory::getDbo();
			$db->setQuery("SELECT COUNT(*)
						 FROM `#__rbid_auctions` AS `a`
						 LEFT JOIN `#__rbid_invites` AS `i`
						 ON `a`.`id` = `i`.`auctionId`
						 WHERE ((`i`.`guestType` = 'user' AND `i`.`guestId` = '{$my->id}')
						 OR (`i`.`guestType` = 'group' AND `i`.`guestId` IN ('{$userGroups}') ))
						 {$aucId}
				");

			$isInvited = $db->loadResult();

			return $isInvited ? true : false;


		}
	} // End Class

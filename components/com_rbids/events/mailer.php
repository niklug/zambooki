<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : RBids
	 * @subpackage: Events
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventMailer extends JTheFactoryEvents
	{
		/**
		 * @param $auction
		 */
		public function onAfterCancelAuction($auction)
		{
			$database = & JFactory::getDbo();

			$q = "SELECT  `u`.*
				   FROM  `#__users` AS `u`
				   LEFT JOIN `#__rbids` AS `b` ON `u`.`id` = `b`.`userid`
				   WHERE `b`.`userid` IS NOT NULL
				   AND `b`.`auction_id` = '{$auction->id}'
				   AND `b`.`cancel` = 0
			";
			$database->setQuery($q);
			$usermails = $database->loadObjectList();

			$query = "SELECT `u`.*
					  FROM `#__rbid_watchlist` AS `w`
					  LEFT JOIN `#__users` AS `u` ON `w`.`userid` = `u`.`id`
					  WHERE `w`.`auction_id` = '{$auction->id}'
				";
			$database->setQuery($query);
			$watchlist_mails = $database->loadObjectList();

			$auction->SendMails($watchlist_mails, 'bid_watchlist_canceled');
			$auction->SendMails($usermails, 'bid_canceled');
		}

		/**
		 * @param $auction
		 * @param $bid
		 */
		public function onAfterSaveBid($auction, $bid)
		{

			if ($bid->cancel) return; //not published yet

			$owner_user = & JTable::getInstance("user");
			$owner_user->load($auction->userid);
			$auction->SendMails(array($owner_user), 'new_bid');

			/*		if ($auction->auction_type == AUCTION_TYPE_PUBLIC) {

						$database = & JFactory::getDbo();
						$query = "SELECT `u`.*
								 FROM `#__rbid_watchlist` AS `w`
								 LEFT JOIN `#__users` AS `u` ON `w`.`userid` = `u`.`id`
								 WHERE `w`.`auction_id` = '{$auction->id}'
							";

						$database->setQuery($query);
						$watches = $database->loadObjectList();
						$auction->SendMails($watches, 'new_bid');
					}*/
		}

		/**
		 * Event for email notify
		 *
		 * @param object $auction       Auctions Table Object
		 * @param int    $to            USer id that receive rate
		 */
		public function onAfterRateSuccessfully($auction, $to)
		{
			$toUserObj = JFactory::getUser($to);
			$auction->SendMails(array($toUserObj), "bid_rate");
		}

		/**
		 * @param $auction
		 * @param $message
		 */
		public function onAfterSendMessage($auction, $message)
		{
			$usr = & JTable::getInstance("user");
			$usr->load($message->userid2);
			$auction->SendMails(array($usr), "new_message");
		}

		/**
		 * @param $auction
		 * @param $message
		 */
		public function onAfterBroadcastMessage($auction, $message)
		{
			$database = & JFactory::getDbo();
			$database->setQuery("SELECT DISTINCT `u`.*
							   FROM `#__users` AS `u`
							   LEFT JOIN `#__rbid_messages` AS `m` ON `m`.`userid1` = `u`.`id`
							   WHERE `auction_id` = '{$auction->id}'
						");
			$usr1 = $database->loadObjectList();

			$database->setQuery("SELECT DISTINCT `u`.*
							   FROM `#__users` AS `u`
							   LEFT JOIN `#__rbids` AS `b` ON `b`.`userid` = `u`.`id`
							   WHERE `b`.`cancel` = 0
							   AND `auction_id` = '{$auction->id}'
						");
			$usr2 = $database->loadObjectList();

			$usr = array_merge($usr1, $usr2);
			$auction->SendMails($usr, "new_broadcast_message");

		}

		/**
		 * @param $auction
		 * @param $bid
		 */
		public function onAfterAcceptBid($auction, $bid)
		{
			$database = & JFactory::getDbo();
			$user1 = JFactory::getUser($auction->winner_id);
			$auction->SendMails(array($user1), 'bid_accepted');


			$query = "SELECT DISTINCT `u`.*
					  FROM `#__rbids` AS `b`
					  LEFT JOIN `#__users` AS `u` ON `b`.`userid` = `u`.`id`
					  WHERE `b`.`cancel` = 0
					  AND `b`.`accept` = 0
					  AND `u`.`block` = 0
					  AND `b`.`auction_id` = '{$auction->id}'
				";
			$database->setQuery($query);
			$loser = $database->loadObjectList();
			$auction->SendMails($loser, 'bid_lost');

			$query = "SELECT `u`.*
					  FROM `#__rbid_watchlist` AS `w`
					  LEFT JOIN `#__users` AS `u` ON `w`.`userid` = `u`.`id`
					  WHERE `w`.`auction_id` = '{$auction->id}'
   				  ";
			$database->setQuery($query);
			$watches = $database->loadObjectList();
			$auction->SendMails($watches, 'bid_watchlist_closed');

			$auctioneer = JFactory::getUser($auction->userid);
			$auction->SendMails(array($auctioneer), 'bauction_id_winner_to_owner');

		}

		/**
		 * @param $auction
		 */
		public function onAfterSaveAuctionSuccess($auction)
		{
                        //mail('npkorban@gmail.com', 'newaction', print_r($auction, true));
			$cfg =& JTheFactoryHelper::getConfig();
			$db = & JFactory::getDbo();
			$user = & JFactory::getUser();
			$auction->SendMails(array($user), 'new_auction_created');

			if ($auction->published) {
                            /* old code
				$query = "SELECT `u`.*
						  FROM `#__users` AS `u`
						  LEFT JOIN `#__rbid_watchlist_cats` AS `f` ON `f`.`userid` = `u`.`id`
						  WHERE `f`.`catid` = '{$auction->cat}'
						  AND `u`.`id` <> '{$user->id}'
					";
				$db->setQuery($query);
				$watches = $db->loadObjectList();
                             * */
                                //get category name by id
                                $query = "SELECT catname FROM #__rbid_categories WHERE id='{$auction->cat}'";
                                $db->setQuery($query);
                                $catname = $db->loadResult();
                                //get list of users following action category
                                $query = "SELECT f.user_id as id, u.latitude, u.longitude FROM #__community_fields_values as f
                                    LEFT JOIN #__community_users as u ON f.user_id=u.userid
                                    WHERE f.field_id='19' 
                                    AND f.value LIKE'%$catname%'
                                    AND f.user_id <> '{$user->id}'";
                                $db->setQuery($query);
                                $watches = $db->loadObjectList();
                                                
                                            
                                $c = 0;
                                foreach ($watches as $watcher) {
                                    //if($watcher->user_id != '3100') {
                                    //    unset($watches[$c]);
                                    //}
                                    $distance = $this->distance($watcher->latitude, $watcher->longitude, $auction->googlex, $auction->googley);
                                    $serviceArea = $this->getServiceArea($watcher->id);
                                    if($serviceArea) {
                                        if(($distance > $serviceArea)) {
                                            unset($watches[$c]);

                                        }
                                    }
                                    
                                    $c++;
                                }
                                
                                //mail('npkorban@gmail.com', 'new_auction_watch', print_r($watches, true));
                                
				$auction->SendMails($watches, 'new_auction_watch');
			}

			if ($cfg->admin_approval) {
				$query = "SELECT DISTINCT `u`.*
						  FROM `#__users` AS `u`
						  LEFT JOIN `#__user_usergroup_map` AS `m` ON `m`.`user_id` = `u`.`id`
						  LEFT JOIN `#__usergroups` AS `g` ON `m`.`group_id` = `g`.`id`
						  WHERE `g`.`title` = 'Super Users'
						  OR `g`.`title` = 'Administrator'
					";
				$db->setQuery($query);
				$admins = $db->loadObjectList();

				$auction->SendMails($admins, 'bid_admin_approval');
			}


		}
                
                public function getServiceArea($user_id) {
                    $db = & JFactory::getDbo();
                    $query = "SELECT value FROM #__community_fields_values WHERE user_id='$user_id' AND field_id='24'";
                    $db->setQuery($query);
                    $result = $db->loadResult();
                    return $result;
                }
                
                
                /** calculate distance between two places 
                 * 
                 * @param type $lat1
                 * @param type $lng1
                 * @param type $lat2
                 * @param type $lng2
                 * @param type $miles
                 * @return type
                 */
                public     function distance($lat1, $lng1, $lat2, $lng2, $miles = true)
                {
                    $pi80 = M_PI / 180;
                    $lat1 *= $pi80;
                    $lng1 *= $pi80;
                    $lat2 *= $pi80;
                    $lng2 *= $pi80;

                    $r = 6372.797; // mean radius of Earth in km
                    $dlat = $lat2 - $lat1;
                    $dlng = $lng2 - $lng1;
                    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                    $km = $r * $c;

                    return ($miles ? ($km * 0.621371192) : $km);
                 }

		/**
		 * @param $auction
		 */
		public function onAfterCloseAuction($auction)
		{
			$db = & JFactory::getDbo();
			//Notify users who have bids;
			$query = "SELECT DISTINCT `u`.*
					  FROM `#__rbids` AS `a`
					  LEFT JOIN `#__users` AS `u` ON `a`.`userid` = `u`.`id`
					  WHERE `a`.`auction_id` = {$auction->id} AND `a`.`cancel` = 0
				";
			$db->setQuery($query);
			$users_with_bids = $db->loadObjectList();
			$auction->SendMails($users_with_bids, 'bid_closed');


			//Notify  Watchlist, Clean Watchlist
			$query = "SELECT DISTINCT `u`.*
					  FROM `#__rbid_watchlist` AS `a`
					  LEFT JOIN `#__users` AS `u` ON `a`.`userid` = `u`.`id`
					  WHERE `a`.`auction_id` = {$auction->id}
				 ";
			$db->setQuery($query);
			$users_with_watchlist = $db->loadObjectList();
			$auction->SendMails($users_with_watchlist, 'bid_watchlist_closed');

			$query = "DELETE FROM `#__rbid_watchlist` WHERE `auction_id` = {$auction->id}";
			$db->setQuery($query);
			$db->query();

		}

		/**
		 * @param $userid
		 */
		public function onAfterReqWithdraw($userid)
		{
			$db = & JFactory::getDbo();
			$auction = JTable::getInstance('auctions', 'Table');
			$user = & JTable::getInstance("user");
			$user->load($userid);
			// Notify users that has requested withdraw funds
			$auction->SendMails(array($user), 'user_withdraw_funds');

			$query = "SELECT DISTINCT `u`.*
						  FROM `#__users` AS `u`
						  LEFT JOIN `#__user_usergroup_map` AS `m` ON `m`.`user_id` = `u`.`id`
						  LEFT JOIN `#__usergroups` AS `g` ON `m`.`group_id` = `g`.`id`
						  WHERE `g`.`title` = 'Super Users'
						  OR `g`.`title` = 'Administrator'
					";
			$db->setQuery($query);
			$admins = $db->loadObjectList();

			// Notify admins related to new users withdraw request
			$auction->SendMails($admins, 'admin_pay_requested_withdraw');

		}
	}

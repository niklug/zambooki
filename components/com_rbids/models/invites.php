<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	-------------------------------------------------------------------------*/


	defined('_JEXEC') or die('Restricted access');

	jimport('joomla.application.component.model');

	/**
	 * rbidsModelInvites
	 */
	class rbidsModelInvites extends JModel
	{

		private $invites = array();
		private $auction = null;

		/**
		 * loadAuction
		 *
		 * @param $auctionId
		 *
		 * @return mixed
		 */
		public function loadAuction($auctionId)
		{
			$this->auction = JTable::getInstance('auctions', 'Table');
			return $this->auction->load($auctionId);
		}

		/**
		 * loadInvites
		 *
		 * @param       $guestType
		 * @param array $almostInvited
		 *
		 * @return array
		 */
		public function loadInvites($guestType, $almostInvited = array())
		{
			// Prepend new selection to
			// users already invited
			if (count($almostInvited)) {
				$this->invites[$guestType] = $almostInvited;
			}
			if (!$this->getAuction()->id) {
				return in_array($guestType, $this->invites) ? $this->invites[$guestType] : array();
			}

			$db = JFactory::getDbo();
			$db->setQuery('SELECT `guestId`
						 FROM `#__rbid_invites`
						 WHERE `guestType` = ' . $db->Quote($guestType) . '
						 AND `auctionId` = ' . $this->getAuction()->id
			);
			$objInvited = $db->loadObjectList();
			// Transform Array of Objects in
			// Array of Arrays for compatibility with rest of code
			foreach ($objInvited as $k => $v) {
				$this->invites[$guestType][$k] = $v->guestId;
			}

			return in_array($guestType, $this->invites) ? $this->invites[$guestType] : array();
		}

		/**
		 * getInvites
		 *
		 * @return array
		 */
		public function getInvites()
		{
			return $this->invites;
		}

		/**
		 * getAuction
		 *
		 * @return null
		 */
		public function getAuction()
		{
			return $this->auction;
		}

		/**
		 * save
		 *
		 * @param $guestType
		 * @param $invitedIds
		 */
		public function save($guestType, $invitedIds)
		{

			$db = JFactory::getDbo();
			$my = JFactory::getUser();

			$auct = $this->getAuction();


			//select all invites for this auction
			$db->setQuery('SELECT `guestId` FROM `#__rbid_invites` WHERE `guestType` = ' . $db->Quote($guestType) . ' AND `auctionId` = ' . $auct->id);
			$oldInvited = $db->loadResultArray();
			//delete invites
			$deleteInvited = array_diff($oldInvited, $invitedIds);

			if (count($deleteInvited)) {
				$db->setQuery('DELETE FROM `#__rbid_invites`
							 WHERE `guestType` = ' . $db->Quote($guestType) . '
							 AND `auctionId` = ' . $auct->id . '
							 AND `guestId` IN (' . $db->escape(implode(',', array_filter($deleteInvited))) . ')
						 ');
				$db->query();
				//delete bids matching invites
				switch ($guestType) {
					case 'user':
						$db->setQuery('DELETE FROM `#__rbids`
									 WHERE `auction_id` = ' . $auct->id . '
									 AND `userid` IN(' . $db->escape(implode(',', array_filter($deleteInvited))) . ')
							');
						break;
					case 'group':
						$db->setQuery("DELETE `b`
									 FROM `#__rbids` AS `b`
									 LEFT JOIN `#__users` AS `u` ON `b`.`userid` = `u`.`id`
									 RIGHT JOIN `#__user_usergroup_map` AS `g` ON  `g`.`group_id` IN({$db->escape(implode(',', array_filter($deleteInvited)))})
														                         AND `g`.`user_id` = `b`.`userid`
									 WHERE `b`.`auction_id` = '{$auct->id}'
								");
						break;
				}
				$db->query();
			}

			//save new invites
			$newInvited = array_diff($invitedIds, $oldInvited);
			$vals = array();

			foreach ($newInvited as $k => $id) {
				if (empty($id)) continue;
				if ('user' == $guestType && $id == $auct->userid) {
					unset($newInvited[$k]);
					continue;
				}
				$vals[] = $auct->id . ',' . $db->escape($id) . ',' . $db->Quote($guestType);
			}

			if (count($vals)) {
				$q = "INSERT INTO `#__rbid_invites` (`auctionId`,`guestId`,`guestType`) VALUES (" . implode('),(', $vals) . ")";
				$db->setQuery($q);
				$db->query();
			}

			//send mails to new guests
			if (count($newInvited) && $this->getAuction()->published) {
				switch ($guestType) {
					case 'user':
						$db->setQuery("SELECT * FROM `#__users` WHERE `id` IN (" . $db->escape(implode(',', array_filter($newInvited))) . ")");
						break;
					case 'group':
						$db->setQuery("SELECT * FROM `#__users` AS `u`
										   LEFT JOIN `#__user_usergroup_map` AS `g`  ON `g`.`user_id` = `u`.`id`
						                                   WHERE `g`.`group_id` IN({$db->escape(implode(',', array_filter($newInvited)))})
						                                   AND `u`.`id` <> {$my->id}
						");
						break;
				}
				$mails = $db->loadObjectList();

				$auct->SendMails($mails, 'bid_new_invite');
			}
		}
	} // End Class

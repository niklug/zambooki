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

	class RatingsController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		/**
		 * UserRatings
		 */
		public function UserRatings()
		{
			$userid = JRequest::getInt('id');

			if (!JFactory::getUser($userid)) {
				JError::raiseWarning(500, JText::_("COM_RBIDS_USER_DOES_NOT_EXIST"));
				return;
			}
			$userprofile = RBidsHelperTools::getUserProfileObject();
			$userprofile->getUserProfile($userid);

			$ratingsmodel =& JModel::getInstance('Ratings', 'rbidsModel');
			$lists['ratings'] = $ratingsmodel->getRatingsList($userid);
			$isMyRatings = 1;
			// Display top tabs only if is myratings displayed from my profile
			if ('userratings' == JRequest::getCmd('task')) {
				$isMyRatings = 0;
			}

			$view = $this->getView('ratings', JRequest::getWord('format', 'html'));

			$view->assignRef('user', clone $userprofile);
			$view->assignRef('isMyRatings', $isMyRatings);
			$view->assignRef('lists', $lists);
			$view->assign("generalrating", $ratingsmodel->getUserRatings($userid));

			$view->display("t_myratings.tpl");
		}

		/**
		 * showRateAuction
		 */
		public function showRateAuction()
		{
			$userid = JRequest::getInt('user_rated');
			$auctionid = JRequest::getInt('id');

			if (!JFactory::getUser($userid)) {
				JError::raiseWarning(500, JText::_("COM_RBIDS_USER_DOES_NOT_EXIST"));
				return;
			}
			$auction = & JTable::getInstance('Auctions', 'Table');
			if (!$auction->load($auctionid)) {
				JError::raiseWarning(500, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				return;
			}

			$userprofile = RBidsHelperTools::getUserProfileObject();
			$userprofile->getUserProfile($userid);

			$view = $this->getView('ratings', JRequest::getWord('format', 'html'));
			$view->assignRef('user', clone $userprofile);
			$view->assignRef('auction', $auction);

			$view->display("t_rateauction.tpl");
		}

		/**
		 * reviews_save
		 */
		public function reviews_save()
		{
			$user = & JFactory::getUser();

			$user_rated = JRequest::getInt("user_rated", 0);
			$vote = JRequest::getInt("vote", 0);
			$msg = JRequest::getVar("comment");
			$auction_id = JRequest::getInt("id", 0);
			$ret = JRequest::getVar("ret");
			$ip = JRequest::getVar('REMOTE_ADDR', '', 'server');

			$auction =& JTable::getInstance('auctions', 'Table');
			if (!$auction->load($auction_id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				return;
			}

			/* Check if Auction, Winner, Rateduser matches*/
			if ($user->id == $user_rated) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_YOU_CAN_NOT_RATE_YOURSELF"));
				return;
			}
			/* Check if Auction, Winner, Rateduser matches*/
			if ($auction->winner_id != $user->id && $auction->userid != $user->id) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_YOU_HAVE_NO_RIGHTS_TO_RATE_THIS_USER"));
				return;
			}
			if ($auction->winner_id != $user_rated && $auction->userid != $user_rated) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_YOU_HAVE_NO_RIGHTS_TO_RATE_THIS_USER"));
				return;
			}

			$db = & JFactory::getDBO();

			$db->setQuery("SELECT COUNT(*)
						  FROM `#__rbid_rate`
						  WHERE `voter`= {$user->id}
						  AND `user_rated`= {$user_rated}
						  AND `auction_id`= {$auction_id}
				");

			if ($db->loadResult() > 0) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_YOU_ALREADY_RATED_THIS_AUCTION"));
				return;
			}

			if ($auction->userid == $user_rated)
				$rate_type = 'auctioneer';
			else
				$rate_type = 'bidder';

			$db->setQuery("INSERT INTO `#__rbid_rate`
						    SET `modified` = NOW(),
						          `message` = '{$msg}',
						          `rating` = '{$vote}',
						          `voter` = '{$user->id}',
						          `user_rated` = '{$user_rated}',
						          `auction_id` = '{$auction_id}',
						          `rate_type` = '{$rate_type}',
						          `rate_ip` = '{$ip}'
					");
			if (false !== $db->query()) {
				JTheFactoryEventsHelper::triggerEvent('onAfterRateSuccessfully', array($auction, $user_rated));
			}

			if ($ret)
				$this->setRedirect(base64_decode($ret), JText::_("COM_RBIDS_AUCTION_RATED_SUCCESFULLY"));
			else
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id), JText::_("COM_RBIDS_RATED_SUCCESSFULLY"));

		}

		/**
		 * MyRatings
		 */
		public function MyRatings()
		{
			$user =& JFactory::getUser();
			JRequest::setVar('id', $user->id);
			return $this->UserRatings();
		}


	} // End Class

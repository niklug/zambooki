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

	class MessagesController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		function saveMessage()
		{

			$auction_id = JRequest::getInt('id');
			$cfg =& JTheFactoryHelper::getConfig();

			$redirect_link = RBidsHelperRoute::getAuctionDetailRoute($auction_id);
			if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
				$redirect_link = $_SERVER['HTTP_REFERER'];
			if (!$cfg->allow_messages) {
				$this->setRedirect($redirect_link, JText::_("COM_RBIDS_MESSAGING_IS_DISALLOWED"));
				return;
			}
			if ($cfg->enable_captcha) {
				if (!RBidsHelperCaptcha::verify_captcha()) {
					$this->setRedirect($redirect_link, JText::_("COM_RBIDS_CAPTCHA_ERROR"));
					return;
				}
			}

			$id_msg = JRequest::getInt("idmsg");
			$bidder_id = JRequest::getInt("bidder_id");
			$message = JRequest::getVar('message', '');
			$is_private = JRequest::getInt('msgisprivate');

			$auction =& JTable::getInstance('auctions', 'Table');

			if (!$auction->load($auction_id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect($redirect_link);
				return;
			}
			if (!$message) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_MESSAGE_CAN_NOT_BE_EMPTY"));
				$this->setRedirect($redirect_link);
				return;
			}

			if ($auction->close_offer) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_IS_CLOSED"));
				$this->setRedirect($redirect_link);
				return;
			}
			if ($auction->published != 1) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_IS_NOT_PUBLISHED"));
				$this->setRedirect($redirect_link);
				return;
			}
			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}

			if ($auction->isMyAuction()) {
				$auction->sendNewMessage($message, $bidder_id, $id_msg, $is_private);
			} else {
				$auction->sendNewMessage($message, null, null, $is_private);
			}
			$this->setRedirect($redirect_link, JText::_("COM_RBIDS_MESSAGE_SENT"));
		}

		function saveBroadcastMessage()
		{

			$my = &JFactory::getUser();
			$cfg =& JTheFactoryHelper::getConfig();
			$auction_id = JRequest::getInt('id', '');

			$auction =& JTable::getInstance('auctions', 'Table');

			if (!$cfg->allow_messages) {
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id), JText::_("COM_RBIDS_MESSAGING_IS_DISALLOWED"));
				return;
			}
			if (!$auction->load($auction_id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				return;
			}

			if ($auction->published != 1) {
				echo JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST");
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id));
				return;
			}
			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}

			$message = JRequest::getVar('message', '');

			if (empty($message)) {
				echo JText::_("COM_RBIDS_PLEASE_SPECIFY_A_MESSAGE");
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id));
				return;
			}

			$m =& JTable::getInstance('messages', 'Table');

			$m->auction_id = $auction->id;
			$m->parent_message = 0;
			$m->message = $message;
			$m->modified = gmdate('Y-m-d H:i:s');
			$m->userid1 = $my->id;

			JTheFactoryEventsHelper::triggerEvent('onBeforeBroadcastMessage', array($auction, $m));
			$m->store();
			JTheFactoryEventsHelper::triggerEvent('onAfterBroadcastMessage', array($auction, $m));

			$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id), JText::_("COM_RBIDS_MESSAGE_SENT"));

		}

	}

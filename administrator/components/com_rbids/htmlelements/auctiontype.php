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
	 * JHTMLAuctiontype
	 */
	abstract class JHTMLAuctiontype
	{
		/**
		 * @param string $name
		 * @param string $attributes
		 * @param null   $defaultvalue
		 *
		 * @return mixed
		 */
		static function selectlist($name = 'auction_type', $attributes = '', $defaultvalue = null)
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$opts = array();
			$opts[] = JHTML::_('select.option', '', JText::_("COM_RBIDS_SELECT_AUCTION_TYPE"));

			if ($cfg->enable_auctiontype_public) {
				$opts[] = JHTML::_('select.option', AUCTION_TYPE_PUBLIC, JText::_("COM_RBIDS_AUCTION_TYPE_PUBLIC"));
			}

			if ($cfg->enable_auctiontype_private) {
				$opts[] = JHTML::_('select.option', AUCTION_TYPE_PRIVATE, JText::_("COM_RBIDS_AUCTION_TYPE_PRIVATE"));
			}

			if ($cfg->enable_auctiontype_invite) {
				$opts[] = JHTML::_('select.option', AUCTION_TYPE_INVITE, JText::_("COM_RBIDS_AUCTION_TYPE_INVITE"));
			}

			return JHTML::_('select.genericlist', $opts, $name, $attributes, 'value', 'text', $defaultvalue);
		}

	} // End Class

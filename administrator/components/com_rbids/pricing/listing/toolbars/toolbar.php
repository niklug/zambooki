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
	 * @subpackage: Pay per listing
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JRBidAdminListingToolbar
	{
		function display($task = null)
		{
			JToolBarHelper::title(JText::_('COM_RBIDS_PAY_PER_LISTING__CONFIGURATION'));
			switch ($task) {
				default:
					JToolBarHelper::apply('pricing.save');
					JToolBarHelper::back();

					JSubMenuHelper::addEntry(
						JText::_('COM_RBIDS_PAYMENT_ITEMS'),
						'index.php?option=' . APP_EXTENSION . '&task=pricing.listing',
						false
					);
					break;
			}

		}
	}

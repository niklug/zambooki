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
	 * @subpackage: Comission
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JRBidAdminComissionToolbar
	{
		function display($task = null)
		{

			JToolBarHelper::title(JText::_('COM_RBIDS_COMMISSION'));
			JSubMenuHelper::addEntry(
				JText::_('COM_RBIDS_PAYMENT_ITEMS'),
				'index.php?option=' . APP_EXTENSION . '&task=pricing.listing',
				false
			);
			switch ($task) {
				case 'config':
				default:
					JToolBarHelper::title(JText::_('COM_RBIDS_COMMISSION__CONFIGURATION'));
					JToolBarHelper::apply('pricing.save');
					JToolBarHelper::custom( 'settingsmanager', 'back', 'back', JText::_('COM_RBIDS_BACK'), false );
					break;
				case 'balance':
					JToolBarHelper::title(JText::_('COM_RBIDS_COMMISSION__USER_BALANCES'));
					break;
				case 'payments':
					JToolBarHelper::title(JText::_('COM_RBIDS_COMMISSION__USER_PAYMENTS'));
					break;
				case 'notices':
					JToolBarHelper::title(JText::_('COM_RBIDS_COMMISSION__NOTIFY_USERS'));
					break;
			}

		}
	}

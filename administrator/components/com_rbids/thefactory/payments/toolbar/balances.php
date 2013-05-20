<?php
	/**------------------------------------------------------------------------
	thefactory - The Factory Class Library - v 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : thefactory
	 * @subpackage: payments
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryBalancesToolbar
	{
		function display($task)
		{
			switch ($task) {
				default:
				case 'listing':
					JToolBarHelper::title(JText::_('FACTORY_USER_BALANCES'));
					JToolBarHelper::custom('payments.newpayment', 'new', 'new', JText::_('FACTORY_ADD_FUNDS'), false);
					break;
				case 'withdrawForm':
					JToolBarHelper::title(JText::_('FACTORY_USER_BALANCES') . ': <span style=" color: #146295;font-size:12px;">[' . JText::_('FACTORY_PAY_REQUESTED_WITHDRAW_AMOUNT') . ']</span>');
					break;
			}
		}
	}

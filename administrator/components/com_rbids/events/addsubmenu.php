<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 05/09/2012
	 * @package   : RBids
	 * @subpackage: Events
	-------------------------------------------------------------------------*/
	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventAddSubMenu extends JTheFactoryEvents
	{

		function onAfterExecuteTask($controller)
		{

			$controllerName = strtolower($controller->getName());
			$method = strtolower($controller->get('task'));

			$task = $controllerName . '.' . $method;

			//what tasks need the submenuhelper added?
			$aTasks = array(
				'config.display',
				'orders.listing',
				'payments.listing',
				'balances.listing',
				'themes.listthemes',
				'currencies.listing',
				'pricing.listing',
				'gateways.listing',
				'mailman.mails',
				'about.main',
				'category.categories'
			);

			if (!in_array($task, $aTasks)) {
				return;
			}

			RBidsHelperTools::getAdminSubmenu();
		}
	}

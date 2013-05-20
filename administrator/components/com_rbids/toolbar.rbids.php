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

	if (strpos($task, '.')) {
		$c = substr($task, 0, strpos($task, '.'));
		if (in_array($c, array()))
			//JSubMenuHelper::addEntry(JText::_('COM_RBIDS_SETTINGS'), 'index.php?option=com_rbids&task=settingsmanager', false);
		if (in_array($c, array()))
		//	JSubMenuHelper::addEntry(JText::_('COM_RBIDS_PAYMENTS'), 'index.php?option=com_rbids&task=paymentmanager', false);
		return;
	}

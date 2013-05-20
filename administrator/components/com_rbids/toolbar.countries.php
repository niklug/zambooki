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

	JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_COUNTRY_MANAGER') . ' ]</small></small>', 'generic.png');
	JToolBarHelper::custom('countries.toggle', 'apply', 'apply', JText::_('COM_RBIDS_ENABLEDISABLE'));
	JToolBarHelper::custom('settingsmanager', 'back', 'back', JText::_('COM_RBIDS_BACK'), false);

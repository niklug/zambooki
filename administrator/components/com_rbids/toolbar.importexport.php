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

	switch ($task) {
		case 'importexport':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_IMPORT__EXPORT_AUCTIONS') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom("settingsmanager", 'back', 'back', JText::_('COM_RBIDS_BACK'), false);
			break;
		case 'showadmimportform':
		case 'importcsv':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_IMPORT_FROM_CSV') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom("importexport.importcsv", 'upload', 'upload', JText::_('COM_RBIDS_UPLOAD'), false);
			JToolBarHelper::back();
			break;
		case 'exportToXls':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_EXPORT_AUCTIONS') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom("importexport.importexport", 'back', 'back', JText::_('COM_RBIDS_BACK'), false);

			break;
	}

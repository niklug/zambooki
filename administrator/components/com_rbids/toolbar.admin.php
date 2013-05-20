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
		case 'new':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_NEW_AUCTION') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::save('saveclose');
			JToolBarHelper::apply('save');
			JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_rbids&task=offers');
			break;
		case 'edit':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_EDIT_AUCTION') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::save('saveclose');
			JToolBarHelper::apply('save');
			JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_rbids&task=offers');
			break;
		case 'view':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_VIEW_AUCTION') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::back();
			break;
		case 'write_admin_message':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_WRITE_MESSAGE_TO_USER') . ' ]</small></small>', 'generic.png');
			break;
		case 'comments_administrator':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_MESSAGE_ADMINISTRATOR') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom('del_comment', 'delete', 'delete', JText::_('COM_RBIDS_DELETE'), true);
			JToolBarHelper::custom('toggle_comment', 'cancel', 'cancel', JText::_('COM_RBIDS_ENABLEDISABLE'), true);
			break;
		case 'reviews_administrator':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_RATINGS_AND_REVIEWS') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom('del_review', 'delete', 'delete', JText::_('COM_RBIDS_DELETE'), true);
			break;
		case 'offers':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[' . JText::_('COM_RBIDS_AUCTIONS_LIST') . ']</small></small>', 'generic.png');
			JToolBarHelper::addNew('new');
			JToolBarHelper::custom('unblock', 'apply', 'apply', JText::_('COM_RBIDS_UNBLOCK'), true);
			JToolBarHelper::custom('block', 'cancel', 'cancel', JText::_('COM_RBIDS_BLOCK'), true);
			JToolBarHelper::custom('remove', 'delete', 'cancel', JText::_('COM_RBIDS_DELETE_AUCTIONS'), true);
			break;
		case 'users':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[' . JText::_('COM_RBIDS_MANAGE_USERS') . ']</small></small>', 'generic.png');
			JToolBarHelper::custom('unblockuser', 'apply', 'apply', JText::_('COM_RBIDS_UNBLOCK'), false);
			JToolBarHelper::custom('blockuser', 'cancel', 'cancel', JText::_('COM_RBIDS_BLOCK'), false);
			JToolBarHelper::divider();
			JToolBarHelper::custom('setverify', 'apply', 'apply', JText::_('COM_RBIDS_SET_AS_VERIFY'), true);
			JToolBarHelper::custom('unsetverify', 'apply', 'apply', JText::_('COM_RBIDS_UNSET_VERIFY'), true);
			JToolBarHelper::divider();
			JToolBarHelper::custom('setpowerseller', 'apply', 'apply', JText::_('COM_RBIDS_SET_AS_POWERSELLER'), true);
			JToolBarHelper::custom('unsetpowerseller', 'cancel', 'cancel', JText::_('COM_RBIDS_UNSET_POWERSELLER'), true);
			break;
		case 'detailUser':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[' . JText::_('COM_RBIDS_MANAGE_USERS') . ']</small></small>', 'generic.png');
			JToolBarHelper::custom('users', 'back', 'back', JText::_('COM_RBIDS_BACK'), false);
			break;
		case 'paymentmanager':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_PAYMENT_MANAGEMENT') . ' ]</small></small>', 'generic.png');
			break;
		case 'reported_offers':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_REPORTED_AUCTIONS') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom('solved', 'save', 'save', JText::_('COM_RBIDS_MARK_AS_SOLVED'));
			break;
		case 'settingsmanager':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_EXTENSION_CONTROL_PANEL') . ' ]</small></small>', 'generic.png');
			break;
		case 'integration':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_CONFIGURE_USER_PROFILE') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::apply('changeprofileintegration');
			JToolBarHelper::custom( 'settingsmanager', 'back', 'back', JText::_('COM_RBIDS_BACK'), false );
			break;
		default:
		case 'dashboard':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_DASHBOARD') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom('about.main', 'help', 'help', JText::_('COM_RBIDS_ABOUT_COMPONENT'), false);
			break;
		case 'cronjob_info':
			JToolBarHelper::title(JText::_('COM_RBIDS_REVERSE_AUCTIONS_FACTORY') . ': <small><small>[ ' . JText::_('COM_RBIDS_CRON_INFO') . ' ]</small></small>', 'generic.png');
			JToolBarHelper::custom('settingsmanager', 'back', 'back', JText::_('COM_RBIDS_BACK'), false);
			break;
	}

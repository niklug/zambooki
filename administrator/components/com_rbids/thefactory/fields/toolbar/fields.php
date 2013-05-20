<?php
	/**------------------------------------------------------------------------
	thefactory - The Factory Class Library - v 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : thefactory
	 * @subpackage: custom_fields
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryFieldsToolbar
	{
		function display($task = null)
		{
			switch ($task) {
				default:
				case 'listfields':
					JToolBarHelper::title(JText::_('FACTORY_CUSTOM_FIELDS_MANAGEMENT'));
					JToolBarHelper::custom('fields.edit', 'new.png', 'new_f2.png', JText::_('FACTORY_NEW_FIELD'), false);
					JToolBarHelper::custom('fields.edit', 'edit.png', 'edit_f2.png', JText::_('FACTORY_EDIT_FIELD'), true);
					JToolBarHelper::custom('fields.deletefield', 'delete.png', 'delete_f2.png', JText::_('FACTORY_DELETE_FIELDS'), true);
					JToolBarHelper::custom('positions.listfields', 'html.png', 'html_f2.png', JText::_('FACTORY_PUBLISH_ON_TEMPLATE'), false);
					JToolBarHelper::custom('settingsmanager', 'back', 'back', JText::_('COM_RBIDS_BACK'), false);

					JTheFactoryFieldsSubmenu::subMenuListFields();
					break;
				case 'edit':
					JToolBarHelper::title(JText::_('FACTORY_CUSTOM_FIELDS_MANAGEMENT_EDIT'));
					JToolBarHelper::save("fields.savefield");
					JToolBarHelper::save2new("fields.savefield2new");
					JToolBarHelper::apply("fields.applyfield");
					JToolBarHelper::custom('fields.listfields', 'back', 'back', JText::_('COM_RBIDS_BACK'), false);
					JTheFactoryFieldsSubmenu::subMenuEditField();
					break;
			}

		}
	}

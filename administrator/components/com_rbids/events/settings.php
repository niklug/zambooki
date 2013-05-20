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
	 * @subpackage: Events
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventSettings extends JTheFactoryEvents
	{
		/**
		 * onDisplaySettings
		 *
		 * @param $form
		 * @param $groups
		 * @param $data
		 */
		public function onDisplaySettings($form, $groups, $data)
		{
			JHtml::_('behavior.mootools');
			$doc =& JFactory::getDocument();
			$doc->addScript(JURI::base() . 'components/com_rbids/js/rbids_settings.js');
		}

		/**
		 * onBeforeSaveSettings
		 */
		public function onBeforeSaveSettings()
		{
		}

		/**
		 * onAfterSaveSettings
		 */
		public function onAfterSaveSettings()
		{
		}
	}

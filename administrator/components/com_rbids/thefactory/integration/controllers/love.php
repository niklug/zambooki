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
	 * @subpackage: integration
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryIntegrationLoveController extends JController
	{
		var $_name = 'IntegrationLove';
		var $name = 'IntegrationLove';
		var $modulename = 'Integration';

		/**
		 *
		 */
		public function __construct()
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$lang = JFactory::getLanguage();
			$lang->load('thefactory.' . strtolower($this->modulename));

			$config = array(
				'view_path' => $MyApp->app_path_admin . 'integration' . DS . "views",
			);
			JLoader::register('JTheFactoryIntegrationLove', $MyApp->app_path_admin . 'integration/love.php');
			JLoader::register('JTheFactoryIntegrationLoveToolbar', $MyApp->app_path_admin . 'integration/toolbar/love.php');
			JLoader::register('JTheFactoryIntegrationLoveHelper', $MyApp->app_path_admin . 'integration/helper/love.php');

			parent::__construct($config);
		}

		/**
		 * @param string $task
		 */
		public function execute($task)
		{
			JTheFactoryIntegrationLoveToolbar::display($task);
			parent::execute($task);
		}

		/**
		 * @param string $name
		 * @param string $type
		 * @param string $prefix
		 * @param array  $config
		 *
		 * @return object
		 */
		public function getView($name = '', $type = 'html', $prefix = '', $config = array())
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$config['template_path'] = $MyApp->app_path_admin . 'integration' . DS . "views" . DS . "integrationlove" . DS . "tmpl";
			return parent::getView($name, $type, 'JTheFactoryView', $config);
		}

		/**
		 *
		 */
		public function display()
		{
			$integrationFields = JTheFactoryIntegrationLove::getIntegrationFields();
			$integrationArray = JTheFactoryIntegrationLove::getIntegrationArray();

			$db =& JFactory::getDBO();

			// Change profile custom fields name with name used in title column fom #__lovefactory_fields

			$db->setQuery('SHOW FIELDS FROM `#__lovefactory_profiles`');
			$loveUserProfileColumns = $db->loadResultArray();


			// Get correspondent field name for this user data profile field from #__lovefactory_field
			foreach ($loveUserProfileColumns as $k => $col) {
				// Ignore other fields than custom fields
				$pos_1 = strpos($col, 'field_');
				$pos_2 = strpos($col, '_visibility');
				// We don't need _visibility columns
				if (false !== $pos_2) {
					unset($loveUserProfileColumns[$k]);
					continue;
				}
				// Change only custom fields
				if (false === $pos_1 || false !== $pos_2) {
					continue;
				}
				$fieldId = filter_var($col, FILTER_SANITIZE_NUMBER_INT);

				$query = "SELECT `title` FROM `#__lovefactory_fields` WHERE `id` = {$fieldId}";
				$db->setQuery($query);


				$stdObj = new stdClass();
				$stdObj->value = $col;
				$stdObj->text = $db->loadResult();
				$loveUserProfileColumns[$k] = $stdObj;
			}

			// TypeCast to object any items that are not object
			foreach ($loveUserProfileColumns as $k => $v) {
				if (is_object($v)) {
					continue;
				}
				if (strstr($v, 'id')) {
					unset($loveUserProfileColumns[$k]);
				}
				$stdObj = new stdClass();
				$stdObj->value = $v;
				$stdObj->text = $v;
				$loveUserProfileColumns[$k] = $stdObj;
			}


			$loveFields = array_merge(array(JHTML::_("select.option", '', '-' . JText::_("FACTORY_NONE") . '-')), $loveUserProfileColumns);

			$view = $this->getView();
			$view->assignRef('integrationFields', $integrationFields);
			$view->assignRef('integrationArray', $integrationArray);
			$view->assignRef('lovefields', $loveFields);
			$view->assign('love_detected', JTheFactoryIntegrationLove::detectIntegration());

			$view->display();
		}

		/**
		 *
		 */
		public function save()
		{
			$MyApp = & JTheFactoryApplication::getInstance();
			$tablename = $MyApp->getIniValue("field_map_table", "profile-integration");

			$fields = JTheFactoryIntegrationLove::getIntegrationFields();
			$db =& JFactory::getDBO();

			foreach ($fields as $field) {
				$assoc_fd = JRequest::getVar($field, null);
				$db->setQuery("SELECT * FROM `{$tablename}` WHERE `field`='{$field}'");
				$res = $db->loadObject();
				if ($res)
					$db->setQuery("UPDATE `{$tablename}` SET `assoc_field` = '{$assoc_fd}' WHERE `field` = '{$field}'");
				else
					$db->setQuery("INSERT INTO `{$tablename}` SET `assoc_field` = '{$assoc_fd}', `field` = '{$field}'");
				$db->query();
			}

			$this->setRedirect("index.php?option=" . APP_EXTENSION . "&task=integrationLove.display", JText::_("FACTORY_SETTINGS_SAVED"));
		}


	} // End Class



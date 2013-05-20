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
	 * @subpackage: integration
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryUserProfile extends JObject
	{

		var $profile_mode = null;
		var $integrationObject = null;

		function __construct($profile_mode = 'component')
		{
			$MyApp = & JTheFactoryApplication::getInstance();
			$integrationClass = 'JTheFactoryIntegration' . ucfirst($profile_mode);
			JLoader::register($integrationClass, $MyApp->app_path_admin . 'integration' . DS . $profile_mode . '.php');
			$this->integrationObject = new $integrationClass();
			$this->profile_mode = $profile_mode;
		}

		static function &getInstance($profile_mode = 'component')
		{
			static $instance;

			if (!isset ($instance)) {
				$instance = new JTheFactoryUserProfile($profile_mode);
			}
			return $instance;
		}

		function getUserProfile($userid = null)
		{

			$juser = & JFactory::getUser($userid);
			$extendedProfile = $this->integrationObject->getUserProfile($juser->id);

			foreach ($juser as $k => $v) {
				$this->$k = $v;
			}

			$fieldmap = $this->integrationObject->getIntegrationArray();

			foreach ($extendedProfile as $k => $v) {
				$this->$k = $v;

				//fill the integration fields
				$integrationFields = array_keys($fieldmap, $k);

				if (count($integrationFields)) {
					foreach ($integrationFields as $f) {
						$this->$f = $v;
					}
				}
			}
		}

		function getIntegrationTable()
		{
			return $this->integrationObject->table;
		}

		function getIntegrationKey()
		{
			return $this->integrationObject->keyfield;
		}

		function getIntegrationArray()
		{
			return $this->integrationObject->getIntegrationArray();
		}

		function getProfileLink($userid)
		{
			return $this->integrationObject->getProfileLink($userid);
		}

		function getProfileFields()
		{
			return $this->integrationObject->getIntegrationFields();
		}

		function getUserList($limitstart = 0, $limit = 30, $filters = null, $ordering = null)
		{
			return $this->integrationObject->getUserList($limitstart, $limit, $filters, $ordering);
		}

		function getCountUserList($limitstart = 0, $limit = 30, $filters = null, $ordering = null)
		{
			return $this->integrationObject->getUserList($limitstart, $limit, $filters, $ordering);
		}

		function checkProfile($userid = null)
		{
			return $this->integrationObject->checkProfile($userid);

		}

		function getFilterField($filterName)
		{

			$integrationArray = $this->getIntegrationArray();
			return isset($integrationArray[$filterName]) ? $integrationArray[$filterName] : $filterName;
		}

		function getFilterTable($filterName)
		{

			$db =& JFactory::getDBO();

			$fieldName = $this->getFilterField($filterName);
			if (!$fieldName) {
				return null;
			}

			static $standardFields = null;
			if (!$standardFields) {
				$db->setQuery('SHOW FIELDS FROM #__users');
				$standardFields = $db->loadResultArray();
			}

			if (in_array($fieldName, $standardFields)) {
				return '#__users';
			}

			static $extendedFields = null;
			if (!$extendedFields) {
				$db->setQuery('SHOW FIELDS FROM ' . $this->integrationObject->table);
				$extendedFields = $db->loadResultArray();

				if (strstr($this->integrationObject->table,'love')) {
					foreach ($extendedFields as $v) {
						// Ignore other fields than custom fields
						$pos_1 = strpos($v, 'field_');
						$pos_2 = strpos($v, '_visibility');
						if (false === $pos_1 || false !== $pos_2) {
							continue;
						}
						$fieldId = filter_var($v, FILTER_SANITIZE_NUMBER_INT);

						// Get correspondent field name for this user data profile field from #__lovefactory_field
						$query = "SELECT `title` FROM `#__lovefactory_fields` WHERE `id` = {$fieldId}";
						$db->setQuery($query);

						$getCF = str_replace(' ', '_', $db->loadResult());
						$extendedFields[] = $getCF;

					}
				}
			}

			if (in_array($fieldName, $extendedFields)) {
				return $this->integrationObject->table;
			}

			return false;
		}

		function setFieldValue($filterName, $value, $userid = 0)
		{

			$db = JFactory::getDBO();

			$fieldName = $this->getFilterField($filterName);
			if (!$fieldName) {
				JError::raiseWarning(1, 'Field "' . strtoupper($filterName) . '" is not mapped in profile integration!');
				return;
			}
			$fieldTable = $this->getFilterTable($filterName);

			$newValue = preg_replace('/#fieldName/', $fieldName, $value);
			$value = ($newValue == $value) ? $db->Quote($newValue) : $db->escape($newValue);

			$keyField = ('#__users' == $fieldTable) ? 'id' : $this->integrationObject->keyfield;
			$userid = (array)$userid;

			$db->setQuery('UPDATE `' . $fieldTable . '` SET `' . $fieldName . '`=' . $value . ' WHERE `' . $keyField . '` IN (' . implode(',', $userid) . ')');

			return $db->query();
		}
	} // End Class

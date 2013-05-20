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

	class JTheFactoryIntegrationCBController extends JController
	{

		var $_name = 'IntegrationCB';
		var $name = 'IntegrationCB';
		var $modulename = 'Integration';

		function __construct()
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$lang = JFactory::getLanguage();
			$lang->load('thefactory.' . strtolower($this->modulename));
			$config = array(
				'view_path' => $MyApp->app_path_admin . 'integration' . DS . "views",
			);
			JLoader::register('JTheFactoryIntegrationCB', $MyApp->app_path_admin . 'integration/cb.php');
			JLoader::register('JTheFactoryIntegrationCBToolbar', $MyApp->app_path_admin . 'integration/toolbar/cb.php');
			JLoader::register('JTheFactoryIntegrationCBHelper', $MyApp->app_path_admin . 'integration/helper/cb.php');

			parent::__construct($config);
		}

		function execute($task)
		{
			JTheFactoryIntegrationCBToolbar::display($task);
			return parent::execute($task);
		}

		function getView($name = '', $type = 'html', $prefix = '', $config = array())
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$config['template_path'] = $MyApp->app_path_admin . 'integration' . DS . "views" . DS . "integrationcb" . DS . "tmpl";
			return parent::getView($name, $type, 'JTheFactoryView', $config);
		}

		function display()
		{
			require_once(JPATH_ADMINISTRATOR . DS . "components" . DS . "com_comprofiler" . DS . "plugin.foundation.php");
			cbimport('language.all');

			$integrationFields = JTheFactoryIntegrationCB::getIntegrationFields();
			$integrationArray = JTheFactoryIntegrationCB::getIntegrationArray();

			$database =& JFactory::getDBO();
			$query = "SELECT `name` AS value,`title` AS text FROM `#__comprofiler_fields` ORDER BY `name`";
			$database->setQuery($query);
			$cbfields = $database->loadObjectList();
			foreach ($cbfields as &$f) {
				$f->text = defined($f->text) ? constant($f->text) : $f->text;
			}
			$cbfields = array_merge(array(JHTML::_("select.option", '', '-' . JText::_("FACTORY_NONE") . '-')), $cbfields);

			$view = $this->getView();
			$view->assignRef('integrationFields', $integrationFields);
			$view->assignRef('integrationArray', $integrationArray);
			$view->assignRef('cbfields', $cbfields);
			$view->assign('cb_detected', JTheFactoryIntegrationCB::detectIntegration());

			$view->display();
		}

		function save()
		{
			$MyApp = & JTheFactoryApplication::getInstance();
			$tablename = $MyApp->getIniValue("field_map_table", "profile-integration");

			$fields = JTheFactoryIntegrationCB::getIntegrationFields();
			$db =& JFactory::getDBO();

			foreach ($fields as $field) {
				$cb = JRequest::getVar($field, null);
				$db->setQuery("select * from `{$tablename}` where `field`='{$field}'");
				$res = $db->loadObject();
				if ($res)
					$db->setQuery("update `{$tablename}` set `assoc_field`='{$cb}' where `field`='{$field}'");
				else
					$db->setQuery("insert into `{$tablename}` set `assoc_field`='{$cb}' ,`field`='{$field}'");
				$db->query();
			}

			$this->setRedirect("index.php?option=" . APP_EXTENSION . "&task=integrationCB.display", JText::_("FACTORY_SETTINGS_SAVED"));
		}


		function installPlugins()
		{
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');
			$plugin_dir = JPATH_ROOT . DS . "components" . DS . APP_EXTENSION . DS . "installer" . DS . "cb_plug" . DS;
			$files = JFolder::files($plugin_dir, '\.xml$');
			$installed_plugins = array();

			foreach ($files as $file) {
				$xml =& JFactory::getXMLParser('simple');
				if (!$xml->loadFile($plugin_dir . $file))
					continue;

				$root =& $xml->document;

				if (!is_object($root) || ($root->name() != 'cbinstall'))
					continue;

				$title = $root->getElementByPath('name')->data();
				$tab = $root->getElementByPath('tabs/tab');
				$tabtitle = $tab->attributes('name');
				$class = $tab->attributes('class');
				$pluginfiles = $root->getElementByPath('files/filename');
				$plugin = $pluginfiles->attributes('plugin');
				$folder = 'plug_' . JFile::stripExt($plugin);

				$installed_plugins[] = JTheFactoryIntegrationCBHelper::InstallCBPlugin($title, $tabtitle, $plugin, $folder, $class);
				unset($xml);
			}

			$view = $this->getView();
			$view->assignRef('plugins', $installed_plugins);
			$view->display('plugininstall');

		}

	}



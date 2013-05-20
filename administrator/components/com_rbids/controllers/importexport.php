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

	class JRbidsAdminControllerImportExport extends JController
	{

		public function execute($task)
		{
			if (file_exists(JPATH_COMPONENT_ADMINISTRATOR . DS . 'toolbar.importexport.php'))
				require JPATH_COMPONENT_ADMINISTRATOR . DS . 'toolbar.importexport.php';

			parent::execute($task);
		}

		public function ImportExport()
		{
			$view = $this->getView('importexport', 'html');
			$view->display();

		}

		public function ExportToXls()
		{
			$db = & JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__" . APP_PREFIX . "_fields ORDER BY ordering ASC, page ASC ");
			$fields = $db->loadObjectList();
			if (count($fields) > 0) {
				$view = $this->getView('importexport', 'html');
				$view->assign('fields', $fields);
				$view->display('exporttoxls');

			} else
				$this->do_ExportToXls();
			return true;
		}

		public function do_ExportToXls()
		{

			$db = & JFactory::getDBO();
			$profile = RBidsHelperTools::getUserProfileObject();

			$tableName = $profile->getIntegrationTable();
			$tableKey = $profile->getIntegrationKey();
			$integration = $profile->getIntegrationArray();

			$cid = JRequest::getVar("cid", array());

			$sql = "SELECT *,  c.catname, u.username,prof.*  \r\n
    		FROM #__rbid_auctions a \r\n
    		LEFT JOIN `$tableName` prof ON a.userid= prof.`$tableKey`  \r\n
    		LEFT JOIN #__rbid_categories c ON a.cat = c.id \r\n
    		LEFT JOIN #__users u ON a.userid = u.id
		";
			$db->setQuery($sql);
			$result = $db->loadAssocList();


			$headers["username"] = "User";
			$headers["title"] = "Title";
			$headers["shortdescription"] = "Short description";
			$headers["description"] = "Description";
			$headers["max_price"] = "Max price";
			$headers["currency"] = "Currency";
			$headers["start_date"] = "Start date";
			$headers["end_date"] = "End date";
			$headers["published"] = "Published";
			$headers["catname"] = "Category";
			$labels = array("username", "title", "shortdescription", "description", "max_price", "currency", "start_date", "end_date", "published", "catname");

			if (count($cid) > 0) {
				foreach ($cid as $fieldname) {
					$field = CustomFieldsFactory::getFieldObject($fieldname);
					$headers[$fieldname] = $field->name;
					$labels[] = $fieldname;
				}
			}

			require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "thefactory" . DS . "library" . DS . "admin.xlscreator.php");

			$filename = "ExportXLS";

			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"" . $filename . ".xls\"");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");

			JTheFactoryXLSCreator::createXLSAdv($result, $labels, $headers, "Exported Auctions");
			exit;
		}

		public function ShowAdmImportForm()
		{
			$view = $this->getView('import', 'html');
			$view->assign('errors', array());
			$view->display();
		}

		public function ImportCSV()
		{
			$err = RBidsHelperAuction::ImportFromCSV();
			if (!count($err))
				$this->setRedirect("index.php?option=com_rbids&task=offers", JText::_("COM_RBIDS_AUCTIONS_SUCCESSFULLY_IMPORTED"));
			else {
				$view = $this->getView('import', 'html');
				$view->assign('errors', $err);
				$view->display();
			}

		}

	} // End Class

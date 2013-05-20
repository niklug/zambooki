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

	jimport('joomla.application.component.controller');

	class JRbidsAdminController extends JController
	{
		public function __construct()
		{
			parent::__construct();

			$this->registerDefaultTask('dashboard');
			$this->registerTask('new', 'edit');

		}

		/**
		 * execute
		 *
		 * @param string $task
		 *
		 * @return mixed|void
		 */
		public function execute($task)
		{
			if (file_exists(JPATH_COMPONENT_ADMINISTRATOR . DS . 'toolbar.admin.php'))
				require JPATH_COMPONENT_ADMINISTRATOR . DS . 'toolbar.admin.php';
			parent::execute($task);

			// I don't want to display sub menu in this tasks
			if (!in_array($task, array('integration', 'cronjob_info'))) {
				RBidsHelperTools::getAdminSubmenu($task);
			}
		}

		/**
		 *
		 */
		public function PaymentManager()
		{
			$view = $this->getView('paymentmanager', 'html');
			$view->display();
		}

		/**
		 *
		 */
		public function Offers()
		{

			$app = & JFactory::getApplication();
			$db =& JFactory::getDBO();
			$cfg =& JTheFactoryHelper::getConfig();

			if ($cfg->admin_approval) {
				JToolBarHelper::custom('approve_toggle', 'apply', 'apply', JText::_('COM_RBIDS_TOGGLE_APPROVAL_STATUS'), true);
			}
			$where = array();

			$context = 'com_rbids.listadds';
			$reset = JRequest::getInt('reset');
			if ($reset) {
				$app->setUserState($context, null);
				$filter_authorid = '';
				$filter_approved = '';
				$search = '';
			} else {
				$filter_authorid = $app->getUserStateFromRequest($context . 'filter_authorid', 'filter_authorid', '', 'string');
				$filter_approved = $app->getUserStateFromRequest($context . 'filter_approved', 'filter_approved', '', 'string');
				$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
			}
			$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', '', 'cmd');
			$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
			$search = JString::strtolower($search);

			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);


			if (!$filter_order) {
				$filter_order = 'a.title';
			}
			$order = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . '';

			if ($search)
				$where[] = " a.title LIKE '%$search%' ";
			if ($filter_authorid) {
				$where[] = " u.username like '%$filter_authorid%' ";
			}
			if ($cfg->admin_approval && $filter_approved !== '') {
				$where[] = " a.approved= '$filter_approved'";
			}

			// Build the where clause of the content record query
			$where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

			// Get the total number of records
			$query = "SELECT COUNT(*)
					  FROM `#__rbid_auctions` AS `a`
					  LEFT JOIN `#__rbid_categories` AS `cc` ON `cc`.`id` = `a`.`cat`
					  LEFT JOIN `#__users` AS `u` ON `u`.`id` = `a`.`userid`
					  {$where}
					  ";
			$db->setQuery($query);
			$total = $db->loadResult();

			// Create the pagination object
			jimport('joomla.html.pagination');
			$pagination = new JPagination($total, $limitstart, $limit);

			// Get the auctions
			$query = "SELECT `a`.*,
						 `cc`.`catname` AS `name`,
						 `u`.`name` AS `editor`,
						 COUNT(DISTINCT `rbids`.`id`) AS `nr_bids`,
						 MIN(`rbids`.`bid_price`) AS `min_bid`,
						 COUNT(DISTINCT `rbids`.`userid`) AS `nr_bidders`
					  FROM `#__rbid_auctions` AS `a`
					  LEFT JOIN `#__rbid_categories` AS `cc` ON `cc`.`id` = `a`.`cat`
					  LEFT JOIN `#__users` AS `u` ON `u`.`id` = `a`.`userid`
					  LEFT JOIN `#__rbids` AS `rbids` ON `rbids`.`auction_id` = `a`.`id` AND `rbids`.`cancel` = 0
					  {$where}
					  GROUP BY `a`.`id`
					  {$order}
					";
			$db->setQuery($query, $pagination->limitstart, $pagination->limit);
			$rows = $db->loadObjectList();

			$view = $this->getView('auction', 'html');
			$view->assign('filter_authorid', $filter_authorid);
			$view->assignRef('order_Dir', $filter_order_Dir);
			$view->assignRef('order', $filter_order);
			$view->assignRef('filter_approved', $filter_approved);
			$view->assignRef('search', $search);
			$view->assignRef('cfg', $cfg);
			$view->assignRef('auctions', $rows);
			$view->assignRef('pagnation', $pagination);
			$view->display('list');
		}

		/**
		 * @return bool
		 */
		public function Approve_Toggle()
		{
			$db =& JFactory::getDBO();
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			$cids = implode(',', $cid);

			$query = 'UPDATE #__rbid_auctions' .
				' SET approved = 1-coalesce(approved,0)' .
				' WHERE id IN ( ' . $cids . ' ) ';
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}
			$total = $db->getAffectedRows();
			$msg = JText::sprintf('%s Item(s) Affected', $total);
			$this->setRedirect('index.php?option=com_rbids&task=offers', $msg);
		}


		/**
		 * @return bool
		 */
		public function Publish()
		{
			$db =& JFactory::getDBO();
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			$cids = implode(',', $cid);

			$query = 'UPDATE #__rbid_auctions' .
				' SET published = 1' .
				' WHERE id IN ( ' . $cids . ' ) ';
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}
			$total = $db->getAffectedRows();
			$msg = JText::sprintf('%s Item(s) successfully Published', $total);
			$this->setRedirect('index.php?option=com_rbids&task=offers', $msg);
		}

		/**
		 * @return bool
		 */
		public function Unpublish()
		{
			$db =& JFactory::getDBO();
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			$cids = implode(',', $cid);

			$query = 'UPDATE #__rbid_auctions' .
				' SET published = 0' .
				' WHERE id IN ( ' . $cids . ' ) ';
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}
			$total = $db->getAffectedRows();
			$msg = JText::sprintf('%s Item(s) successfully Unpublished', $total);
			$this->setRedirect('index.php?option=com_rbids&task=offers', $msg);
		}

		/**
		 * @return bool
		 */
		public function Block()
		{
			$db =& JFactory::getDBO();
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			$cids = implode(',', $cid);

			$query = "UPDATE `#__rbid_auctions` SET `close_by_admin` = 1 WHERE `id` IN ('{$cids}')";

			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}
			$auction = JTable::getInstance('auctions', 'Table');
			foreach ($cid as $id) {
				$auction->load($id);
				$user = JFactory::getUser($auction->userid);

				$auction->SendMails(array($user), 'bid_admin_close_auction');
			}

			$total = $db->getAffectedRows();
			$msg = JText::sprintf('%s Item(s) successfully Banned', $total);
			$this->setRedirect('index.php?option=com_rbids&task=offers', $msg);
		}

		/**
		 * @return bool
		 */
		public function Unblock()
		{

			$db =& JFactory::getDBO();
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			$cids = implode(',', $cid);

			$query = 'UPDATE #__rbid_auctions' .
				' SET close_by_admin = 0' .
				' WHERE id IN ( ' . $cids . ' ) ';
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}

			$total = $db->getAffectedRows();
			$msg = JText::sprintf('%s Item(s) successfully opened', $total);
			$this->setRedirect('index.php?option=com_rbids&task=offers', $msg);
		}

		/**
		 * @return bool
		 */
		public function CloseOffer()
		{
			$db =& JFactory::getDBO();
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			$cids = implode(',', $cid);

			$query = 'UPDATE #__rbid_auctions' .
				' SET close_offer = 1' .
				' WHERE id IN ( ' . $cids . ' ) ';
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}

			$total = $db->getAffectedRows();
			$msg = JText::sprintf('%s Item(s) successfully Closed', $total);
			$this->setRedirect('index.php?option=com_rbids&task=offers', $msg);
		}

		/**
		 * @return bool
		 */
		public function OpenOffer()
		{
			$db =& JFactory::getDBO();
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			$cids = implode(',', $cid);

			$query = 'UPDATE #__rbid_auctions' .
				' SET close_offer = 0' .
				' WHERE id IN ( ' . $cids . ' ) ';
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}

			$total = $db->getAffectedRows();
			$msg = JText::sprintf('%s Item(s) successfully opened', $total);
			$this->setRedirect('index.php?option=com_rbids&task=offers', $msg);
		}

		/**
		 *
		 */
		public function Remove()
		{
			$cid = JRequest::getVar('cid', array(0), '', 'array');
			$auction =& JTable::getInstance('auctions', 'Table');
			foreach ($cid as $id) {
				$auction->load($id);
				$auction->delete($id);
			}
			$this->setRedirect("index.php?option=com_rbids&task=offers", JText::_("COM_RBIDS_REMOVED"));
		}

		/**
		 *
		 */
		public function Edit()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$cid = JRequest::getVar('cid', array(0), '', 'array');
			$view = $this->getView('auction', 'html');
			JArrayHelper::toInteger($cid, array(0));

			$row =& JTable::getInstance('auctions', 'Table');
			$row->load($cid[0]);

			$fields =& CustomFieldsFactory::getFieldsList("auctions");

			$userprofile = RBidsHelperTools::getUserProfileObject();
			$userprofile->getUserProfile($row->userid);

			$row->userdetails = clone $userprofile;
			$row->messages = $row->getMessages();
			$row->bids = $row->getBidList();

			$tag_obj =& JTable::getInstance('tags', 'Table');
			$row->tags = $tag_obj->getTagsAsString($row->id);

			$feat[] = JHTML::_('select.option', 'none', JText::_("COM_RBIDS_NONE"));
			$feat[] = JHTML::_('select.option', 'featured', JText::_("COM_RBIDS_FEATURED"));

			$lists['featured'] = JHTML::_('select.genericlist', $feat, 'featured', 'class="inputbox" id="featured" style="width:120px;"', 'value', 'text', $row->featured);

			$lists['category'] = JHtml::_('factorycategory.select', 'cat', '', $row->cat);
			$lists['currency'] = JHtml::_('currency.selectlist', 'currency', '', $row->currency);

			if (!$cfg->auctiontype_enable && $cfg->auctiontype_val) {
				$lists['auctiontype'] = "<input type='hidden' name='auction_type' value='" . $cfg->auctiontype_val . "' >";
			} else {
				$mode = $row->id ? 'form' : 'cookie';
				$lists['inviteSettingsContainer'] =
					'<div id="inviteSettingsContainer" style="display:' . (($row->auction_type == AUCTION_TYPE_INVITE) ? 'inline' : 'none') . '">' .
						JHTML::link("index.php?option=" . APP_EXTENSION . "&controller=invites&task=getlists&auctionId=" . $row->id . "&mode=" . $mode . "&tmpl=component", JText::_('COM_RBIDS_INVITE_BIDDERS'),
							'class="modal" rel="{handler:\'iframe\',size: {x:550,y:400}, ajaxOptions: { method: \'get\' } }"'
						) .
						'</div>';
				$lists['auctiontype'] = JHtml::_('auctiontype.selectlist', 'auction_type', 'onchange="auctionTypeExtras(this.value);" class="required"', $row->auction_type);
			}


			if ($cfg->admin_approval) {
				JToolBarHelper::custom('approve_toggle', 'apply', 'apply',
					($row->approved) ? JText::_('COM_RBIDS_UNAPPROVE') : JText::_('COM_RBIDS_APPROVE'), false);
			}

			$view->assignRef('row', $row);
			$view->assignRef('cfg', $cfg);
			$view->assignRef('fields', $fields);
			$view->assignRef('featured', $lists["featured"]);
			$view->assignRef('category', $lists["category"]);
			$view->assignRef('currency', $lists["currency"]);
			$view->assignRef('inviteSettingsContainer', $lists["inviteSettingsContainer"]);
			$view->assignRef('auctiontype', $lists["auctiontype"]);


			$view->display('edit');
		}

		/**
		 *
		 */
		public function SaveClose()
		{
			$this->Save();
			$this->setRedirect("index.php?option=com_rbids&task=offers", JText::_("COM_RBIDS_AUCTION_SAVED"));

		}

		/**
		 * @return bool
		 */
		public function Save()
		{

			$id = JRequest::getInt('id');

			// Since 3.3.0 is used rbid model separated from front model
			$auction = JTable::getInstance('auctions', 'Table');
			$model = JModel::getInstance('Rbid', 'JRbidsAdminModel');


			if (!$auction->load($id)) {
				JError::raiseWarning(550, JText::_("COM_RBIDS_ERROR_LOADING_AUCTION_ID") . $id);
				return false;
			}

			$model->bindAuction($auction);
			$err = $model->saveAuction($auction);

			if (count($err)) {
				$err_message = implode('<br />', $err);
				// If auction is saved we can redirect to edit page
				if ($auction->id) {
					$this->setRedirect("index.php?option=com_rbids&task=edit&cid[]={$id}", $err_message);
				} else {
					$this->setRedirect("index.php?option=com_rbids&task=new", $err_message);
				}
				return false;
			}
			// Auction is from now saved so we can redirect to new added auction edit page
			$this->setRedirect("index.php?option=com_rbids&task=edit&cid[]={$auction->id}", JText::_("COM_RBIDS_AUCTION_SAVED"));
		}

		/**
		 *
		 */
		public function SettingsManager()
		{
			$db =& JFactory::getDBO();
			$db->setQuery("select * from #__rbid_paysystems where enabled=1");
			$gateways = $db->loadObjectList();

			$db->setQuery("select * from #__rbid_pricing where enabled=1");
			$items = $db->loadObjectList();

			$db->setQuery("select * from #__rbid_log where event='cron' order by logtime desc limit 1");
			$log = $db->loadObject();

			$cfg =& JTheFactoryHelper::getConfig();

			$view = $this->getView('settings', 'html');
			$view->assignref('gateways', $gateways);
			$view->assignref('items', $items);
			if ($log)
				$view->assignref('latest_cron_time', $log->logtime);
			else
				$view->assign('latest_cron_time', JText::_('COM_RBIDS_NEVER'));
			$view->assignref('cfg', $cfg);
			$view->display();
		}

		/**
		 *
		 */
		public function Cronjob_Info()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$db =& JFactory::getDBO();
			$db->setQuery("select * from #__rbid_log where event='cron' order by logtime desc limit 1");
			$log = $db->loadObject();

			$view = $this->getView('settings', 'html');
			$view->assignref('cfg', $cfg);
			$view->assignref('cronlog', $log);
			$view->display('cronsettings');
		}

		/**
		 *
		 */
		public function BlockUser()
		{
			$cids = JRequest::getVar('cid', array(), 'post', 'array');
			foreach ($cids as $id) {
				$user =& JTable::getInstance('User');
				$user->load($id);
				$user->block = 1;
				$user->store();
			}
			$this->setRedirect('index.php?option=com_rbids&task=users', JText::sprintf('%s User(s) successfully Blocked ', count($cids)));
		}

		/**
		 *
		 */
		public function UnblockUser()
		{
			$cids = JRequest::getVar('cid', array(), 'post', 'array');
			foreach ($cids as $id) {
				$user =& JTable::getInstance('User');
				$user->load($id);
				$user->block = 0;
				$user->store();
			}
			$this->setRedirect('index.php?option=com_rbids&task=users', JText::sprintf('%s User(s) successfully Blocked ', count($cids)));
		}

		/**
		 * @param $field
		 * @param $value
		 *
		 * @return bool|mixed
		 */
		public function _setProfileField($field, $value)
		{
			$cid = JRequest::getVar("cid", array());

			$app =& JFactory::getApplication();
			$database = & JFactory::getDBO();
			$profile = RBidsHelperTools::getUserProfileObject();

			$tableName = $profile->getIntegrationTable();
			$tableKey = $profile->getIntegrationKey();
			$integration = $profile->getIntegrationArray();

			if (count($cid) < 1) {
				$app->enqueueMessage(JText::_("COM_RBIDS_NO_USER_SELECTED"), 'warning');
				return false;
			}
			if (!$integration[$field]) {
				$app->enqueueMessage(JText::_("COM_RBIDS_PROFILE_INTEGRATION_NOT_SET_FOR") . ": $field", 'warning');
				return false;
			}
			$cids = implode(',', $cid);

			$query = "UPDATE `$tableName` "
				. "\n SET " . $integration[$field] . "='$value' "
				. "\n WHERE `$tableKey` IN ( $cids )";
			$database->setQuery($query);

			return $database->query();
		}

		/**
		 *
		 */
		public function setVerify()
		{
			if ($this->_setProfileField('verified', 1)) $msg = JText::_("COM_RBIDS_VERIFIED_STATUS_CHANGED");
			else $msg = '';
			$this->setRedirect(JURI::root() . 'administrator/index.php?option=com_rbids&task=users', $msg);
		}

		/**
		 *
		 */
		public function unsetVerify()
		{
			if ($this->_setProfileField('verified', 0)) $msg = JText::_("COM_RBIDS_VERIFIED_STATUS_CHANGED");
			else $msg = '';
			$this->setRedirect(JURI::root() . 'administrator/index.php?option=com_rbids&task=users', $msg);

		}

		/**
		 *
		 */
		public function setPowerseller()
		{
			if ($this->_setProfileField('powerseller', 1)) $msg = JText::_("COM_RBIDS_POWERSELLER_STATUS_CHANGED");
			else $msg = '';
			$this->setRedirect(JURI::root() . 'administrator/index.php?option=com_rbids&task=users', $msg);
		}

		/**
		 *
		 */
		public function unsetPowerseller()
		{
			if ($this->_setProfileField('powerseller', 0)) $msg = JText::_("COM_RBIDS_POWERSELLER_STATUS_CHANGED");
			else $msg = '';
			$this->setRedirect(JURI::root() . 'administrator/index.php?option=com_rbids&task=users', $msg);
		}


		/**
		 *
		 */
		public function Users()
		{

			$cfg =& JTheFactoryHelper::getConfig();

			$db =& JFactory::getDBO();
			$app = & JFactory::getApplication();
			$where = array();

			$context = 'com_rbids.users';
			$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', '', 'cmd');
			$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
			$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
			$search = JString::strtolower($search);
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			if (!$filter_order) {
				$filter_order = 'u.name';
			}
			$order = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . '';

			if ($search)
				$where[] = " username LIKE '%$search%' ";

			// Build the where clause of the content record query
			$where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

			// Get the total number of records
			$query = "SELECT COUNT(*) FROM `#__users` {$where}";
			$db->setQuery($query);
			$total = $db->loadResult();

			// Create the pagination object
			jimport('joomla.html.pagination');
			$pagination = new JPagination($total, $limitstart, $limit);

			$profile = RBidsHelperTools::getUserProfileObject();

			$tableName = $profile->getIntegrationTable();
			$tableKey = $profile->getIntegrationKey();

			$integration = $profile->getIntegrationArray();

			$sel_fields = array("`b`.{$tableKey} AS id");
			foreach ($integration as $k => $v)
				if ($v) $sel_fields[] = "b.`$v` as $k";

			$s = implode(',', $sel_fields);
			// Get the users
			$query = "SELECT `u`.`id` AS `userid`,
						 `u`.`username` AS `username`,
						 `u`.`name` AS `name`,
						 `u`.`email` AS `email`,
						 `u`.`block`,
						 COUNT(`a`.`userid`) AS `nr_aucs`,
						 SUM(IF(`a`.`featured`<>'none',1,0)) AS `nr_featured`,
						 SUM(IF(`a`.`published`='1' AND `close_offer`<>'1',1,0)) AS `nr_published`,
						 SUM(IF(`a`.`close_offer`='1',1,0)) AS `nr_closed`,
						 SUM(IF(`a`.`close_by_admin`='1',1,0)) AS `nr_blocked`,
						 {$s}
					  FROM `#__users` AS `u`
					  LEFT JOIN  `{$tableName}` AS `b` ON `u`.`id`=`b`.`{$tableKey}`
					  LEFT JOIN `#__rbid_auctions` AS `a` ON `a`.`userid` = `u`.`id`
					  {$where}
					  GROUP BY `u`.`id`
					  {$order}
				";
			$db->setQuery($query, $pagination->limitstart, $pagination->limit);
			$rows = $db->loadObjectList();
			$view = $this->getView('users', 'html');
			$view->assignRef('users', $rows);
			$view->assignRef('pagination', $pagination);

			$view->assignRef('order_Dir', $filter_order_Dir);
			$view->assignRef('order', $filter_order);
			$view->assignRef('search', $search);
			$view->assignRef('cfg', $cfg);
			$view->display('list');

		}

		/**
		 *
		 */
		public function DetailUser()
		{

			$cid = JRequest::getVar("cid", array());
			$database = & JFactory::getDBO();

			if (count($cid) < 1) {
				echo "<script> alert('" . JText::_("COM_RBIDS_NO_USER_SELECTED") . "'); window.history.go(-1);</script>\n";
				exit;
			}


			$id = $cid[0];
			$userprofile = RBidsHelperTools::getUserProfileObject();

			if (!$userprofile->checkProfile($id)) {
				echo "<script type='text/javascript'>alert('" . JText::_("COM_RBIDS_PROFILE_NOT_FILLED_YET") . "');</script>";
				echo "<script type='text/javascript'>history.go(-1);</script>";
				return;
			}
			$userprofile->getUserProfile($id);

			$lists = array();
			$lists["user_fields"] = CustomFieldsFactory::getFieldsList('user_profile');

			$query = "SELECT count(*) AS nr_ads,max(start_date) AS last_ad_date,min(start_date) AS first_ad_date  FROM #__rbid_auctions WHERE userid=$id";
			$database->setQuery($query);
			$res = $database->loadAssocList();

			$lists['nr_ads'] = $res[0]['nr_ads'];
			$lists['last_ads_placed'] = $res[0]['last_ad_date'];
			$lists['first_ads_placed'] = $res[0]['first_ad_date'];

			JTheFactoryHelper::modelIncludePath('payments');
			$model =& JModel::getInstance('Balance', 'JTheFactoryModel');
			$balance = $model->getUserBalance($id);

			$view = $this->getView('users', 'html');
			$view->assignRef('user', clone $userprofile);

			$view->assignRef('user_fields', $lists["user_fields"]);
			$view->assignRef('nr_ads', $lists["nr_ads"]);
			$view->assignRef('last_ads_placed', $lists["last_ads_placed"]);
			$view->assignRef('first_ads_placed', $lists["first_ads_placed"]);
			$view->assignRef('balance', $balance);
			$view->display();

		}


		/**
		 *
		 */
		public function Reported_Offers()
		{
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$cfg = JTheFactoryHelper::getConfig();

			$where = array();

			$context = 'com_rbids.reported_auctions';
			$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'title', 'cmd');
			$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
			$filter_progress = $app->getUserStateFromRequest($context . 'filter_progress', 'filter_progress', '', 'string');
			$filter_solved = $app->getUserStateFromRequest($context . 'filter_solved', 'filter_solved', '0', 'string');
			$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
			$search = JString::strtolower($search);
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$order = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . '';

			if ($search)
				$where[] = "b.title LIKE '%$search%' OR a.message LIKE '%$search%'";
			if (isset($filter_progress) && $filter_progress != "") {
				$where[] = "a.processing = '$filter_progress' ";
			}
			if (isset($filter_solved) && $filter_solved != "") {
				$where[] = "a.solved = '$filter_solved' ";
			}

			// Build the where clause of the content record query
			$where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

			// Get the total number of records
			$query = "SELECT count(*) FROM #__rbid_report_auctions a " .
				"LEFT JOIN #__rbid_auctions AS b ON a.auction_id=b.id " .
				"LEFT JOIN #__users AS u ON a.userid=u.id " .
				$where;
			$db->setQuery($query);
			$total = $db->loadResult();

			// Create the pagination object
			jimport('joomla.html.pagination');
			$pagination = new JPagination($total, $limitstart, $limit);

			// Get the articles
			$query = "SELECT a.*,b.title,u.username,b.close_by_admin FROM #__rbid_report_auctions a " .
				"LEFT JOIN #__rbid_auctions AS b ON a.auction_id=b.id " .
				"LEFT JOIN #__users AS u ON a.userid=u.id " .
				$where . $order;
			$db->setQuery($query, $pagination->limitstart, $pagination->limit);
			$rows = $db->loadObjectList();

			// table ordering
			$lists['order_Dir'] = $filter_order_Dir;
			$lists['order'] = $filter_order;

			// search filter
			$lists['search'] = $search;
			$lists['progress'] = $filter_progress;
			$lists['solved'] = $filter_solved;

			$view = $this->getView('auction', 'html');

			$view->assignRef('auctions', $rows);
			$view->assignRef('pagination', $pagination);
			$view->assignRef('order_Dir', $filter_order_Dir);
			$view->assignRef('order', $filter_order);
			$view->assignRef('search', $search);
			$view->assignRef('progress', $filter_progress);
			$view->assignRef('solved', $filter_solved);
			$view->assignRef('cfg', $cfg);

			$view->display('list_reported');

		}

		/**
		 *
		 */
		public function Change_Reported_Status()
		{
			$cid = JRequest::getVar("cid", array());
			$database = & JFactory::getDBO();

			if (count($cid) < 1) {
				echo "<script type='text/javascript'> alert('" . JText::_("COM_RBIDS_NO_AUCTION_SELECTED") . "'); window.history.go(-1);</script>\n";
				exit;
			}
			$status = JRequest::getVar('status', '');

			switch ($status) {
				default:
				case "solved":
					$set = " solved=1, processing=0 ";
					break;
				case "unsolved":
					$set = " solved=0 ";
					break;
				case "processing":
					$set = " solved=0, processing=1 ";
					break;
				case "unprocessing":
					$set = " processing=0 ";
					break;
			}


			$cids = implode(',', $cid);

			$query = "UPDATE #__rbid_report_auctions"
				. "\n SET $set "
				. "\n WHERE auction_id IN ( $cids )";
			$database->setQuery($query);
			if (!$database->query()) {
				echo "<script type='text/javascript'> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
				exit();
			}
			$this->setRedirect(JURI::root() . 'administrator/index.php?option=com_rbids&task=reported_offers', JText::_("COM_RBIDS_REPORT_STATUS_CHANGED"));
		}

		public function Dashboard()
		{
			$app = JFactory::getApplication();
			$db = JFactory::getDbo();


			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 1 AND close_by_admin <> 1";
			$db->setQuery($query);
			$nr_active = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions ";
			$db->setQuery($query);
			$nr_total = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 1 ";
			$db->setQuery($query);
			$nr_published = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 0 ";
			$db->setQuery($query);
			$nr_unpublished = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_by_admin = 1";
			$db->setQuery($query);
			$nr_blocked = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 1";
			$db->setQuery($query);
			$nr_expired = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_messages";
			$db->setQuery($query);
			$nr_messages = $db->loadResult();

			$query = "SELECT COUNT(distinct userid) FROM #__rbid_auctions";
			$db->setQuery($query);
			$nr_a_users = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__users";
			$db->setQuery($query);
			$nr_r_users = $db->loadResult();


			// Get latest 5 auctions
			$query = "SELECT `a`.`id`,
						 `a`.`title`,
						 `a`.`start_date`,
						 `a`.`currency`,
						 `a`.`end_date`,
						 MIN(`bids`.`bid_price`) AS `min_bid`,
						 MAX(`bids`.`bid_price`) AS `max_bid`,
						 `u`.`id` AS `user_id`,
						 `u`.`username` AS `owner`
					   FROM `#__rbid_auctions` AS `a`
					   LEFT JOIN `#__users` AS `u` ON `u`.`id` = `a`.`userid`
					   LEFT JOIN `#__rbids` AS `bids` ON `bids`.`auction_id` = `a`.`id`
					   GROUP BY `a`.`id`
					   ORDER BY `a`.`id` DESC
					   LIMIT 5
                                 ";
			$db->setQuery($query);
			$latest5auctions = $db->loadObjectList();

			// Get latest 5 payments
			$query = "SELECT `paylog`.`id`,
						 `paylog`.`orderid`,
						 `paylog`.`amount`,
						 `paylog`.`currency`,
						 `paylog`.`payment_method`,
						 `paylog`.`status`,
						 `paylog`.`date`,
						 `paylog`.`userid`,
						 `u`.`username`
					FROM `#__rbid_payment_log` AS `paylog`
					LEFT JOIN `#__users` AS `u` ON `u`.`id` = `paylog`.`userid`
				   ORDER BY `paylog`.`id` DESC
				   LIMIT 5
			";
			$db->setQuery($query);
			$latest5payments = $db->loadObjectList();

			// table ordering
			$lists['nr_r_users'] = $nr_r_users;
			$lists['nr_a_users'] = $nr_a_users;
			$lists['nr_messages'] = $nr_messages;
			$lists['nr_total'] = $nr_total;
			$lists['nr_active'] = $nr_active;
			$lists['nr_published'] = $nr_published;
			$lists['nr_unpublished'] = $nr_unpublished;
			$lists['nr_blocked'] = $nr_blocked;
			$lists['nr_expired'] = $nr_expired;


			$view = $this->getView('dashboard', 'html');
			$view->assignRef('latest5auctions', $latest5auctions);
			$view->assignRef('latest5payments', $latest5payments);
			$view->assignRef('lists', $lists);
			$view->display();

		}

		/**
		 *
		 */
		public function Statistics()
		{
			$app = & JFactory::getApplication();
			$db = & JFactory::getDBO();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 1 AND close_by_admin <> 1";
			$db->setQuery($query);
			$nr_active = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions ";
			$db->setQuery($query);
			$nr_total = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 1 ";
			$db->setQuery($query);
			$nr_published = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 0 ";
			$db->setQuery($query);
			$nr_unpublished = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_by_admin = 1";
			$db->setQuery($query);
			$nr_blocked = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_auctions WHERE close_offer = 1";
			$db->setQuery($query);
			$nr_expired = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__rbid_messages";
			$db->setQuery($query);
			$nr_messages = $db->loadResult();

			$query = "SELECT COUNT(distinct userid) FROM #__rbid_auctions";
			$db->setQuery($query);
			$nr_a_users = $db->loadResult();

			$query = "SELECT COUNT(*) FROM #__users";
			$db->setQuery($query);
			$nr_r_users = $db->loadResult();

			$filter_order_Dir = $app->getUserStateFromRequest('statistics.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest('global.limitstart', 'limitstart', 0, 'int');
			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			// Get the total number of records
			$query = "SELECT COUNT(*) " .
				"FROM #__users";
			$db->setQuery($query);
			$total = $db->loadResult();

			// Create the pagination object
			jimport('joomla.html.pagination');
			$pagination = new JPagination($total, $limitstart, $limit);

			// Get the articles
			$query = "
                SELECT u.id, u.username, count( a.userid ) as nr_ads ,
                sum( if(featured<>'none',1,0)) as nr_featured,
                sum( if(published='1' and close_offer<>'1',1,0)) as nr_published,
                sum( if(close_offer='1',1,0)) as nr_closed,
                sum( if(close_by_admin='1',1,0)) as nr_blocked
                FROM #__users AS u 
                LEFT JOIN #__rbid_auctions AS a ON a.userid = u.id 
                GROUP BY u.username $filter_order_Dir        
        ";
			$db->setQuery($query, $pagination->limitstart, $pagination->limit);
			$rows = $db->loadObjectList();

			// table ordering
			$lists['nr_r_users'] = $nr_r_users;
			$lists['nr_a_users'] = $nr_a_users;
			$lists['nr_messages'] = $nr_messages;
			$lists['nr_total'] = $nr_total;
			$lists['nr_active'] = $nr_active;
			$lists['nr_published'] = $nr_published;
			$lists['nr_unpublished'] = $nr_unpublished;
			$lists['nr_blocked'] = $nr_blocked;
			$lists['nr_expired'] = $nr_expired;
			$lists['order_Dir'] = $filter_order_Dir;

			$view = $this->getView('statistics', 'html');
			$view->assignRef('rows', $rows);
			$view->assignRef('pagination', $pagination);
			$view->assignRef('lists', $lists);
			$view->display();

		}

		/**
		 *
		 */
		public function Googlemap_Tool()
		{
			$format = JRequest::getVar('format', 'html');
			$cfg =& JTheFactoryHelper::getConfig();
			JHtml::_('behavior.mootools');
			$view = $this->getView('settings', $format);
			$view->assignRef('cfg', $cfg);
			$view->display('googlemap_tool');
		}

		/**
		 *
		 */
		public function Write_Admin_Message()
		{
			$auction_id = JRequest::getInt('auction_id', 0);
			$return_task = JRequest::getWord('return_task', 'offers');

			$auction =& JTable::getInstance('auctions', 'Table');
			if (!$auction->load($auction_id)) {
				$this->setRedirect("index.php?option=com_rbids&task=$return_task", JText::_("COM_RBIDS_ERROR__AUCTION_DOES_NOT_EXIST") . $auction_id);
				return;
			}

			$usr =& JFactory::getUser($auction->userid);
			$auction->username = $usr->username;

			$view = $this->getView('auction', 'html');
			$view->assignRef('auction', $auction);
			$view->assignRef('user', $usr);
			$view->assignRef('return_task', $return_task);
			$view->display('adminmessage');
		}

		/**
		 *
		 */
		public function Send_Message_Auction()
		{

			$auction_id = JRequest::getInt('auction_id', 0);
			$return_task = JRequest::getWord('return_task', 'offers');
			$message = JRequest::getVar('message', '');
			$is_private = JRequest::getInt('isprivate');
			$auction =& JTable::getInstance('auctions', 'Table');

			if (!$auction->load($auction_id)) {
				$this->setRedirect("index.php?option=com_rbids&task=$return_task", JText::_("COM_RBIDS_ERROR__AUCTION_DOES_NOT_EXIST") . $auction_id);
				return;
			}
			if (!$message) {
				$this->setRedirect("index.php?option=com_rbids&task=edit&cid=" . $auction->id, JText::_("COM_RBIDS_YOU_CAN_NOT_SEND_AN_EMPTY_MESSAGE"));
				return;
			}

			$auction->store();
			$auction->sendNewMessage($message, null, null, $is_private);
			//echo $database->getQuery();

			$owner =& JFactory::getUser($auction->userid);

			$auction->SendMails(array($owner), 'bid_admin_message');

			$this->setRedirect("index.php?option=com_rbids&task=edit&cid=" . $auction->id, JText::_("COM_RBIDS_MESSAGE_SENT"));

		}

		/**
		 *
		 */
		public function ChangeProfileIntegration()
		{
			$MyApp =& JTheFactoryApplication::getInstance();

			$cfg =& JTheFactoryHelper::getConfig();
			$cfg->profile_mode = JRequest::getVar('profile_mode');

			JTheFactoryHelper::modelIncludePath('config');

			$formxml = JPATH_ROOT . DS . "administrator" . DS . "components" . DS . APP_EXTENSION . DS . $MyApp->getIniValue('configxml');
			$model =& JModel::getInstance('Config', 'JTheFactoryModel', array('formxml' => $formxml));

			$model->save($cfg);

			$this->setRedirect("index.php?option=com_rbids&task=integration", JText::_("COM_RBIDS_SETTINGS_SAVED"));

		}

		/**
		 *
		 */
		public function Integration()
		{
			$cfg =& JTheFactoryHelper::getConfig();

			$profile_modes = array();
			$profile_modes[] = JHTML::_('select.option', 'component', 'Component Profile');
			$profile_modes[] = JHTML::_('select.option', 'cb', 'Community Builder');
			$profile_modes[] = JHTML::_('select.option', 'love', 'Love Factory');

			$view = $this->getView('settings', 'html');
			$view->assign('profile_select_list', JHTML::_("select.genericlist", $profile_modes, 'profile_mode', '', 'value', 'text', $cfg->profile_mode));
			$view->assign('current_profile_mode', $cfg->profile_mode);
			$view->assign('configure_link', "index.php?option=com_rbids&task=integrationconfiguration");

			$view->display('integration');

		}

		/**
		 *
		 */
		public function IntegrationConfiguration()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			if ($cfg->profile_mode == 'component') {
				$view = $this->getView('settings', 'html');
				$view->display('integration');
				return;
			} else {
				$MyApp = & JTheFactoryApplication::getInstance();
				$integrationClass = 'JTheFactoryIntegration' . ucfirst($cfg->profile_mode);
				JLoader::register($integrationClass, $MyApp->app_path_admin . 'integration/' . $cfg->profile_mode . '.php');

				$controller_class = 'JTheFactoryIntegration' . $cfg->profile_mode . 'Controller';
				$controller = new $controller_class;
				$controller->execute('display');
				return;
			}

		}

		/**
		 *
		 */
		public function PurgeCache()
		{
			jimport('joomla.filesystem.folder');
			$dir = AUCTION_TEMPLATE_CACHE;
			if (!JFolder::exists($dir))
				JFolder::create($dir);
			$requested_by = $_SERVER['HTTP_REFERER'];
			if (JFolder::exists($dir)) {
				if (is_writable($dir)) {
					$smarty = new JTheFactorySmarty();
					$smarty->clear_compiled_tpl();
					$this->setRedirect($requested_by, JText::_("COM_RBIDS_CACHED_CLEARED"));
				} else
					$this->setRedirect($requested_by, JText::_("COM_RBIDS_PERMISSION_UNAVAILABLE_FOR_THIS"));
			} else
				$this->setRedirect($requested_by, JText::_("COM_RBIDS_CACHE_FOLDER_DOESNT_EXIST"));
		}

		/**
		 *
		 */
		public function Reviews_Administrator()
		{
			$app =& JFactory::getApplication();

			$context = 'com_rbids.reviews.';
			$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'modified', 'cmd');
			$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'ASC', 'word');

			$limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');


			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$db =& JFactory::getDBO();
			$db->setQuery(
				"SELECT r.* , u.username as rauthor, ur.username as urated, a.title as auctiontitle
                FROM `#__rbid_rate` as r
                LEFT JOIN #__users AS u ON r.voter = u.id
                LEFT JOIN #__users AS ur ON r.user_rated = ur.id
                LEFT JOIN #__rbid_auctions as a on a.id=r.auction_id
                ORDER BY $filter_order $filter_order_Dir ",
				$limitstart, $limit
			);

			$list = $db->loadObjectList();

			$db->setQuery("select count(*) from `#__rbid_rate`");
			$total = $db->loadResult();

			jimport('joomla.html.pagination');
			$page = new JPagination($total, $limitstart, $limit);

			$view = $this->getView('ratings', 'html');
			$view->assignRef('page', $page);
			$view->assignRef('ratings', $list);
			$view->assign('order', $filter_order);
			$view->assign('order_Dir', $filter_order_Dir);
			$view->display();
		}

		/**
		 *
		 */
		public function Del_Review()
		{
			$id = JRequest::getInt("id");
			$db =& JFactory::getDBO();
			$db->setQuery("DELETE FROM `#__rbid_rate` WHERE id = $id");
			$db->query();
			$this->setRedirect("index.php?option=com_rbids&task=reviews_administrator", JText::_("COM_RBIDS_REVIEW_REMOVED"));

		}

		/**
		 *
		 */
		public function Del_Comment()
		{
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			foreach ($cid as $id) {
				$db =& JFactory::getDBO();
				$db->setQuery("DELETE FROM #__rbid_messages WHERE id = $id");
				$db->query();
			}
			$this->setRedirect("index.php?option=com_rbids&task=comments_administrator", JText::_("COM_RBIDS_COMMENT_REMOVED"));
		}

		/**
		 *
		 */
		public function Toggle_Comment()
		{
			$cid = JRequest::getVar('cid', array(), 'request', 'array');
			foreach ($cid as $id) {
				$db =& JFactory::getDBO();
				$db->setQuery("UPDATE #__rbid_messages SET published=1-published WHERE id = $id");
				$db->query();
			}
			$this->setRedirect("index.php?option=com_rbids&task=comments_administrator", JText::_("COM_RBIDS_COMMENT_STATUS_TOGGLED"));
		}

		/**
		 *
		 */
		public function Comments_Administrator()
		{

			$app = JFactory::getApplication();
			$cfg = JTheFactoryHelper::getConfig();
			$context = 'com_rbids.comments.';
			$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'modified', 'cmd');
			$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'ASC', 'word');

			$limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$db =& JFactory::getDBO();
			$db->setQuery("SELECT COUNT(*) FROM #__rbid_messages");
			$total = $db->loadResult();

			$db->setQuery(
				" SELECT `r`.* , `u`.`username` AS `rauthor`, `ur`.`username` AS `user_replied`, `a`.`title` AS `auctiontitle`
                                     FROM `#__rbid_messages` AS `r`
                                     LEFT JOIN `#__users` AS `u` ON `r`.`userid1` = `u`.`id`
                                     LEFT JOIN `#__users` AS `ur` ON `r`.`userid2` = `ur`.`id`
                                     LEFT JOIN `#__rbid_auctions` AS `a` ON `r`.`auction_id` = `a`.`id`
                                     ORDER BY {$filter_order} {$filter_order_Dir} ",
				$limitstart, $limit
			);

			$rows = $db->loadObjectList();

			jimport('joomla.html.pagination');
			$pagination = new JPagination($total, $limitstart, $limit);

			$view = $this->getView('messages', 'html');
			$view->assign('order', $filter_order);
			$view->assign('order_Dir', $filter_order_Dir);
			$view->assignRef('messages', $rows);
			$view->assignRef('pagination', $pagination);
			$view->assignRef('cfg', $cfg);
			$view->display();

		}

		/**
		 *
		 */
		public function installTemplates()
		{

			jimport('joomla.filesystem.folder');
			if (JFolder::exists(JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'templates-dist')) {
				JFolder::delete(JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'templates');
				JFolder::move(JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'templates-dist',
					JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'templates');
				$message = JText::_('COM_RBIDS_TEMPLATES_OVERWRITTEN');
			} else {
				$message = JText::_('COM_RBIDS_NEW_TEMPLATES_NOT_FOUND_PLEASE_CHECK_INSTALLATION_KIT');
			}
			$this->setRedirect('index.php?option=com_rbids&task=settingsmanager', $message);
		}

		/**
		 *
		 */
		public function postUpgrade()
		{
			$view = $this->getView('install', 'html');
			$view->display("upgrade");
		}
	}

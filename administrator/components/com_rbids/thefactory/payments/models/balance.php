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
	 * @subpackage: payments
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	jimport('joomla.application.component.model');

	class JTheFactoryModelBalance extends JModel
	{
		var $context = 'balance';
		var $tablename = null;

		function __construct()
		{
			$this->context = APP_EXTENSION . "_balance.";
			$this->tablename = '#__' . APP_PREFIX . '_payment_balance';
			JTheFactoryHelper::tableIncludePath('payments');
			parent::__construct();
		}

		/**
		 * @param null $userid
		 *
		 * @return mixed
		 */
		function getUserBalance($userid = null)
		{
			if (!$userid) {
				$my =& JFactory::getUser();
				$userid = $my->id;
			}
			$balance = JTable::getInstance('BalanceTable', 'JTheFactory');
			// Add balance with sold 0.00 for this user (Case: no payments made until now)
			if (!$balance->load($userid)) {
				$modelCurrency = JModel::getInstance('Currency', 'JTheFactoryModel');
				$defaultCurrency = $modelCurrency->getDefault();

				$balance->addBalance($userid, 0.00, $defaultCurrency);

				// Is not really necessary but in any case
				$balance->load($userid);
			}

			return $balance;
		}

		/**
		 * @param      $amount
		 * @param null $userid
		 *
		 * @return mixed
		 */
		function decreaseBalance($amount, $userid = null)
		{
			if (!$userid) {
				$my =& JFactory::getUser();
				$userid = $my->id;
			}
			$balance = JTable::getInstance('BalanceTable', 'JTheFactory');
			if (!$balance->load($userid)) {
				$balance->addBalance($userid);
			}
			$balance->userid = $userid;
			$balance->balance -= $amount;
			if (!$balance->currency) {
				$model =& JModel::getInstance('Currency', 'JTheFactoryModel');
				$balance->currency = $model->getDefault();
			}
			$balance->store();
			return $balance;
		}

		/**
		 * @param      $amount
		 * @param null $userid
		 *
		 * @return mixed
		 */
		function increaseBalance($amount, $userid = null)
		{
			if (!$userid) {
				$my =& JFactory::getUser();
				$userid = $my->id;
			}
			$balance = JTable::getInstance('BalanceTable', 'JTheFactory');
			if (!$balance->load($userid))
				$balance->addBalance($userid);

			$balance->userid = $userid;
			$balance->balance += $amount;
			if (!$balance->currency) {
				$model =& JModel::getInstance('Currency', 'JTheFactoryModel');
				$balance->currency = $model->getDefault();
			}
			$balance->store();

			return $balance;
		}

		/**
		 * @return mixed
		 */
		function getBalancesList()
		{
			$db =& $this->getDbo();
			$app =& JFactory::getApplication();

			$filter_userid = $app->getUserStateFromRequest($this->context . 'filter_userid', 'filter_userid', '', 'string');
			$filter_balances = $app->getUserStateFromRequest($this->context . 'filter_balances', 'filter_balances', 1, 'int');

			$limit = $app->getUserStateFromRequest($this->context . "limit", 'limit', $app->getCfg('list_limit'));
			$limitstart = $app->getUserStateFromRequest($this->context . "limitstart", 'limitstart', 0);

			$this->set('filter_userid', $filter_userid);
			$this->set('filter_balances', $filter_balances);

			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->getTotal(), $limitstart, $limit);

			$where = "WHERE 1=1 ";
			if ($this->get('filters')) $where .= "AND " . $this->get('filters');
			if ($filter_userid) $where .= " AND `u`.`username` LIKE '%$filter_userid%' ";

			if ($filter_balances == 1) $where .= " AND `p`.`balance` IS NOT NULL AND `p`.`balance` <> 0 ";
			if ($filter_balances == 2) $where .= " AND `p`.`balance` IS NOT NULL AND `p`.`balance` < 0 ";
			if ($filter_balances == 3) $where .= " AND `p`.`req_withdraw` IS NOT NULL AND `p`.`req_withdraw` > 0 ";

			$db->setQuery("SELECT `p`.`balance`,
							  `p`.`currency`,
							  `p`.`req_withdraw`,
							  `p`.`withdrawn_until_now`,
							  `p`.`last_withdraw_date`,
							  `u`.`username`,
							  `u`.`id` AS `userid`
						  FROM `#__users` AS `u`
						  LEFT JOIN `{$this->tablename}` AS `p` ON `u`.`id` = `p`.`userid`
						  {$where}
						  ORDER BY `username`",
				$limitstart, $limit);

			return $db->loadObjectList();
		}

		/**
		 * @return mixed
		 */
		function getTotal()
		{
			$db =& $this->getDbo();
			$db->setQuery("select count(*) from `{$this->tablename}` ");
			return $db->loadResult();
		}

		/**
		 * Set Request Withdraw
		 *
		 * @param      $amount
		 * @param null $userid
		 *
		 * @return bool|mixed
		 */
		public function setReqWithdraw($amount, $userid = null)
		{
			if (!$userid) {
				$my =& JFactory::getUser();
				$userid = $my->id;
			}
			$balance = JTable::getInstance('BalanceTable', 'JTheFactory');
			if (!$balance->load($userid)) {
				return false;
			}
			$balance->req_withdraw += $amount;
			$balance->last_withdraw_date = date('Y-m-d');
			$balance->balance -= $amount;
			$balance->store();

			return $balance;
		}

		/**
		 * Last step of payment  for withdraw requested amount
		 *
		 * @param float $amount Amount paid by an admin. Is reflected in payments log table
		 * @param int   $userid User that receive amount requested
		 *
		 * @return bool|object
		 */
		public function lastStepWithdraw($amount, $userid)
		{

			$balance = JTable::getInstance('BalanceTable', 'JTheFactory');
			if (!$balance->load($userid)) {
				return false;
			}
			$balance->req_withdraw -= $amount;
			$balance->paid_withdraw_date = date('Y-m-d');
			$balance->withdrawn_until_now += $amount;
			$balance->store();

			return $balance;
		}

	}

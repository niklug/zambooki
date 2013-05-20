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
	/**
	 *      Status - P=pending, C=completed, X=cancelled, R=refunded
	 */
	class JTheFactoryModelOrders extends JModel
	{
		var $context = 'orders';
		var $tablename = null;
		var $table_items = null;
		var $pagination = null;

		/**
		 *
		 */
		public function __construct()
		{
			$this->context = APP_EXTENSION . "_orders.";
			$this->tablename = '#__' . APP_PREFIX . '_payment_orders';
			$this->table_items = '#__' . APP_PREFIX . '_payment_orderitems';
			JTheFactoryHelper::tableIncludePath('payments');
			parent::__construct();
		}

		/**
		 * @param        $items
		 * @param null   $total
		 * @param null   $currency
		 * @param null   $userid
		 * @param string $status
		 *
		 * @return mixed
		 */
		public function createNewOrder($items, $total = null, $currency = null, $userid = null, $status = 'P')
		{
			if (!$userid) {
				$my =& JFactory::getUser();
				$userid = $my->id;
			}
			if (!is_array($items)) $items = array($items);

			$order =& JTable::getInstance('OrdersTable', 'JTheFactory');
			$orderitem =& JTable::getInstance('OrderItemsTable', 'JTheFactory');
			$date =& JFactory::getDate();
			$order->id = null;
			$order->orderdate = $date->toSQL();
			$order->userid = $userid;
			$order->order_total = $total;
			$order->order_currency = $currency;
			$order->status = $status;
			$order->store();

			$t = 0;
			$c = null;
			foreach ($items as $item) {
				$orderitem->id = null;
				$orderitem->orderid = $order->id;
				$orderitem->itemname = $item->itemname;
				$orderitem->itemdetails = $item->itemdetails;
				$orderitem->iteminfo = $item->iteminfo;
				$orderitem->price = $item->price;
				$orderitem->currency = $item->currency;
				$orderitem->params = $item->params;
				$orderitem->quantity = $item->quantity;
				$orderitem->store();
				$t += $item->price;
				$c = $item->currency;
			}
			if (!$total) {
				$order->order_total = $t;
				$order->order_currency = $c;
				$order->store();
			}
			return $order;
		}

		/**
		 * @param      $orderid
		 * @param null $itemname
		 *
		 * @return mixed
		 */
		public function getOrderItems($orderid, $itemname = null)
		{
			$db =& $this->getDbo();
			$db->setQuery("SELECT * FROM `{$this->table_items}` WHERE `orderid` = '{$orderid}'" . ($itemname ? " AND `itemname` = '{$itemname}'" : ""));
			return $db->loadObjectList();
		}

		/**
		 * @param null $username
		 *
		 * @return mixed
		 */
		public function getOrdersList($username = null)
		{
			$db =& $this->getDbo();
			$app =& JFactory::getApplication();

			$limit = $app->getUserStateFromRequest($this->context . "limit", 'limit', $app->getCfg('list_limit'));
			$limitstart = $app->getUserStateFromRequest($this->context . "limitstart", 'limitstart', 0);

			$status = $app->getUserStateFromRequest($this->context . "status", 'status', 0);
			$type = $app->getUserStateFromRequest($this->context . "type", 'type', 0);

			// Apply filters
			$where = 'WHERE 1';
			$ord_items_join = '';

			if ($username) $where .= " AND `u`.`username` LIKE '%" . $db->escape($username) . "%' ";
			if ($status) $where .= " AND `o`.`status`= '" . $db->escape($status) . "'";

			if ($type && 'to_me' == $type) {
				$ord_items_join = "LEFT JOIN `#__rbid_payment_orderitems` AS `ord_items` ON `ord_items`.`orderid` = `o`.`id`";
				$where .= " AND `ord_items`.`itemname`= 'withdraw'";

			} elseif ($type && 'by_me' == $type) {
				$ord_items_join = "LEFT JOIN `#__rbid_payment_orderitems` AS `ord_items` ON `ord_items`.`orderid` = `o`.`id`";
				$where .= " AND `ord_items`.`itemname`!= 'withdraw'";
			}

			jimport('joomla.html.pagination');

			$this->pagination = new JPagination($this->getTotal($username), $limitstart, $limit);

			$db->setQuery("SELECT `o`.*, `u`.`username`
						  FROM `{$this->tablename}` AS `o`
						  LEFT JOIN `#__users` AS `u` ON `u`.`id` = `o`.`userid`
						  {$ord_items_join}
						  {$where}
						  ORDER BY `id` DESC",
				$limitstart, $limit
			);

			return $db->loadObjectList();
		}

		/**
		 * @param null $username
		 *
		 * @return mixed
		 */
		public function getTotal($username = null)
		{
			$db =& $this->getDbo();
			$where = "";
			if ($username) $where = " WHERE `u`.`username` LIKE '%" . $db->escape($username) . "%' ";
			$db =& $this->getDbo();
			$db->setQuery("SELECT COUNT(*)
						  FROM `{$this->tablename}` AS `o`
						  LEFT JOIN `#__users` AS `u` ON `u`.`id` = `o`.`userid`
						  {$where}
					");
			return $db->loadResult();
		}

		/**
		 * @param $order
		 *
		 * @return mixed
		 */
		public function confirmOrder($order)
		{
			if ($order->status == 'C')
				return;

			$paylog =& JTable::getInstance('PaymentLogTable', 'JTheFactory');
			if ($order->paylogid)
				$paylog->load($order->paylogid);
			$date = new JDate();
			$paylog->date = $date->toSQL();
			$paylog->amount = $order->order_total;
			$paylog->currency = $order->order_currency;
			$paylog->refnumber = JText::_("FACTORY_ADMIN_APPROVED");
			$paylog->invoice = $order->id;
			$paylog->ipn_response = "";
			$paylog->ipn_ip = $_SERVER['REMOTE_ADDR'];
			$paylog->status = 'ok';
			$paylog->userid = $order->userid;
			$paylog->orderid = $order->id;
			$paylog->payment_method = JText::_("FACTORY_ADMIN_APPROVED");
			$paylog->store();

			$order->status = 'C';
			$order->paylogid = $paylog->id;
			$order->store();
			return $paylog;
		}

	}

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
	 * @subpackage: payments
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryPaymentsController extends JController
	{
		var $name = 'Payments';
		var $_name = 'Payments';

		function __construct($config = array())
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$lang =& JFactory::getLanguage();
			$lang->load('thefactory.payments');

			$config['view_path'] = $MyApp->app_path_front . 'payments' . DS . "views";

			parent::__construct($config);
			JTheFactoryHelper::modelIncludePath('payments');
			JTheFactoryHelper::tableIncludePath('payments');

		}

		function getView($name = '', $type = 'html', $prefix = '', $config = array())
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$config['template_path'] = $MyApp->app_path_front . 'payments' . DS . "views" . DS . strtolower($name) . DS . "tmpl";

			return parent::getView($name, $type, 'JTheFactoryViewPayments', $config);
		}

		function History()
		{
			$Itemid = JRequest::getInt('Itemid');
			$user = JFactory::getUser();
			$cfg = JTheFactoryHelper::getConfig();
			$app =& JFactory::getApplication();
			$doc = JFactory::getDocument();
			// Get stylesheet for active template
			$doc->addStyleSheet(JURI::root() . 'components/' . APP_EXTENSION . '/templates/' . $cfg->theme . '/bid_template.css');

			$status = $app->getUserStateFromRequest(APP_EXTENSION . 'status', 'status', 0);
			$type = $app->getUserStateFromRequest(APP_EXTENSION . 'type', 'type', 0);

			$lists = array();
			// Define Status drop down list
			$statusOptions = array();
			$statusOptions[] = JHtml::_('select.option', '', JText::_('FACTORY_FILTER_ALL'));
			$statusOptions[] = JHtml::_('select.option', 'C', JText::_('FACTORY_FILTER_COMPLETED'));
			$statusOptions[] = JHtml::_('select.option', 'P', JText::_('FACTORY_FILTER_PENDING'));
			$statusOptions[] = JHtml::_('select.option', 'X', JText::_('FACTORY_FILTER_CANCELED'));
			$lists['status'] = JHtml::_('select.genericlist', $statusOptions, 'status', "onchange=this.form.submit();", 'value', 'text', $status);

			// Define Type drop down list
			$typeOptions = array();
			$typeOptions[] = JHtml::_('select.option', '', JText::_('FACTORY_FILTER_ALL'));
			$typeOptions[] = JHtml::_('select.option', 'to_me', JText::_('FACTORY_FILTER_WITHDRAW'));
			$typeOptions[] = JHtml::_('select.option', 'by_me', JText::_('FACTORY_FILTER_PAY'));
			$lists['type'] = JHtml::_('select.genericlist', $typeOptions, 'type', "onchange=this.form.submit();", 'value', 'text', $type);

			$modelOrders = JTheFactoryPricingHelper::getModel('orders');
			$orders = $modelOrders->getOrdersList($user->username);

			// Add funds to site balance
			// Since 3.2.0
			$balance =& JModel::getInstance('balance', 'JTheFactoryModel');
			$currency =& JModel::getInstance('currency', 'JTheFactoryModel');
			$user_balance = $balance->getUserBalance();
			$default_currency = $currency->getDefault();

			$view = $this->getView('payments');

			// Assign modelOrders to template
			// because we need iterate order items for each order
			$view->assign('modelOrders', $modelOrders);

			$view->assign('orders', $orders);
			$view->assign('type', $type);
			$view->assign('status', $status);
			$view->assign('pagination', $modelOrders->pagination);
			$view->assign('cfg', $cfg);
			$view->assign('lists', $lists);
			$view->assign('Itemid', $Itemid);

			$view->assign('balance', $user_balance);
			$view->assign('currency', $default_currency);

			$view->display('history');

		}

		function Cancel()
		{

			$orderid = JRequest::getVar('id');
			$itemId = JRequest::getInt('Itemid');

			if (is_array($orderid)) $orderid = $orderid[0];

			$order =& JTable::getInstance('OrdersTable', 'JTheFactory');

			if (!$orderid || !$order->load($orderid)) {
				$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=payments.history&Itemid=' . $itemId, JText::_("FACTORY_ORDER_DOES_NOT_EXIST"));
				return;
			}
			if ($order->status == 'X') {
				$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=orderprocessor.details&orderid=' . $orderid . '&Itemid=' . $itemId, JText::_("FACTORY_ORDER_IS_ALREADY_CANCELLED"));
				return;
			}
			$order->modifydate = date('Y-m-d H:i:s');
			$order->status = 'X';
			$order->store();

			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=orderprocessor.details&orderid=' . $orderid . '&Itemid=' . $itemId, JText::_("FACTORY_ORDER_WAS_CANCELLED"));
		}

	}

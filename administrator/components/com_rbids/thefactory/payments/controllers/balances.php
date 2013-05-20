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

	class JTheFactoryBalancesController extends JTheFactoryController
	{
		var $name = 'Balances';
		var $_name = 'Balances';

		public function __construct()
		{
			parent::__construct('payments');
			JHtml::addIncludePath($this->basepath . DS . 'html');
		}

		public function Listing()
		{
			$model = JModel::getInstance('Balance', 'JTheFactoryModel');
			$rows = $model->getBalancesList();

			$opts = array();
			$opts[] = JHTML::_('select.option', '', JText::_("FACTORY_ALL"));
			$opts[] = JHTML::_('select.option', '1', JText::_("FACTORY_ALL_WITH_NON_ZERO_BALANCES"));
			$opts[] = JHTML::_('select.option', '2', JText::_("FACTORY_ALL_WITH_NEGATIVE_BALANCES"));
			$opts[] = JHTML::_('select.option', '3', JText::_("FACTORY_ALL_WITH_REQUEST_WITHDRAWAL"));

			$filter_balances = JHtml::_('select.genericlist', $opts, 'filter_balances', "class='inputbox'", 'value', 'text', $model->get('filter_balances'));

			$view = $this->getView('balance');
			$view->assign('userbalances', $rows);
			$view->assign('filter_userid', $model->get('filter_userid'));
			$view->assign('filter_balances', $filter_balances);
			$view->assign('pagination', $model->get('pagination'));
			$view->display('list');
		}

		/**
		 * PayPal form displayed in admin payments
		 * as payment for withdrawal request
		 */
		public function withdrawForm()
		{
			// Push to template various variables
			$lists = array();

			// Get request variables
			$gateway = JRequest::getString('gateway', 'pay_paypal');
			$userId = JRequest::getInt('userId');

			$amountToPay = JRequest::getFloat('amountToPay');
			$currency = JRequest::getString('currency');
			$lastWithdrawDate = JRequest::getString('last_withdraw_date');

			$lists['amountToPay'] = $amountToPay;
			$lists['currency'] = $currency;
			$lists['last_withdraw_date'] = $lastWithdrawDate;

			// TheFactory Config
			$cfg = JTheFactoryHelper::getConfig();

			// Load necessaries models
			$modelGateways =& JModel::getInstance('Gateways', 'JTheFactoryModel');
			$modelOrders = JModel::getInstance('Orders', 'JTheFactoryModel');

			// Load PayPal gateway
			$gw = $modelGateways->getGatewayObject($gateway);

			// Load user profile for receiver
			$receiver = new JTheFactoryUserProfile();
			$receiver->getUserProfile($userId);

			$lists['receiver'] = $receiver;

			// Create New pending order
			$items = new stdClass();

			$items->itemname = 'withdraw';
			$items->itemdetails = JText::_('FACTORY_WITHDRAW_ORDER_ITEM_DETAILS');
			$items->iteminfo = $receiver->userid;
			$items->price = $amountToPay;
			$items->quantity = 1;
			$items->currency = $currency;
			$items->params = '';

			$order = $modelOrders->createNewOrder($items, $amountToPay, $currency, $userId, 'P');

			// Create gateway links for return | notify | cancel
			$urls = array();
			$urls['return_url'] = JURI::root() . 'index.php?option=' . APP_EXTENSION . '&task=orderprocessor.withdrawreturning&gateway=' . $gw->name;
			$urls['notify_url'] = JURI::root() . 'index.php?option=' . APP_EXTENSION . '&task=orderprocessor.ipn&orderid=' . $order->id . '&gateway=' . $gw->name;
			$urls['cancel_url'] = JURI::root() . 'index.php?option=' . APP_EXTENSION . '&task=orderprocessor.withdrawcancel&orderid=' . $order->id . '&gateway=' . $gw->name;

			// Set view and assign view variables
			$view = $this->getView('balance');
			$view->assign('withdrawForm', $gw->getWithdrawPaymentForm($order, array($items), $urls, $receiver));
			$view->assign('lists', $lists);
			$view->assign('cfg', $cfg);

			$view->display('do_withdraw');
		}

	}

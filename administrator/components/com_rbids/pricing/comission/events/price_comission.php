<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : RBids
	 * @subpackage: Comission
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventPrice_Comission extends JTheFactoryEvents
	{
		function getItemName()
		{
			return "comission";
		}

		function getContext()
		{
			return APP_PREFIX . "." . self::getItemName();
		}

		function &getModel()
		{
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'pricing' . DS . self::getItemName() . DS . 'models');
			$model =& JModel::getInstance(self::getItemName(), 'JRBidPricingModel');
			return $model;
		}

		function &getTable()
		{
			JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'pricing' . DS . self::getItemName() . DS . 'tables');
			$table =& JTable::getInstance('Comission');
			return $table;
		}

		function onBeforeDisplay($task, $smarty)
		{
			if (!is_object($smarty))
				return;
			if (!in_array($task, array('viewbids', 'details')))
				return;

			$cfg =& JTheFactoryHelper::getConfig();
			$user = JFactory::getUser();

			$curent_info = $smarty->get_template_vars('payment_items_header');
			$auction =& $smarty->get_template_vars('auction');
			if ($user->guest) {
				return;
			}
			if ($auction->close_offer || !$auction->published || (!$auction->isMyAuction() && 1 == $cfg->bid_accept_user_commision))
				return;

			$model = self::getModel();
			$price = $model->getItemPrice($auction->cat);

			$modelbalance = JTheFactoryPricingHelper::getModel('balance');
			$balance = $modelbalance->getUserBalance();

			if (!floatval($price->price)) {
				$priceinfo = JText::_("COM_RBIDS_THERE_IS_NO_COMMISSION_FOR_THIS_CATEGORY");
			} else {

				$priceinfo = JText::_("COM_RBIDS_YOUR_CURRENT_BALANCE_IS") . number_format($balance->balance, 2) . " " . $balance->currency . "<br /><br />";
				$priceinfo .= JText::_("COM_RBIDS_COMMISSION_FOR_THIS_CATEGORY_IS") . number_format($price->price, 2) . "% <br />";
				$priceinfo .= JText::_("COM_RBIDS_THE_COMMISSION_FOR_THE_LOWEST_BID_NOW_WOULD_BE") . number_format($auction->get('lowest_bid') * $price->price / 100, 2) . " " . $auction->currency . "<br />";
				if (1 == $cfg->bid_accept_user_commision) {
					$priceinfo .= JText::_('COM_RBIDS_THE_COMMISSION_FOR_THE_LOWEST_BID_WILL_BE_PAID_BY_THE_AUCTIONEER') . "<br />";
				} elseif (2 == $cfg->bid_accept_user_commision) {
					$priceinfo .= JText::_('COM_RBIDS_THE_COMMISSION_FOR_THE_LOWEST_BID_WILL_BE_PAID_BY_THE_WINNER') . "<br />";
				}
				if ($balance->currency <> $auction->currency) {
					$amount = RBidsHelperPrices::convertCurrency($auction->get('lowest_bid'), $auction->currency, $balance->currency);
					$priceinfo .= " (" . number_format($amount * $price->price / 100, 2) . " $balance->currency)";
				}
				$priceinfo .= "<br />";
			}
			$smarty->assign('payment_items_header', $curent_info . $priceinfo);

		}

		function onAfterAcceptBid($auction, $bid)
		{
			if (!$auction->published) return; //not published yet

			$orderitems = RBidsHelperAuction::getOrderItemsForAuction($auction->id, self::getItemName());
			if (count($orderitems)) {
				foreach ($orderitems as $item)
					if ($item->status == 'C')
						return;
				//Auction was payed for!
			}
			$cfg = JTheFactoryHelper::getConfig();

			// Choose the user who pay commission for bid accepted
			$userId = $auction->userid;
			if (2 == $cfg->bid_accept_user_commision) {
				$userId = $auction->winner_id;
			}

			$model = self::getModel();
			$price = $model->getItemPrice($auction->cat);
			if (!floatval($price->price)) return; // Free publishing


			$modelbalance = JTheFactoryPricingHelper::getModel('balance');
			$balance = $modelbalance->getUserBalance($userId);

			$comission_amount = $price->price * $bid->bid_price / 100;
			$currency = $auction->currency;
			$funds_delta = 0;
			if (RBidsHelperPrices::comparePrices(array("price" => $comission_amount, "currency" => $currency),
				array("price" => $balance->balance, "currency" => $balance->currency)) > 0
			) {
				$funds_delta = RBidsHelperPrices::convertCurrency($balance->balance, $balance->currency, $currency);
				if ($funds_delta <= 0) $funds_delta = 0; //if he has some funds - get the rest
				$has_funds = false;
			} else
				$has_funds = true;

			$balance_minus = RBidsHelperPrices::convertCurrency($comission_amount, $currency, $balance->currency);
			$modelbalance->decreaseBalance($balance_minus, $userId);


			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			$items = array($model->getOderitem($auction, $bid));
			if ($funds_delta > 0) {
				$item = new stdClass();
				$item->itemname = JText::_("COM_RBIDS_EXISTING_FUNDS");
				$item->itemdetails = JText::_("COM_RBIDS_EXISTING_FUNDS");
				$item->iteminfo = 0;
				$item->price = -$funds_delta;
				$item->currency = $currency;
				$item->quantity = 1;
				$item->params = '';
				$items[] = $item;
			}
			$order = $modelorder->createNewOrder($items, $comission_amount - $funds_delta, $currency, $userId, $has_funds ? 'C' : 'P');
			if (!$has_funds) {
				$session =& JFactory::getSession();
				$session->set('checkout-order', $order->id, self::getContext());
			}

			$user =& JFactory::getUser($userId);
			$date = new JDate();

			$comission_table = self::getTable();
			$comission_table->userid = $user->id;
			$comission_table->auction_id = $auction->id;
			$comission_table->bid_id = $bid->id;
			$comission_table->comission_date = $date->toSQL();
			$comission_table->amount = $comission_amount;
			$comission_table->currency = $currency;
			$comission_table->store();

		}

		function onAfterExecuteTask($controller)
		{
			$session =& JFactory::getSession();
			$orderid = $session->get('checkout-order', 0, self::getContext());
			$session->set('checkout-order', null, self::getContext());
			$session->clear('checkout-order', self::getContext());
			if ($orderid) $controller->setRedirect(RBidsHelperRoute::getCheckoutRoute($orderid));

		}

		function onPaymentForOrder($paylog, $order)
		{
			if (!$order->status == 'C') return;
			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			$items = $modelorder->getOrderItems($order->id, self::getItemName());
			if (!is_array($items) || !count($items)) return; //no Listing items in order

			foreach ($items as $item) {
				if ($item->itemname != self::getItemName()) continue;

				$modelbalance = JTheFactoryPricingHelper::getModel('balance');

				$currency = JTheFactoryPricingHelper::getModel('currency');
				$default_currency = $currency->getDefault();
				$amount = RBidsHelperPrices::convertCurrency($item->price, $item->currency, $default_currency);
				$modelbalance->increaseBalance($amount, $order->userid);
			}

		}

		function onBeforeExecuteTask(&$stopexecution)
		{
			$task = JRequest::getCmd('task', 'listauctions');
			if ($task == 'paycomission') {
				$stopexecution = true; //task is fully processed here
				$app =& JFactory::getApplication();
				$user =& JFactory::getUser();
				$modelbalance = JTheFactoryPricingHelper::getModel('balance');
				$balance = $modelbalance->getUserBalance();

				if ($balance->balance >= 0) {
					JError::raiseNotice(501, JText::_("COM_RBIDS_YOU_HAVE_A_POSITIVE_BALANCE"));
					$app->redirect(RBidsHelperRoute::getAddFundsRoute());
					return;
				}

				$model = self::getModel();
				$modelorder = JTheFactoryPricingHelper::getModel('orders');
				$item = $model->getOderitemFromBalance($balance);
				$order = $modelorder->createNewOrder($item, $item->price, $item->currency, null, 'P');
				$app->redirect(RBidsHelperRoute::getCheckoutRoute($order->id));
				return;
			}

		}


	}

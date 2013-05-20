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
	 * @subpackage: Pay per bid
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventPrice_Bid extends JTheFactoryEvents
	{
		/**
		 * getItemName
		 *
		 * @return string
		 */
		function getItemName()
		{
			return "bid";
		}

		/**
		 * getContext
		 *
		 * @return string
		 */
		function getContext()
		{
			return APP_PREFIX . "." . self::getItemName();
		}

		/**
		 * getModel
		 *
		 * @return mixed
		 */
		function &getModel()
		{
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'pricing' . DS . self::getItemName() . DS . 'models');
			$model =& JModel::getInstance(self::getItemName(), 'JRBidPricingModel');
			return $model;
		}


		/**
		 * onBeforeDisplay
		 *
		 * @param $task
		 * @param $smarty
		 */
		function onBeforeDisplay($task, $smarty)
		{
			if (!is_object($smarty))
				return;
			if (!in_array($task, array('viewbids', 'details')))
				return;
			$user = JFactory::getUser();
			if ($user->guest) return;

			$id = JRequest::getInt("id");
			$auction =& $smarty->get_template_vars('auction');
			$curent_info = $smarty->get_template_vars('payment_items_header');
			if ($auction->isMyAuction() || $auction->close_offer) return; //TODO: Verify if auction Expired
			$model = self::getModel();
			$price = $model->getItemPrice($auction->cat);
			if (!floatval($price->price)) {
				return;
			} else {
				$priceinfo = JText::_("COM_RBIDS_PRICE_PER_BID") . " " . number_format($price->price, 2) . " " . $price->currency;
			}
			$smarty->assign('payment_items_header', $curent_info . $priceinfo);

		}

		/**
		 * onAfterSaveBid
		 *
		 * @param $auction
		 * @param $bid
		 */
		function onAfterSaveBid($auction, $bid)
		{
			if ($bid->cancel) return; //not published yet

			$orderitems = RBidsHelperAuction::getOrderItemsForAuction($bid->id, self::getItemName());
			if (count($orderitems)) {
				foreach ($orderitems as $item)
					if ($item->status == 'C')
						return;
				//Auction was payed for!
			}

			$model = self::getModel();
			$price = $model->getItemPrice($auction->cat);
			if (!floatval($price->price)) return; // Free publishing


			$modelbalance = JTheFactoryPricingHelper::getModel('balance');
			$balance = $modelbalance->getUserBalance();


			if (RBidsHelperPrices::comparePrices($price, array("price" => $balance->balance, "currency" => $balance->currency)) > 0) {
				//insufficient funds
				//hide bid
				$bid->cancel = 1;
				$bid->store();

				$modelorder = JTheFactoryPricingHelper::getModel('orders');
				$item = $model->getOderitem($bid);
				$order = $modelorder->createNewOrder($item, $price->price, $price->currency, null, 'P');
				$session =& JFactory::getSession();
				$session->set('checkout-order', $order->id, self::getContext());
				return;
			}
			//get funds from account, create confirmed order
			$balance_minus = RBidsHelperPrices::convertCurrency($price->price, $price->currency, $balance->currency);

			$modelbalance->decreaseBalance($balance_minus);

			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			$item = $model->getOderitem($bid);
			$order = $modelorder->createNewOrder($item, $price->price, $price->currency, null, 'C');

		}

		/**
		 * onAfterExecuteTask
		 *
		 * @param $controller
		 */
		function onAfterExecuteTask($controller)
		{
			$app = JFactory::getApplication();
			if ($app->isAdmin()) {
				return;
			}

			$session =& JFactory::getSession();
			$orderid = $session->get('checkout-order', 0, self::getContext());
			$session->set('checkout-order', null, self::getContext());
			$session->clear('checkout-order', self::getContext());
			if ($orderid) $controller->setRedirect(RBidsHelperRoute::getCheckoutRoute($orderid));

		}

		/**
		 * onPaymentForOrder
		 *
		 * @param $paylog
		 * @param $order
		 */
		function onPaymentForOrder($paylog, $order)
		{
			if (!$order->status == 'C') return;
			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			$items = $modelorder->getOrderItems($order->id, self::getItemName());
			if (!is_array($items) || !count($items)) return; //no Listing items in order

			$date = new JDate();
			$bid =& JTable::getInstance('rbids', 'Table');
			$auction =& JTable::getInstance('auctions', 'Table');
			foreach ($items as $item) {
				if (!$item->iteminfo) continue; //AuctionID is stored in iteminfo
				if ($item->itemname != self::getItemName()) continue;

				if (!$bid->load($item->iteminfo)) continue; //auction no longer exists
				$bid->modified = $date->toSQL();
				$bid->cancel = 0;
				$bid->store();
				$auction->load($bid->auction_id);
				JTheFactoryEventsHelper::triggerEvent('onAfterSaveBid', array($auction, $bid)); //for email notifications

			}

		}

		/**
		 * onDefaultCurrencyChange
		 */
		public function onDefaultCurrencyChange()
		{
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'pricing' . DS . self::getItemName() . DS . 'models');

			$modelBid = JModel::getInstance('Bid', 'J' . APP_PREFIX . 'PricingModel');
			$r = $modelBid->getItemPrices();

			$modelCurrency = JModel::getInstance('Currency', 'JTheFactoryModel');
			$default_currency = $modelCurrency->getDefault();

			/* *****************************************************
			 *                      $r return
			 * *****************************************************
			 * stdClass Object
			 * (
			 *	    [default_price] => 50.00
			 *	    [default_currency] => EUR
			 * 	    [price_powerseller] => 5
			 *	    [price_verified] => 1
			 * )
			 * *****************************************************/

			// Convert prices to new default currency
			$itemPrices = array(
				'default_price' => number_format(RBidsHelperPrices::convertToDefaultCurrency($r->default_price, $r->default_currency), 2),
				'currency' => $default_currency,
				'price_powerseller' => number_format(RBidsHelperPrices::convertToDefaultCurrency($r->price_powerseller, $r->default_currency), 2),
				'price_verified' => number_format(RBidsHelperPrices::convertToDefaultCurrency($r->price_verified, $r->default_currency), 2)
			);
			// Save converted prices
			$modelBid->saveItemPrices($itemPrices);
		}

	}


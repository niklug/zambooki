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
	 * @subpackage: Pay per listing
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventPrice_Listing extends JTheFactoryEvents
	{
		/**
		 * @return string
		 */
		function getItemName()
		{
			return "listing";
		}

		/**
		 * @return string
		 */
		function getContext()
		{
			return APP_PREFIX . "." . self::getItemName();
		}

		/**
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
		 * @param $task
		 * @param $smarty
		 */
		function onBeforeDisplay($task, $smarty)
		{
			if (!is_object($smarty))
				return;
			if (!in_array($task, array('republish', 'new', 'form', 'edit', 'editauction', 'newauction')))
				return;

			$id = JRequest::getInt("id");
			$curent_info = $smarty->get_template_vars('payment_items_header');

			if (in_array($task, array('form', 'edit', 'editauction')) && $id) {
				$orderitems = RBidsHelperAuction::getOrderItemsForAuction($id, self::getItemName());
				if (count($orderitems)) {
					$priceinfo = JText::_("COM_RBIDS_PAYMENT_FOR_THIS_AUCTION_IS_PENDING");
					foreach ($orderitems as $item)
						if ($item->status == 'C')
							return;
					//Auction was payed for!
					$smarty->assign('payment_items_header', $curent_info . $priceinfo);
					return;
				}
			}
			$auction =& $smarty->get_template_vars('auction');

			$model = self::getModel();
			$price = $model->getItemPrice($auction->cat);

			$modelbalance = JTheFactoryPricingHelper::getModel('balance');
			$balance = $modelbalance->getUserBalance();
			if (!floatval($price->price)) {
				$priceinfo = JText::_("COM_RBIDS_PUBLISHING_IN_THIS_CATEGORY_IS_FREE");
			} else {
				$priceinfo = JText::_("COM_RBIDS_YOUR_CURRENT_BALANCE_IS") . number_format($balance->balance, 2) . " " . $balance->currency . "<br />";
				$priceinfo .= JText::_("COM_RBIDS_PUBLISHING_IN_THIS_CATEGORY_WILL_COST") . ' ' . number_format($price->price, 2) . ' ' . $price->currency;
				if ($balance->currency && $balance->currency <> $price->currency) $priceinfo .= " (" .
					number_format(RBidsHelperPrices::convertCurrency($price->price, $price->currency, $balance->currency), 2) . " " . $balance->currency . ")";
				$priceinfo .= "<br />";
				if (RBidsHelperPrices::comparePrices($price, array("price" => $balance->balance, "currency" => $balance->currency)) > 0)
					$priceinfo .= JText::_("COM_RBIDS_YOUR_FUNDS_ARE_UNSUFFICIENT") . "<br />";
			}
			$smarty->assign('payment_items_header', $curent_info . $priceinfo);

		}

		/**
		 * @param $auction
		 */
		function onAfterSaveAuctionSuccess($auction)
		{
			if (!$auction->published) return; //not published yet

			$orderitems = RBidsHelperAuction::getOrderItemsForAuction($auction->id, self::getItemName());
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
				$auction->published = 0;
				$auction->store();

				$modelorder = JTheFactoryPricingHelper::getModel('orders');
				$item = $model->getOderitem($auction);
				$order = $modelorder->createNewOrder($item, $price->price, $price->currency, null, 'P');
				$session =& JFactory::getSession();
				$session->set('checkout-order', $order->id, self::getContext());
				return;
			}
			//get funds from account, create confirmed order
			$balance_minus = RBidsHelperPrices::convertCurrency($price->price, $price->currency, $balance->currency);

			$modelbalance->decreaseBalance($balance_minus);

			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			$item = $model->getOderitem($auction);
			$order = $modelorder->createNewOrder($item, $price->price, $price->currency, null, 'C');

		}

		/**
		 * @param $controller
		 */
		function onAfterExecuteTask($controller)
		{
			$session =& JFactory::getSession();
			$orderid = $session->get('checkout-order', 0, self::getContext());
			$session->set('checkout-order', null, self::getContext());
			$session->clear('checkout-order', self::getContext());
			if ($orderid) $controller->setRedirect(RBidsHelperRoute::getCheckoutRoute($orderid));

		}

		/**
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
			$auction =& JTable::getInstance('auctions', 'Table');
			foreach ($items as $item) {
				if (!$item->iteminfo) continue; //AuctionID is stored in iteminfo
				if ($item->itemname != self::getItemName()) continue;

				if (!$auction->load($item->iteminfo)) continue; //auction no longer exists
				$auction->modified = $date->toSQL();
				$auction->published = 1;
				$auction->store();
				JTheFactoryEventsHelper::triggerEvent('onAfterSaveAuctionSuccess', array($auction)); //for email notifications
			}
		}

		/**
		 * onDefaultCurrencyChange
		 */
		public function onDefaultCurrencyChange()
		{
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'pricing' . DS . self::getItemName() . DS . 'models');

			$modelListing = JModel::getInstance('Listing', 'J' . APP_PREFIX . 'PricingModel');
			$r = $modelListing->getItemPrices();

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
			 *          [category_pricing_enabled] => 1
			 *	    [category_pricing] => Array
			 *		        (
			 *		            [1] => Array
			 *		                (
			 *		                    [category] => 1
			 *		                    [price] => 5
			 *		                )
			 *
			 *		            [2] => Array
			 *		                (
			 *		                    [category] => 2
			 *		                    [price] => 3
			 *		                )
			 *
			 *		        )
			 *  )
			 * *****************************************************/

			// Convert prices to new default currency
			$itemPrices = array(
				'default_price' => number_format(RBidsHelperPrices::convertToDefaultCurrency($r->default_price, $r->default_currency), 2),
				'currency' => $default_currency,
				'price_powerseller' => isset($r->price_powerseller) ? number_format(RBidsHelperPrices::convertToDefaultCurrency($r->price_powerseller, $r->default_currency), 2) : null,
				'price_verified' => isset($r->price_verified) ? number_format(RBidsHelperPrices::convertToDefaultCurrency($r->price_verified, $r->default_currency), 2) : null,
				'category_pricing_enabled' => $r->category_pricing_enabled,
				'category_pricing' => array()
			);

			if (isset($r->category_pricing) && count($r->category_pricing)) {
				foreach ($r->category_pricing as $arrCat) {
					$itemPrices['category_pricing'][$arrCat['category']] = isset($arrCat['price']) ? number_format(
						RBidsHelperPrices::convertToDefaultCurrency($arrCat['price'], $r->default_currency),
						2) : null;
				}
			}

			// Save converted prices
			$modelListing->saveItemPrices($itemPrices);
		}

	} // End Class

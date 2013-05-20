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
	 * @subpackage: Pay per contact
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventPrice_Contact extends JTheFactoryEvents
	{
		function getItemName()
		{
			return "contact";
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


		function onBeforeDisplay($task, $smarty)
		{
			if (!is_object($smarty))
				return;

			$task = strtolower($task);

			if (!in_array($task, array('userprofile', 'showusers')))
				return;

			$Itemid = JRequest::getInt('Itemid');
			$user =& JFactory::getUser();
			$model = self::getModel();
			$price = $model->getItemPrice();

			$url_buy = 'index.php?option=' . APP_EXTENSION . '&task=buy_contact&id=%s&Itemid=' . $Itemid;
			if (isset($smarty->_tpl_vars["user"]) && is_object($smarty->_tpl_vars["user"])) {
				$userid = isset($smarty->_tpl_vars["user"]->userid) ? $smarty->_tpl_vars["user"]->userid : $smarty->_tpl_vars["user"]->id;
				if ($user->id !== $userid && !$model->checkContact($userid)) {
					$url = sprintf($url_buy, $userid);
					$smarty->_tpl_vars["user"]->name = "hidden&nbsp;<a href='$url'>" . JText::_("COM_RBIDS_BUY_THIS_CONTACT_FOR") . " " . number_format($price->price, 2) . " " . $price->currency . "</a>";
					$smarty->_tpl_vars["user"]->surname = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->phone = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->address = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->paypalemail = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->YM = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->Hotmail = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->Skype = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->Facebook = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->Linkedin = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->activity_domains = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					$smarty->_tpl_vars["user"]->about_me = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
				}
			}

			if (isset($smarty->_tpl_vars["users"]) && is_array($smarty->_tpl_vars["users"])) {
				for ($i = 0; $i < count($smarty->_tpl_vars["users"]); $i++) {
					$userid = isset($smarty->_tpl_vars["users"][$i]->userid) ? $smarty->_tpl_vars["users"][$i]->userid : $smarty->_tpl_vars["users"][$i]->id;
					if ($user->id !== $userid && !$model->checkContact($userid)) {
						$url = sprintf($url_buy, $userid);
						$smarty->_tpl_vars["users"][$i]->name = "hidden&nbsp;<a href='$url'>" . JText::_("COM_RBIDS_BUY_THIS_CONTACT_FOR") . " " . number_format($price->price, 2) . " " . $price->currency . "</a>";
						$smarty->_tpl_vars["users"][$i]->surname = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["users"][$i]->phone = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["users"][$i]->address = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["users"][$i]->paypalemail = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["users"][$i]->YM = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["users"][$i]->Hotmail = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["users"][$i]->Skype = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["user"][$i]->Facebook = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["user"][$i]->Linkedin = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["user"][$i]->activity_domains = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
						$smarty->_tpl_vars["user"][$i]->about_me = "<span class='rbid_hidden'>" . JText::_("COM_RBIDS_HIDDEN") . "</span>";
					}
				}
			}
		}

		function onBeforeExecuteTask(&$stopexecution)
		{

			$task = JRequest::getCmd('task', 'listauctions');
			if ($task == 'buy_contact') {
				$user =& JFactory::getUser();
				$app =& JFactory::getApplication();
				$id = JRequest::getInt("id");
				$model = self::getModel();

				if ($user->id == $id && $model->checkContact($id)) {
					JError::raiseWarning(501, JText::_("COM_RBIDS_CONTACT_IS_ALREADY_PURCHASED"));
					$app->redirect(RBidsHelperRoute::getUserdetailsRoute($id, false));
					return;
				}
				$modelorder = JTheFactoryPricingHelper::getModel('orders');
				$modelbalance = JTheFactoryPricingHelper::getModel('balance');

				$price = $model->getItemPrice();
				$balance = $modelbalance->getUserBalance();
				$item = $model->getOderitem($id);

				if (RBidsHelperPrices::comparePrices($price, array("price" => $balance->balance, "currency" => $balance->currency)) > 0) {
					$order = $modelorder->createNewOrder($item, $price->price, $price->currency, null, 'P');
					$app->redirect(RBidsHelperRoute::getCheckoutRoute($order->id));
					return;
				}
				//get funds from account, create confirmed order
				$balance_minus = RBidsHelperPrices::convertCurrency($price->price, $price->currency, $balance->currency);
				$modelbalance->decreaseBalance($balance_minus);

				$order = $modelorder->createNewOrder($item, $price->price, $price->currency, null, 'C');
				$model->addContact($id, $order->userid);
				$app->redirect(RBidsHelperRoute::getUserdetailsRoute($id));
				return;

			}
		}

		function onPaymentForOrder($paylog, $order)
		{
			if ($order->status != 'C') return;
			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			$items = $modelorder->getOrderItems($order->id, self::getItemName());
			if (!is_array($items) || !count($items)) return; //no Listing items in order

			$model = self::getModel();
			$date = new JDate();
			foreach ($items as $item) {
				if (!$item->iteminfo) continue; //AuctionID is stored in iteminfo
				if ($item->itemname != self::getItemName()) continue;

				$model->addContact($item->iteminfo, $order->userid);
			}

		}

		/**
		 * onDefaultCurrencyChange
		 */
		public function onDefaultCurrencyChange()
		{
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'pricing' . DS . self::getItemName() . DS . 'models');

			$modelListing = JModel::getInstance('Contact', 'J' . APP_PREFIX . 'PricingModel');
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
			 *  )
			 * *****************************************************/

			// Convert prices to new default currency
			$itemPrices = array(
				'default_price' => number_format(RBidsHelperPrices::convertToDefaultCurrency($r->default_price, $r->default_currency), 2),
				'currency' => $default_currency,
				'price_powerseller' => isset($r->price_powerseller) ? number_format(RBidsHelperPrices::convertToDefaultCurrency($r->price_powerseller, $r->default_currency), 2) : null,
				'price_verified' => isset($r->price_verified) ? number_format(RBidsHelperPrices::convertToDefaultCurrency($r->price_verified, $r->default_currency), 2) : null
			);

			// Save converted prices
			$modelListing->saveItemPrices($itemPrices);
		}
	}

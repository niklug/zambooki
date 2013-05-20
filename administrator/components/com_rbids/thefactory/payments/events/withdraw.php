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

	class JTheFactoryEventWithdraw extends JTheFactoryEvents
	{
		function getItemName()
		{
			return "withdraw";
		}

		function getContext()
		{
			return APP_PREFIX . "." . self::getItemName();
		}

		function onPaymentForOrder($paylog, $order)
		{
			if (!$order->status == 'C') return;
			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			// Payment item must be withdraw
			$items = $modelorder->getOrderItems($order->id, self::getItemName());
			if (!is_array($items) || !count($items)) return; //no Listing items in order

			foreach ($items as $item) {
				if ($item->itemname != self::getItemName()) continue;

				$modelbalance = JTheFactoryPricingHelper::getModel('balance');
				// Work with amount paid not with amount requested
				// Prevent to work with price in another currency then order currency
				$price = RBidsHelperPrices::convertCurrency($paylog->amount, $paylog->currency, $order->order_currency);
				$modelbalance->lastStepWithdraw($price, $order->userid);
			}

		}

	} // End Class

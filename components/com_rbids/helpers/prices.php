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

	class RBidsHelperPrices
	{
		/**
		 * comparePrices
		 *
		 * @static
		 *
		 * @param $price1
		 * @param $price2
		 *
		 * @return int
		 */
		static function comparePrices($price1, $price2)
		{
			if (is_array($price1)) {
				$amount1 = $price1['price'];
				$currency1 = $price1['currency'];
			} else {
				$amount1 = $price1->price;
				$currency1 = $price1->currency;
			}
			if (is_array($price2)) {
				$amount2 = $price2['price'];
				$currency2 = $price2['currency'];
			} else {
				$amount2 = $price2->price;
				$currency2 = $price2->currency;
			}

			$p1 = self::convertToDefaultCurrency($amount1, $currency1);
			$p2 = self::convertToDefaultCurrency($amount2, $currency2);
			if ($p1 == $p2)
				return 0;
			elseif ($p1 > $p2)
				return 1;
			else
				return -1;

		}

		/**
		 * convertToDefaultCurrency
		 *
		 * @static
		 *
		 * @param $amount
		 * @param $currency
		 *
		 * @return mixed
		 */
		static function convertToDefaultCurrency($amount, $currency)
		{
			if (!$currency) return $amount;
			$db =& JFactory::getDbo();
			$db->setQuery("SELECT * FROM `#__rbid_currency` WHERE `name` = '$currency'");
			$p = $db->loadObject();
			return $amount * $p->convert;
		}

		/**
		 * convertCurrency
		 *
		 * @static
		 *
		 * @param $amount
		 * @param $currency
		 * @param $tocurrency
		 *
		 * @return mixed
		 */
		static function convertCurrency($amount, $currency, $tocurrency)
		{
			if (!$tocurrency || !$currency) return $amount;

			$db =& JFactory::getDbo();
			$db->setQuery("select * from #__rbid_currency where name='$currency'");
			$c1 = $db->loadObject();
			$db->setQuery("select * from #__rbid_currency where name='$tocurrency'");
			$c2 = $db->loadObject();
			if ($c2->convert)
				$convert = $c1->convert / $c2->convert;
			else
				$convert = 0;

			return $amount * $convert;
		}
	}

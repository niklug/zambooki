<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 05/09/2012
	 * @package   : RBids
	 * @subpackage: Events
	-------------------------------------------------------------------------*/
	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');

	/**
	 * This class is duplicated for front events
	 */
	class JTheFactoryEventCurrency extends JTheFactoryEvents
	{
		public function onConversionRateChange()
		{
			$db = JFactory::getDbo();

			/**********************************************************************************************
			 *  Update max_price_default_currency  to new currency
			 *
			 **********************************************************************************************/

			// Get all max_price fields that are greater than 0
			$query = "SELECT `a`.`id`, `a`.`max_price`, `a`.`currency`
					 FROM `#__rbid_auctions` AS `a`
					 WHERE `a`.`max_price` > 0
					 ";
			$db->setQuery($query);
			$getAllMaxPrice = $db->loadObjectList('id');

			// Update 'max_price_default_currency' field in auctions table
			foreach ($getAllMaxPrice as $price) {
				$maxPriceToDefaultCurrency = RBidsHelperPrices::convertToDefaultCurrency($price->max_price, $price->currency);
				$query = "UPDATE `#__rbid_auctions` AS `a`
					          SET `a`.`max_price_default_currency` = '{$maxPriceToDefaultCurrency}'
					          WHERE `a`.`id` = '{$price->id}'";
				$db->setQuery($query);
				$db->query();
			}

		}
	} // End Class

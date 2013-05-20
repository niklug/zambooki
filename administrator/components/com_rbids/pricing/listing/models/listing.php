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

	jimport('joomla.application.component.model');
	jimport('joomla.html.parameter');

	class JRBidPricingModelListing extends JModel
	{
		var $name = "listing";
		var $description = "Pay per listing";
		var $context = 'listing';

		/**
		 * loadPricingObject
		 *
		 * @return mixed
		 */
		function loadPricingObject()
		{
			$db =& $this->getDbo();
			$db->setQuery("select * from `#__" . APP_PREFIX . "_pricing` where `itemname`='" . $this->name . "'");
			return $db->loadObject();
		}

		/**
		 * getItemPrices
		 *
		 * @return stdClass
		 */
		function getItemPrices()
		{
			$db =& $this->getDbo();
			$r = $this->loadPricingObject();
			$params = new JParameter($r->params);

			$res = new stdClass();
			$res->default_price = $r->price;
			$res->default_currency = $r->currency;
			$res->price_powerseller = $params->get('price_powerseller');
			$res->price_verified = $params->get('price_verified');
			$res->category_pricing_enabled = $params->get('category_pricing_enabled');

			$db->setQuery("SELECT `category`, `price` FROM `#__" . APP_PREFIX . "_pricing_categories` WHERE `itemname` = '" . $this->name . "'");
			$res->category_pricing = $db->loadAssocList('category');


			return $res;
		}

		/**
		 * getItemPrice
		 *
		 * @param $category
		 *
		 * @return stdClass
		 */
		function getItemPrice($category)
		{
			$userprofile = RBidsHelperTools::getUserProfileObject();
			$userprofile->getUserProfile();


			$r = $this->loadPricingObject();
			$params = new JParameter($r->params);

			if ($userprofile->powerseller)
				$defaultprice = $params->get('price_powerseller', $r->price);
			elseif ($userprofile->verified)
				$defaultprice = $params->get('price_verified', $r->price); else
				$defaultprice = $r->price;

			$db =& $this->getDbo();
			$db->setQuery("select price from `#__" . APP_PREFIX . "_pricing_categories` where `itemname`='" . $this->name . "' and category='$category'");
			$price = $db->loadResult();
			$res = new stdClass();
			$res->price = ($price === NULL) ? $defaultprice : $price;
			$res->currency = $r->currency;
			return $res;
		}

		/**
		 * saveItemPrices
		 *
		 * @param $d
		 */
		function saveItemPrices($d)
		{
			$params = new JParameter();
			$params->set('price_powerseller', JArrayHelper::getValue($d, 'price_powerseller'));
			$params->set('price_verified', JArrayHelper::getValue($d, 'price_verified'));
			$params->set('category_pricing_enabled', JArrayHelper::getValue($d, 'category_pricing_enabled'));
			$p = $params->toString('INI');
			$price = JArrayHelper::getValue($d, 'default_price');
			$currency = JArrayHelper::getValue($d, 'currency');

			$db =& $this->getDbo();
			$db->setQuery("UPDATE `#__" . APP_PREFIX . "_pricing`
						  SET `price`='$price',
						        `currency`='$currency',
						        `params`='$p'
						   WHERE `itemname`='listing'
					");
			$db->query();

			$db->setQuery("DELETE FROM `#__" . APP_PREFIX . "_pricing_categories` WHERE `itemname`='" . $this->name . "'");
			$db->query();

			$category_pricing = JArrayHelper::getValue($d, 'category_pricing', array(), 'array');

			foreach ($category_pricing as $k => $v)
				if (!empty($v) || ($v === '0')) {
					$db->setQuery("INSERT INTO `#__" . APP_PREFIX . "_pricing_categories` (`category`, `price`, `itemname`) VALUES ('$k', '$v', '" . $this->name . "')");
					$db->query();

				}

		}

		/**
		 * @param $auction
		 *
		 * @return stdClass
		 */
		function getOderitem($auction)
		{
			$price = $this->getItemPrice($auction->cat);
			$item = new stdClass();
			$item->itemname = $this->name;
			$item->itemdetails = JText::_($this->description);
			$item->iteminfo = $auction->id;
			$item->price = $price->price;
			$item->currency = $price->currency;
			$item->quantity = 1;
			$item->params = '';
			return $item;
		}
	}

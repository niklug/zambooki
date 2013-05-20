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

	jimport('joomla.application.component.model');
	jimport('joomla.application.component.helper');

	/**
	 * @package                RBids
	 */
	class rbidsModelRCategory extends JModel
	{
		var $_name = 'category';
		var $name = 'category';

		/**
		 * getRbidsCategoryTree
		 *
		 * @param int    $root_category
		 * @param null   $max_depth
		 * @param null   $watchlist_user
		 *
		 * @param string $filter_letter
		 *
		 * @return mixed
		 */
		public function getRbidsCategoryTree($root_category = 0, $max_depth = null, $watchlist_user = null, $filter_letter = 'all')
		{
			$db =& $this->getDbo();
			$config = & JFactory::getConfig();
			$datenow = JFactory::getDate('now', $config->getValue('config.offset'));
			$watchlist_fields = "";
			$watchlist_join = "";

			if ($watchlist_user) {
				$watchlist_fields = ", COUNT(`w`.`id`) AS `watchListed_flag` ";
				$watchlist_join = " LEFT JOIN `#__rbid_watchlist_cats` AS `w` ON `w`.`catid` = `c`.`id`
														    AND `w`.`userid` = '{$watchlist_user}' ";
			}
			if ('all' != $filter_letter) {
				$filter_letter = " AND `c`.`catname` LIKE '" . trim($filter_letter) . "%' ";
			} else {
				$filter_letter = ' ';
			}
			$query = "SELECT `c`.*,
						 COUNT(DISTINCT `a`.`id`) AS `nr_a`,
						 COUNT(`c2`.`id`) AS `nr_subcats`,
						 NULL AS `subcategories`
						 {$watchlist_fields}
					 FROM `#__rbid_categories` AS `c`
					 LEFT JOIN `#__rbid_categories` AS `c2` ON `c`.`id` = `c2`.`parent`
					 LEFT JOIN `#__rbid_auctions` AS `a` ON `c`.`id` = `a`.`cat`
					                                                        AND `a`.`close_by_admin` <> 1
					                                                        AND `a`.`close_offer` = 0
					                                                        AND `a`.`published` = 1
					                                                        AND `a`.`start_date` <= '" . $datenow->toSQL(false) . "'
                                        {$watchlist_join}
                                        WHERE `c`.`status`= 1
                                        AND `c`.`parent` = '{$root_category}'
                                        {$filter_letter}
                                        GROUP BY `c`.`id`
                                        ORDER BY `c`.`ordering`
                                ";
			$db->setQuery($query);
			$items = $db->loadObjectList();

			if ($max_depth === NULL || $max_depth)
				for ($i = 0; $i < count($items); $i++)
					if ($items[$i]->nr_subcats)
						$items[$i]->subcategories = $this->getRbidsCategoryTree($items[$i]->id, ($max_depth === NULL) ? $max_depth : ($max_depth - 1), $watchlist_user);

			return $items;
		}


		/**
		 * CATEGORIES WATCHLIST (favoriteS)
		 *
		 * @param $id
		 *
		 * @return mixed
		 */
		public function addWatch($id)
		{
			$database = &JFactory::getDBO();
			$user = &JFactory::getUser();
			if ($user->id && $id != "") {
				$database->setQuery("INSERT INTO `#__rbid_watchlist_cats` SET `userid` = '" . $user->id .
					"', `catid` = '" . (int)$id . "'");
				$database->query();
			}
			return $database->affected_rows;
		}

		/**
		 * delWatch
		 *
		 * @param $id
		 *
		 * @return mixed
		 */
		public function delWatch($id)
		{
			$database = &JFactory::getDBO();
			$user = &JFactory::getUser();
			if ($user->id && $id != "") {
				$database->setQuery("DELETE FROM `#__rbid_watchlist_cats` WHERE `userid` = '" . $user->id .
					"' AND `catid` = '" . (int)$id . "'");
				$database->query();
			}
			return $database->affected_rows;
		}

	} // End Class

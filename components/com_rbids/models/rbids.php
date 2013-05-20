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

	class rbidsModelRbids extends rbidsModelGeneric
	{
		var $_name = 'Rbids';
		var $name = 'Rbids';
		var $page = 'auctions';
		var $context = 'rbidsModel';
		var $order_fields = array(
			'start_date' => "a.start_date",
			'title' => "a.title",
			'username' => "u.username",
			'end_date' => "a.end_date",
			'max_price' => "a.max_price",
			'hits' => "a.hits",
			'catname' => "cat.catname",
			'id' => "a.id",
			'nr_bidders' => "nr_bidders",
			'nr_bids' => "nr_bids"
		);
		var $knownFilters = array(
			'keyword' => array('type' => 'string'),
			'in_description' => array('type' => 'int'),
			'filter_archive' => array('type' => 'string'),
			'in_tags' => array('type' => 'int'),
			'filter_myauctions' => array('type' => 'string'),
			'userid' => array('type' => 'int'),
			'username' => array('type' => 'string'),
			'cat' => array('type' => 'int'),
			'tag' => array('type' => 'string'),
			'city' => array('type' => 'string'),
			'country' => array('type' => 'string'),
			'afterd' => array('type' => 'string'),
			'befored' => array('type' => 'string'),
			'startprice' => array('type' => 'float'),
			'endprice' => array('type' => 'float'),
			'currency' => array('type' => 'string'),

			'filter_order' => array('type' => 'string', 'default' => 'start_date'),
			'filter_order_Dir' => array('type' => 'string', 'default' => 'DESC')
		);

		function buildQuery()
		{
			$user = & JFactory::getUser();
			$db = & JFactory::getDBO();
			$task = JRequest::getCmd('task');
			$cfg =& JTheFactoryHelper::getConfig();
			$config = & JFactory::getConfig();
			$datenow = JFactory::getDate('now', $config->getValue('config.offset'));

			$query = JTheFactoryDatabase::getQuery();

			$query->from('#__rbid_auctions', 'a');

			/**
			 *
			 *  Fields to select
			 *
			 * */
			$query->select('`a`.*');
			$query->select('`a`.`id` AS `auctionId`');
			$query->select('`cat`.`id` AS `cati`, `cat`.`catname`');
			$query->select('`u`.`username`');
			$query->select('GROUP_CONCAT(DISTINCT `t`.`tagname`) AS `tags`');
			$query->select('IF (COUNT(`pics`.`id`) > 0, 1, 0 ) AS `more_pictures`');
			$query->select('IF (`a`.`show_bidder_nr` = 1, COUNT(DISTINCT `rbids`.`userid`), 0  ) AS `nr_bidders`');
			$query->select('IF (`a`.`show_bidder_nr` = 1, COUNT(DISTINCT `rbids`.`id`), 0 ) AS `nr_bids`');
			$query->select('IF (`a`.`show_best_bid` = 1, MIN(`rbids`.`bid_price`), 0)  AS `lowest_price`');
			if ($user->id) {
				$query->select("IF (`a`.`userid` = '" . (int)$user->id . "', 1, 0 ) AS `is_my_auction`");
				$query->select("`fav_table`.`id` AS `favorite`");
			} else {
				$query->select("0  AS `is_my_auction`");
				$query->select("NULL AS favorite");
			}
			$query->select('AVG(`ru`.`rating`) AS `rating_overall`');
			$query->select("IF(`ru`.`rate_type`='auctioneer', AVG(`ru`.`rating`), 0) AS `rating_auctioneer`");
			$query->select("IF(`ru`.`rate_type` = 'bidder', AVG(`ru`.`rating`), 0) AS `rating_bidder`");

			/**
			 *
			 *  Where conditions
			 *
			 * */
			$query->where("`a`.`close_by_admin` <> 1");

			switch ($task) {
				//live auctions
				default:
				case 'listauctions':
					$query->where(array("`cat`.`status`=1", "`cat`.`status` IS NULL"), "OR");
					if ($this->getState('filters.filter_archive'))
						$query->where(array("`a`.`close_offer` = 1", "`a`.`published` = 1"), "AND");
					else
						//Archived Auctions
						$query->where(array("`a`.`close_offer` = 0", "`a`.`published` = 1"), "AND");
					if ($cfg->admin_approval) {
						$query->where("`a`.`approved` = 1");
					}

					$query->where("`a`.`start_date` <= '" . $datenow->toSQL(false) . "'");
					break;
				//Unpublished Auctions
				case 'myauctions':
					switch ($this->getState('filters.filter_myauctions')) {
						default:
							$query->where("`a`.`userid` ='" . $user->id . "'");
							$query->where(array("`a`.`close_offer` = 0", "`a`.`published` = 1"), "AND");
							break;
						case 'unpublished':
							$query->where("`a`.`userid` ='" . $user->id . "'");
							if ($cfg->admin_approval) {
								$query->where(array("`a`.`close_offer` = 0", "(`a`.`published` = 0 OR `a`.`approved` = 1)"), "AND");
							} else
								$query->where(array("`a`.`close_offer` = 0", "`a`.`published` = 0"), "AND");
							break;
						case 'archive':
							$query->where("`a`.`userid` ='" . $user->id . "'");
							$query->where("`a`.`close_offer` = 1");
							break;
						case 'accepted':
							$query->where("`a`.`userid` ='" . $user->id . "'");
							$query->where("`a`.`winner_id` != 0");
							break;
						case 'watchlist':
							$query->where(array("`cat`.`status`=1", "`cat`.`status` IS NULL"), "OR");
							$query->where("`fav_table`.userid ='" . $user->id . "'");
							break;
						case 'wonbids':
							$query->where(array("`cat`.`status`=1", "`cat`.`status` IS NULL"), "OR");
							$query->where("`a`.`winner_id` =" . $user->id . "");
							break;
					}
					break;
				case 'watchlist':
					$query->where(array("`cat`.`status` = 1", "`cat`.`status` IS NULL"), "OR");

//					$query->where("`fav_table`.`userid` = '" . $user->id . "'");
					$query->where(array("`fav_table`.`userid` = '" . $user->id . "'", "`fav_cats_table`.`catid` = `a`.`cat`"), 'OR');
//					$query->where("`fav_cats_table`.`catid` = `a`.`cat`");

					$query->where("`a`.`start_date` <= '" . $datenow->toSQL(false) . "'");
					break;
				case 'mywonbids':
					$query->where(array("`cat`.`status`=1", "`cat`.`status` IS NULL"), "OR");
					$query->where("`a`.`winner_id` =" . $user->id . "");
					break;
				case 'mybids':
					$query->where("`rbids`.`userid` = '" . $user->id . "'");
					break;
			}

			if ($this->getState('filters.keyword')) {

				$keyword = $db->escape($this->getState('filters.keyword'));

				$w = array();

				// Checkboxes from search
				$w[] = "`a`.`title` LIKE '%" . $keyword . "%'";
				if ($this->getState('filters.in_description')) {
					$w[] = '`a`.`shortdescription` LIKE \'%' . $db->escape($keyword) . '%\'';
					$w[] = '`a`.`description` LIKE \'%' . $db->escape($keyword) . '%\'';
				}
				if ($this->getState('filters.in_tags')) {
					$w[] = '`t`.`tagname` LIKE \'%' . $db->escape($keyword) . '%\'';
				}

				$query->where($w, 'OR');
			}

			if ($this->getState('filters.userid')) {
				$query->where(" a.userid = '" . $db->escape($this->getState('filters.userid')) . "' ");
			}

			if ($this->getState('filters.tag')) {
				$query->where(" t.tagname LIKE '%" . $db->escape($this->getState('filters.tag')) . "%' ");
			}
			if ($this->getState('filters.username')) {
				$query->where("u.username LIKE '%" . $db->escape($this->getState('filters.username')) . "%' ");
			}

			if ($this->getState('filters.cat')) {
				if (!$cfg->inner_categories) {
					$query->where(" a.cat= '" . $db->escape($this->getState('filters.cat')) . "' ");
				} else {
					JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'thefactory' . DS . 'category' . DS . 'models');
					$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
					$cat_ids = $catModel->getCategoryChildren((int)$this->getState('filters.cat'));
					$cat_ids [] = (int)$this->getState('filters.cat');

					if (count($cat_ids)) {
						$cat_ids = implode(",", $cat_ids);
						$query->where(' a.cat  IN (' . $cat_ids . ') ');
					}
				}
			}
			// Filter by  start date filter
			if ($this->getState('filters.afterd')) {
				$start_date = RBidsHelperDateTime::DateToIso($this->getState('filters.afterd'));
				$start_date = JFactory::getDate($start_date, $config->getValue('config.offset'))->toSQL(false);
				$query->where(' a.start_date>=\'' . $start_date . '\'');
			}
			// Filter by end date filter
			if ($this->getState('filters.befored')) {
				$before_date = RBidsHelperDateTime::DateToIso($this->getState('filters.befored'));
				$before_date = JFactory::getDate($before_date, $config->getValue('config.offset'))->toSQL(false);
				$query->where('a.end_date>=\'' . $before_date . '\'');
			}
			// Filter by max price greater than start price filter
			if ($this->getState('filters.startprice') && !$this->getState('filters.endprice')) {

				$startprice = RBidsHelperPrices::convertToDefaultCurrency($this->getState('filters.startprice'), $this->getState('filters.currency'));
				$query->where("a.max_price_default_currency >= '" . $startprice . "'");
			} // Filter by max price lower than end price filter
			elseif ($this->getState('filters.endprice') && !$this->getState('filters.startprice')) {
				$endprice = RBidsHelperPrices::convertToDefaultCurrency($this->getState('filters.endprice'), $this->getState('filters.currency'));
				$query->where("a.max_price_default_currency <= '" . $endprice . "'");
				// Filter by max price to be between start price and end price
			} elseif ($this->getState('filters.startprice') && $this->getState('filters.endprice')) {
				$startprice = RBidsHelperPrices::convertToDefaultCurrency($this->getState('filters.startprice'), $this->getState('filters.currency'));
				$endprice = RBidsHelperPrices::convertToDefaultCurrency($this->getState('filters.endprice'), $this->getState('filters.currency'));
				$condFilterMaxPrice = array();
				$condFilterMaxPrice[] = "a.max_price_default_currency >= '" . $startprice . "'";
				$condFilterMaxPrice[] = "a.max_price_default_currency <= '" . $endprice . "'";
				$query->where($condFilterMaxPrice, 'AND');
			}


			/**
			 *  Joins
			 * */
			$query->join('left', '#__rbids', 'rbids', '`rbids`.`auction_id`=`a`.`id` and `rbids`.`cancel`=0 ' . (($task == 'mybids' || $task == 'mywonbids') ? "AND `rbids`.`userid` = '{$user->id}'" : ""));
			$query->join('left', '#__rbid_rate', 'ru', 'ru.user_rated=`a`.`userid`');
			$query->join('left', '#__rbid_categories', 'cat', '`a`.`cat`=`cat`.`id`');
			$query->join('left', '#__rbid_tags', 't', '`a`.`id`=`t`.`auction_id`');
			$query->join('left', '#__rbid_pictures', 'pics', '`a`.`id`=`pics`.`auction_id`');
			if ($user->id) {
				$query->join('left', '`#__rbid_watchlist`', 'fav_table', "`fav_table`.`auction_id`=`a`.`id` AND `fav_table`.`userid` = '{$user->id}'");
				$query->join('left', '`#__rbid_watchlist_cats`', 'fav_cats_table', "`fav_cats_table`.`userid` = '{$user->id}'");
			}
			// Featurings first
			$query->order('`a`.featured=\'featured\' DESC');

			// Required ordering filter
			$filter_order = $this->getState('filters.filter_order');
			if ($filter_order) {
				$filter_order_Dir = $this->getState('filters.filter_order_Dir');
				$query->order($db->escape($this->order_fields["$filter_order"] . ' ' . $filter_order_Dir));
			}

			$query->group('`a`.`id`');


			$profile = RBidsHelperTools::getUserProfileObject();
			//this binds to the query object everything that is related to custom fields
			parent::buildCustomQuery($query, $profile, '`a`.`userid`');

			$queriedTables = $query->getQueriedTables();
			if ($this->getState('filters.country')) {
				$table = $profile->getFilterTable('country');
				$field = $profile->getFilterField('country');
				;
				$alias = array_search($table, $queriedTables);
				$query->where(' (`' . $alias . '`.`' . $field . '` =\'' . $db->escape($this->getState('filters.country')) . '\') ');
			}

			if ($this->getState('filters.city')) {
				$table = $profile->getFilterTable('city');
				$field = $profile->getFilterField('city');

				$alias = array_search($table, $queriedTables);
				$query->where(' (`' . $alias . '`.`' . $field . '` =\'' . $db->escape($this->getState('filters.city')) . '\') ');
			}

			return $query;
		}

		function getTotal()
		{

			if (empty($this->total)) {
				$db =& $this->getDbo();
				$query = $this->buildQuery();
				$query->set('select', "count(distinct `a`.`id`)");
				$query->set('order', null);
				$query->set('group', null);
				$db->setQuery((string)$query);
				$this->total = $db->loadResult();
			}
			return $this->total;
		}

	} // End Class

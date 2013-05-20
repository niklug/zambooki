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

	require_once(JPATH_ROOT . DS . 'components' . DS . 'com_rbids' . DS . 'helpers' . DS . 'route.php');

	function rbidsBuildRoute(&$query)
	{

		$segments = array();
		$task = JArrayHelper::getValue($query, 'task', '');

		$controller = JArrayHelper::getValue($query, 'controller', '');

		if (!$controller && strpos($task, '.') !== FALSE) //task=controller.task?
		{
			$task = explode('.', $task);
			$controller = $task[0];
		}
		$func = ($controller) ? $func = $controller . 'Controller' : 'defaultController';

		if (is_callable(array('rbidsRouteCreate', $func))) {
			$segments = rbidsRouteCreate::$func($task, $query);
		}

		return $segments;
	}


	function rbidsParseRoute($segments)
	{


		// get a menu item based on Itemid or currently active
		$menu = &JSite::getMenu();
		$menuItem = &$menu->getActive();

		if (!isset($menuItem->query['task']))
			$menuItem->query['task'] = "listauctions";

		if (isset($menuItem->query['task']))
			$task = $menuItem->query['task'];

		$vars = array();
		if (in_array($segments[0], array(
				'details',
				'userdetails',
				'userprofile',
				'listauctions',
				'report_auction',
				'editauction',
				'tags',
				'categories',
				'myratings',
				'userratings',
				'addfunds', // Don't works
				'show_search',
				'new', // registered to form
				'newauction', // registered to form
				'editauction', // registered to form
				'republish', // registered to form
				'form',
				'cancelauction'
			)
		)
		) {
			$task = $segments[0];

			$vars['task'] = $task;
		}
		//Set the active menu item
		switch ($task) {
			case "editauction":
				$vars['id'] = $segments[1];
				break;
			case "republish":
				$vars['id'] = $segments[1];
				break;
			case "form":
				if(count($segments) > 3 && $segments[1] == 'category'){
					$vars['category'] = $segments[2];
				}
				break;
			case "cancelauction":
				$vars['id'] = $segments[1];
				break;
			case "details":
				$vars['id'] = $segments[1];
				break;
			case "userdetails":
				$vars['task'] = 'userdetails';
				$vars['controller'] = 'user';
				break;
			case "report_auction":
				$vars['id'] = $segments[1];
				break;
			case "userprofile":
				$vars['task'] = 'UserProfile';
				$vars['controller'] = 'user';
				$vars['id'] = $segments[1];
				break;
			case "listauctions":
				if (count($segments) > 2 && $segments[1] == 'category') {
					$vars['cat'] = $segments[2];
				} else if (count($segments) > 2 && $segments[1] == 'user') {
					$vars['userid'] = $segments[2];
				}
				break;
			case "categories":
				if (count($segments) > 1 && $segments[1] != 'filter_letter') {
					$vars['cat'] = $segments[1];
				} else {
					$vars['filter_letter'] = $segments[2];
				}
				if (count($segments) > 4) {
					$vars['filter_letter'] = $segments[4];
				}

				break;
			case "tags":
				$vars['tag'] = $segments[1];
				break;
			case "myratings":
				$vars['controller'] = 'ratings';
				$vars['task'] = 'myratings';
				break;
			case "userratings":
				$vars['controller'] = 'ratings';
				$vars['task'] = 'userratings';
				if (count($segments) > 2) {
					$vars['id'] = $segments[1];
				}
				break;
			case "addfunds":
				$vars['controller'] = 'balance';
				break;
			case "show_search":
				if (count($segments) > 1 && $segments[1] = 'reload') {
					$vars['reload'] = $segments[2];
				}
		}

		return $vars;

	}

	class rbidsRouteCreate
	{
		static function defaultController($task, &$query)
		{

			$segments = array();
			switch ($task) {
				case "new": // registered to form
					$segments[] = "new";
					unset($query["task"]);
					break;
				case "newauction": // registered to form
					$segments[] = "newauction";
					unset($query["task"]);
					break;
				case "editauction": // registered to form
					$segments[] = "editauction";
					$segments[] = $query['id'];
					unset($query["task"]);
					unset($query["id"]);
					break;
				case "republish": // registered to form
					$segments[] = "republish";
					$segments[] = $query['id'];
					unset($query["task"]);
					unset($query["id"]);
					break;
				case "form":
					$segments[] = "form";
					$catid = JArrayHelper::getValue($query, 'category', null);

					if ($catid) {
						$database = &JFactory::getDBO();
						$database->setQuery("select catname from #__rbid_categories where id='{$catid}'");
						$catname = JFilterOutput::stringURLSafe($database->loadResult());
						$segments[] = 'category';
						$segments[] = $catid;
						$segments[] = $catname;
						unset($query['category']);
					}


					unset($query["task"]);
					break;
				case "cancelauction":
					$segments[] = "cancelauction";
					$segments[] = $query['id'];
					unset($query["task"]);
					unset($query["id"]);
					break;
				case "report_auction":
					$segments[] = "report_auction";
					$segments[] = $query['id'];
					$database = &JFactory::getDBO();
					$database->setQuery("SELECT a.title FROM #__rbid_auctions AS a WHERE id='" . (int)$query['id'] . "'");
					$rec = $database->loadObject();
					$segments[] = JFilterOutput::stringURLSafe($database->loadResult()) . ".html";
					unset($query['id']);
					unset($query["task"]);
					break;

				case "details":
					$segments[] = "details";
					$segments[] = $query['id'];

					$database = &JFactory::getDBO();
					$database->setQuery("SELECT `a`.`title` FROM `#__rbid_auctions` AS `a` WHERE `id`='" . (int)$query['id'] . "'");
					$rec = $database->loadObject();
					$segments[] = JFilterOutput::stringURLSafe($database->loadResult()) . ".html";

					unset($query["task"]);
					unset($query['id']);
					break;

				case "listauctions":
					$segments[] = 'listauctions';
					$catid = JArrayHelper::getValue($query, 'cat', null);

					if ($catid) {
						$database = &JFactory::getDBO();
						$database->setQuery("select catname from #__rbid_categories where id='{$catid}'");
						$catname = JFilterOutput::stringURLSafe($database->loadResult());
						$segments[] = 'category';
						$segments[] = $catid;
						$segments[] = $catname;
						unset($query['cat']);
					}
					$userid = JArrayHelper::getValue($query, 'userid', 0);

					if ($userid) {
						$segments[] = 'user';
						$segments[] = $userid;
						$user = JFactory::getUser($userid);
						$segments[] = $user->username . ".html";
						unset($query['userid']);
					}

					unset($query["task"]);
					unset($query["alias"]);
					break;

				case "categories":
					$segments[] = 'categories';
					$catid = JArrayHelper::getValue($query, 'cat', null);
					if ($catid) {
						$database = &JFactory::getDBO();
						$database->setQuery("select catname from #__rbid_categories where id='{$catid}'");
						$catname = JFilterOutput::stringURLSafe($database->loadResult());
						$segments[] = $catid;
						$segments[] = $catname;
						unset($query['cat']);
					}
					$filter_letter = JArrayHelper::getValue($query, 'filter_letter', null);
					if ($filter_letter) {
						$segments[] = 'filter_letter';
						$segments[] = $filter_letter;
						unset($query["filter_letter"]);
					}
					unset($query["task"]);
					unset($query["alias"]);
					break;

				case "tags":
					$segments[] = 'tags';
					$tag = JArrayHelper::getValue($query, 'tag', null);
					$segments[] = $tag;
					unset($query["task"]);
					unset($query["tag"]);
					break;
				case 'accept':
					unset($query["Itemid"]);
					break;
				case 'show_search':
					$segments[] = 'show_search';
					$reload = JArrayHelper::getValue($query, 'reload', null);
					if ($reload) {
						$segments[] = 'reload';
						$segments[] = 1;
						unset($query["reload"]);
					}
					unset($query["task"]);
					break;
			}

			return $segments;
		}

		static function userController($task, &$query)
		{

			$segments = array();
			switch ($task) {
				case "UserProfile":
					$segments[] = "userprofile";
					$userid = JArrayHelper::getValue($query, 'id', 0);

					$segments[] = $userid;
					$user = JFactory::getUser($userid);
					$segments[] = $user->username . ".html";

					unset($query['Itemid']);
					unset($query['id']);
					unset($query["task"]);
					unset($query["controller"]);
					break;

				case "userdetails":
					$segments[] = "userdetails";
					unset($query["task"]);
					unset($query["controller"]);
					break;
			}

			return $segments;

		}

		static function balanceController($task, &$query)
		{
			$segments = array();

			switch ($task) {
				case "addfunds":
					$segments[] = 'addfunds';
					$segments[] = 'balance';
					unset($query["task"]);
					unset($query["controller"]);
					break;
			}
			return $segments;
		}

		static function ratingsController($task, &$query)
		{

			$segments = array();
			switch ($task) {
				case "myratings":
					$segments[] = 'myratings';
					unset($query["task"]);
					unset($query["controller"]);
					break;
				case "userratings":
					$segments[] = "userratings";
					$userid = JArrayHelper::getValue($query, 'id', null);
					if ($userid) {
						$user = JFactory::getUser($userid);
						$segments[] = $userid;
						$segments[] = $user->username . ".html";
						unset($query["id"]);
					}
					unset($query["task"]);
					unset($query["controller"]);
					break;
			}

			return $segments;
		}
	} // End Class

<?php
	/**------------------------------------------------------------------------
	thefactory - The Factory Class Library - v 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : thefactory
	 * @subpackage: category
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');


	jimport('joomla.application.component.model');
	jimport('joomla.application.component.helper');

	class JTheFactoryModelCategory extends JModel
	{
		var $category_table = '';
		var $_category_tree = null;

		/**
		 *
		 */
		function __construct()
		{
			parent::__construct();
			$myApp =& JTheFactoryApplication::getInstance();

			$this->category_table = $myApp->getIniValue('table', 'categories');
		}

		/**
		 * @param      $parent
		 * @param bool $include_disabled
		 *
		 * @return mixed
		 */
		function getCategoryCount($parent = -1, $include_disabled = false)
		{
			$w = array();

			if ($parent >= 0)
				$w[] = "`parent`=$parent";
			if (!$include_disabled)
				$w[] = "`status`=1";

			$where = count($w) ? (" WHERE " . implode(' AND ', $w)) : "";

			$db =& $this->getDbo();
			$q = "SELECT count(*) FROM `{$this->category_table}` c $where";
			$db->setQuery($q);
			return $db->loadResult();
		}

		/**
		 * @param int $parentid
		 *
		 * @return mixed
		 */
		function getFirstCategory($parentid = 0)
		{
			$db =& $this->getDbo();
			$db->setQuery("SELECT * FROM `{$this->category_table}` c where status=1 and parent='{$parentid}' order by `ordering`", 0, 1);
			return $db->loadObject();
		}

		/**
		 * @param int  $parent_cat
		 * @param bool $include_disabled
		 *
		 * @return mixed
		 */
		function getCategoryTree($parent_cat = 0, $include_disabled = false)
		{
			if (isset($this->_category_tree[$include_disabled])) // already got
				return $this->_category_tree[$include_disabled];

			$db =& $this->getDbo();
			$tree = array();
			if ($parent_cat) {
				$q = "SELECT * FROM `{$this->category_table}` c WHERE id='$parent_cat'";
				$db->setQuery($q);
				$current_cat = $db->loadObject();
				$current_cat->depth = 0;
				$tree[] = $current_cat;
			}
			$tree = array_merge($tree, self::getCategoryTreeRecursive($parent_cat, $include_disabled));
			$this->_category_tree[$include_disabled] = $tree;
			return $this->_category_tree[$include_disabled];
		}

		/**
		 * @param int  $parent_cat
		 * @param bool $include_disabled
		 *
		 * @return array
		 */
		function getCategoryTreeRecursive($parent_cat = 0, $include_disabled = false)
		{
			static $depth = 1;
			$db =& $this->getDbo();
			$count_qry = "select count(*) from {$this->category_table} where parent=c.id " .
				(($include_disabled) ? "" : " and status=1");
			$where[] = "parent='$parent_cat'";
			if (!$include_disabled)
				$where[] = "status=1";
			$w = implode(' AND ', $where);
			$q = "SELECT *,($count_qry) as nr_children
                FROM `{$this->category_table}` c WHERE {$w} ORDER BY `ordering`,`id` ";
			$db->setQuery($q);
			$subcats = $db->loadObjectList();

			$tree = array();
			foreach ($subcats as $cat) {
				$cat->depth = $depth;
				$tree[] = $cat;
				$depth++;
				$tree = array_merge($tree, self::getCategoryTreeRecursive($cat->id, $include_disabled));
				$depth--;
			}
			return $tree;
		}

		/**
		 * @param int  $parentid
		 * @param bool $enabled_only
		 *
		 * @return array
		 */
		function getCategoriesNested($parentid = 0, $enabled_only = true)
		{
			//build whole tree
			$counter_current = 0;
			$cat_array = $this->recurseCategoriesNested(0, $enabled_only, $counter_current);
			if ($parentid > 0 && count($cat_array)) {
				$node = null;
				$sub_tree = array();
				for ($i = 0; $i < count($cat_array); $i++)
					if ($cat_array[$i]->id == $parentid) {
						$node = $cat_array[$i];
						break;
					}
				if (!$node) return $sub_tree; //node not found
				for ($i = 0; $i < count($cat_array); $i++)
					if ($cat_array[$i]->left >= $node->left && $cat_array[$i]->right <= $node->right)
						$sub_tree[] = $cat_array[$i];
				return $sub_tree;
			}
			return $cat_array;
		}

		/**
		 * @param $parentid
		 * @param $enabled_only
		 * @param $counter
		 *
		 * @return array
		 */
		function recurseCategoriesNested($parentid, $enabled_only, &$counter)
		{
			$cat_array = array();
			$filter = '';
			$db = & $this->getDbo();
			if ($enabled_only)
				$filter = "`status`=1 and ";
			$db->setQuery("SELECT c.* FROM {$this->category_table} c WHERE {$filter} `parent`='" . $parentid . "' order by `ordering`");
			$cats = $db->loadObjectList();
			foreach ($cats as $cat) {
				$counter++;
				$cat->left = $counter;
				$c = $this->recurseCategoriesNested($cat->id, $enabled_only, $counter);
				$counter++;
				$cat->right = $counter;

				$cat_array[] = $cat;

				$cat_array = array_merge($c, $cat_array);
			}

			return $cat_array;
		}

		/**
		 * @return mixed
		 */
		function getMaxOrdering()
		{
			$db =& $this->getDbo();
			$db->setQuery("select max(`ordering`) FROM {$this->category_table}");
			return $db->loadResult();
		}

		/**
		 * @param $catid
		 *
		 * @return array
		 */
		function getCategoryPathArray($catid)
		{
			$db =& $this->getDbo();
			$path_array = array();
			while ($catid) {
				$db->setQuery("select * FROM {$this->category_table} where id='$catid'");
				$cat = $db->loadObject();
				$path_array[] = $cat;

				$catid = $cat->parent;
			}

			return $path_array;

		}

		/**
		 * @param $catid
		 *
		 * @return string
		 */
		function getCategoryPathString($catid)
		{
			$cat_array = $this->getCategoryPathArray($catid);
			$catstring = "";
			foreach ($cat_array as $cat)
				$catstring .= (($catstring) ? "/" : "") . $cat->catname;
			return $catstring;

		}

		/**
		 * @param $cids
		 *
		 * @return mixed
		 */
		function delCategory($cids)
		{
			if (!is_array($cids) && $cids) $cids = array($cids);
			if (!count($cids)) return;

			$db =& $this->getDbo();
			$db->setQuery("delete from `{$this->category_table}` where id in (" . implode(',', $cids) . ")");
			$db->query();
			$nr = $db->affected_rows;
			$db->setQuery("update `{$this->category_table}` set parent=0 where parent in (" . implode(',', $cids) . ")");
			$db->query();
			return $nr;
		}

		/**
		 * @param $quicktext
		 */
		function quickAddFromText($quicktext)
		{

			if ('WIN' == substr(PHP_OS, 0, 3)) {
				$separator = "\r\n";
			} else {
				$separator = "\n";
			}

			$textcats = explode($separator, $quicktext);

			$stack = array(0);

			$i = 0;
			$last_id = null;

			$prevcat = '';
			$prevcat_spaces = 0;
			$ordering = $this->getMaxOrdering();
			$cattable =& JTable::getInstance('Category', 'JTheFactoryTable');

			$lang = JFactory::getLanguage();

			foreach ($textcats as $key => $cat) {
				if (!trim($cat)) continue; //skip empty categories
				$ordering++;
				$cat_spaces = 0;
				if (preg_match('/^[ ]*/', $cat, $matches))
					$cat_spaces = strlen($matches[0]);

				if ($cat_spaces > $prevcat_spaces)
					array_push($stack, $last_id);
				if ($cat_spaces < $prevcat_spaces) {
					$diff_level = $prevcat_spaces - $cat_spaces;
					for ($j = 0; $j < $diff_level; $j++) {
						array_pop($stack);
					}
				}
				$cat = trim($cat);
				$cattable->id = null;
				$cattable->catname = $cat;
				$cattable->parent = end($stack);
				$cattable->ordering = $ordering;
				$cattable->hash = md5(strtolower(JTheFactoryHelper::str_clean($lang->transliterate($cat))));
				$cattable->store();

				$prevcat = $cat;
				$prevcat_spaces = $cat_spaces;
				$last_id = $cattable->id;

				$catpath = $this->getCategoryPathString($cattable->id);
				$catpath = JFilterOutput::stringURLSafe($lang->transliterate($catpath));

			}

		}

		/**
		 * @param int $rootcat
		 *
		 * @return array
		 */
		function getCategoryChildren($rootcat = 0)
		{
			//TODO:return lista cu idurile copiilor
			$db = &$this->getDBO();
			$db->setQuery("SELECT id FROM `{$this->category_table}` WHERE parent = '{$rootcat}'");
			$res = $db->loadResultArray();
			$subcat = array();
			for ($i = 0; $i < count($res); $i++)
				$subcat = array_merge($subcat, $this->getCategoryChildren($res[$i]));
			return array_merge($res, $subcat);
		}

	}

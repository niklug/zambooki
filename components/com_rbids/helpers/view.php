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

	class RBidsHelperView
	{
		/**
		 * prepareCategoryTree Class Method
		 *
		 * @static
		 *
		 * @param        $categories
		 * @param string $task
		 */
		static function prepareCategoryTree(&$categories, $task = 'categories')
		{
			$categoryItemid = RBidsHelperRoute::getItemid(array('task' => 'categories'));
			$auctionsItemid = RBidsHelperRoute::getItemid(array('task' => 'listauctions'));

			for ($i = 0; $i < count($categories); $i++) {
				$row =& $categories[$i];

				$catslug = "";
				if (isset($row->catslug)) {
					$separator = PHP_EOL;
					$catslug = str_replace($separator, "/", str_replace("/", "-", $row->catslug));
					$catslug = "&amp;catslug=$catslug";
				}
				if (isset($row->watchListed_flag)) {
					if ($row->watchListed_flag)
						$row->link_watchlist = RBidsHelperRoute::getDelToCatWatchlist($row->id);
					else
						$row->link_watchlist = RBidsHelperRoute::getAddToCatWatchlist($row->id);
				}

				$row->link = RBidsHelperRoute::getCategoryRoute($row->id, 'all', $task, $catslug);
				$row->link_new_listing = RBidsHelperRoute::getNewAuctionInCategoryRoute($row->id);
				$row->view = RBidsHelperRoute::getAuctionListRoute(array('cat' => $row->id));
				$row->descr = $row->description; //backwards compatibility. will be removed ASAP

				if (count($row->subcategories))
					self::prepareCategoryTree($row->subcategories); //recursion
			}


		}

		/**
		 * buildLetterFilter
		 *
		 * @static
		 *
		 * @param $filter_cat
		 *
		 * @internal param $url
		 *
		 * @return string
		 */
		static function buildLetterFilter($filter_cat)
		{

			$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
			$filter_letter = JRequest::getString('filter_letter', 'all');

			$letters_filter = "<div id='box_letters_filter'>";
			foreach ($letters as $letter) {

				$active = strtolower($filter_letter) == strtolower($letter) ? 'active' : '';
				$letters_filter .= "<a href='" . RBidsHelperRoute::getCategoryRoute($filter_cat, $letter) . "' class='" . $active . "'>" . $letter . "</a>";
			}
			// List all categories
			$letters_filter .= "<a href='" . RBidsHelperRoute::getCategoryRoute($filter_cat) . "'>" . JText::_('COM_RBIDS_RESET_FILTER') . "</a>";
			$letters_filter .= "</div>";

			return $letters_filter;
		}

		/**
		 * loadJQuery Class Method
		 */
		static function loadJQuery()
		{

			$jdoc = &JFactory::getDocument();
			$j_loaded = false;
			foreach ($jdoc->_scripts as $j => $jj)
				if (strstr($j, "jquery.js") !== false)
					return;
			$jdoc->addScript('components/com_rbids/js/jquery/jquery.js');
			$jdoc->addScript('components/com_rbids/js/jquery/jquery.noconflict.js');

		}

	} // End Class

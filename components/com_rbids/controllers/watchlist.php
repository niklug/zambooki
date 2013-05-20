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
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class WatchlistController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		function AddWatchCat()
		{
			$cat = JRequest::getInt('cat', 0);
			if (!$cat) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_SPECIFY_A_CATEGORY"));
				return;
			}
			$categoryModel =& JModel::getInstance('RCategory', 'rbidsModel');
			$categoryModel->addWatch($cat);

			$refferer = $_SERVER['HTTP_REFERER'];
			if (!$refferer) $refferer = RBidsHelperRoute::getCategoryRoute();
			$this->setRedirect($refferer, JText::_("COM_RBIDS_ADDED_TO_WACHLIST"));

		}

		function DelWatchCat()
		{
			$cat = JRequest::getInt('cat', 0);
			if (!$cat) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_SPECIFY_A_CATEGORY"));
				return;
			}
			$categoryModel =& JModel::getInstance('RCategory', 'rbidsModel');
			$categoryModel->delWatch($cat);

			$refferer = $_SERVER['HTTP_REFERER'];
			if (!$refferer) $refferer = RBidsHelperRoute::getCategoryRoute();
			$this->setRedirect($refferer, JText::_("COM_RBIDS_AUCTION_REMOVED_FROM_WATCHLIST"));
		}

		function DelWatch()
		{

			$id = JRequest::getInt('id');
			$my = & JFactory::getUser();

			$database = & JFactory::getDBO();
			$database->setQuery("delete from  #__rbid_watchlist  where userid=$my->id and auction_id='$id'");
			$database->query();

			$refferer = $_SERVER['HTTP_REFERER'];
			if (!$refferer) $refferer = RBidsHelperRoute::getAuctionListRoute();

			$this->setRedirect($refferer, JText::_("COM_RBIDS_AUCTION_REMOVED_FROM_WATCHLIST"));
		}

		function AddWatchList()
		{

			$database = & JFactory::getDBO();
			$my = & JFactory::getUser();
			$id = JRequest::getInt('id');

			$database = & JFactory::getDBO();
			$database->setQuery("delete from  #__rbid_watchlist  where userid=$my->id and auction_id='$id'");
			$database->query(); //make sure you do not add duplicates

			$watchList = & JTable::getInstance('watchlist', 'Table');
			$watchList->userid = $my->id;
			$watchList->auction_id = $id;
			$watchList->store();
			$refferer = $_SERVER['HTTP_REFERER'];
			if (!$refferer) $refferer = RBidsHelperRoute::getAuctionListRoute();

			$this->setRedirect($refferer, JText::_("COM_RBIDS_AUCTION_ADDED_IN_WATCHLIST"));
		}

		function watchlist()
		{
			$view = $this->getView('RBids', 'html');
			$view->display('t_mywatchlist.tpl');
		}

	}

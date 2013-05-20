<?php
/**------------------------------------------------------------------------
com_rbids - Reverse Auction Factory 3.0.0
------------------------------------------------------------------------
 * @author TheFactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thefactory.ro
 * Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
 * @build: 01/04/2012
 * @package: RBids
 * @subpackage: CBPlugins
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class getmyrwatchlistTab extends cbTabHandler {

	function getmyrwatchlistTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		
        $Itemid=JRequest::getInt('Itemid');
		
		$database = & JFactory::getDbo();
		$my = & JFactory::getUser();
        $app = &JFactory::getApplication();

		$LO = JFactory::getLanguage();
		$LO->load('com_rbids');
        $context='getmyrwatchlisttab';

		if($my->id!=$user->user_id){
			return null;
		}

		if(!file_exists(JPATH_ROOT.DS."components".DS."com_rbids".DS."rbids.php")){
			  return "<div>You must First install <a href='http://www.thefactory.ro/shop/joomla-components/auction-factory.html'> Reverse Auction Factory </a></div>";
		}
		
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."options.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."tools.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."route.php");

        $cfg=new RbidConfig();
		$limitstart = $app->getUserStateFromRequest($context.'.limitstart','limitstart',0,'INT');

        $w="";        
        if ($cfg->admin_approval)
            $w="AND b.approved='1'"; 

		$query = "SELECT count(*)
			FROM #__rbid_auctions b
			left join #__rbid_watchlist w on '$user->user_id'=w.userid
			left join #__users u on b.userid = u.id
			where b.id=w.auction_id $w ";

		$database->setQuery($query);
		$total = $database->loadResult();

		$pagingParams = $this->_getPaging( array(), array( "rmywatchlist_" ) );
		if (isset($pagingParams["rmywatchlist_limitstart"])) {
			$limitstart=$pagingParams["rmywatchlist_limitstart"];
		}

		$query = "SELECT u.username,b.*
			FROM #__rbid_auctions b
			left join #__rbid_watchlist w on '$user->user_id'=w.userid
			left join #__users u on b.userid = u.id
			where b.id=w.auction_id $w order by id desc";

		$database->setQuery($query,$limitstart, $cfg->nr_items_per_page);
		$mywatches = $database->loadObjectList();
		
		$pagingParams["limitstart"] = $limitstart;
		$pagingParams["limit"] = $cfg->nr_items_per_page;

		$return = "\t\t<div>\n";
		$return .='<form name="topForm'.$tab->tabid.'" action="index.php" method="post">';
		$return .="<input type='hidden' name='option' value='com_comprofiler' />";
		$return .="<input type='hidden' name='task' value='userProfile' />";
		$return .="<input type='hidden' name='user' value='".$user->user_id."' />";
		$return .="<input type='hidden' name='tab' value='".$tab->tabid."' />";
		$return .="<input type='hidden' name='act' value='' />";
		$return .="<table width='100%'>";


		if($mywatches) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_TITLE").'</th>';
			$return .= '<th class="list_ratings_header" width="80">'.JText::_("COM_RBIDS_AUCTIONEER").'</th>';
			$return .= '<th class="list_ratings_header" width="80">'.JText::_("COM_RBIDS_START_DATE").'</th>';
			$return .= '<th class="list_ratings_header" width="80">'.JText::_("COM_RBIDS_END_DATE").'</th>';
			$return .= '<th class="list_ratings_header" width="80">'.JText::_("COM_RBIDS_MAX_PRICE").'</th>';
			$return .= '</tr>';
			$k=0;
			foreach ($mywatches as $mw){
    			 $return .='<tr class="mywatch'.$k.'">';
    			 $return .='<td>';
    			 $return .= '<a href="'.RBidsHelperRoute::getAuctionDetailRoute($mw->id).'">'.$mw->title.'</a>';
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= '<a href="'.RBidsHelperRoute::getUserdetailsRoute($mw->userid).'">'.$mw->username.'</a>';
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= JHtml::date($mw->start_date,$cfg->date_format,false);
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= JHtml::date($mw->end_date,$cfg->date_format,false);
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= $mw->max_price." ".$mw->currency;
    			 $return .='</td>';
    			 $return .= "</tr>";
    			 $k=1-$k;

			 }
		} else {
			$return .=	"".JText::_("COM_RBIDS_NO_ITEMS")."";
		}
		if($total>=$cfg->nr_items_per_page)
			$pageslinks = "index.php?option=com_comprofiler&task=userProfile&user=$user->user_id&limitstart=$limitstart&tab=$tab->tabid";
		$return .= "<tr height='20px'>";
		$return .= "<td colspan='3' align='center'>";
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td colspan='2' align='center'>";
		$return .= $this->_writePaging($pagingParams,"rmywatchlist_", $cfg->nr_items_per_page, $total);
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "</table>";
		$return .= "</form>";
		$return .= "</div>";

		return $return;
	}
}
?>

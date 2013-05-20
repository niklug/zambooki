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

class getmyrauctionsTab extends cbTabHandler {

	function getmyrauctionsTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		
        $Itemid=JRequest::getInt('Itemid');
        $context='getmyrauctionstab';
		
		$LO = JFactory::getLanguage();
		$LO->load('com_rbids');
		
		$database = & JFactory::getDbo();
		$my = & JFactory::getUser();
        $app = &JFactory::getApplication();

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
			left join #__users u on b.userid=u.id
			where userid = '$user->user_id' $w";

		$database->setQuery($query);
		$total = $database->loadResult();
		
		$query = "SELECT u.username,b.*
			FROM #__rbid_auctions b
			left join #__users u on b.userid=u.id
			where userid = '$user->user_id' $w order by `start_date` desc";

		$pagingParams = $this->_getPaging( array(), array( "myauctions_" ) );
		if (isset($pagingParams["myauctions_limitstart"])) {
			$limitstart=$pagingParams["myauctions_limitstart"];
		}

		$database->setQuery($query,$limitstart, $cfg->nr_items_per_page);
		$myauctions = $database->loadObjectList();
		
		$pagingParams["limitstart"] = $limitstart;
		$pagingParams["limit"] = $cfg->nr_items_per_page;

		$return ="";
		$return .= "\t\t<div>\n";
		$return .='<form name="topForm'.$tab->tabid.'" action="index.php" method="post">';
		$return .="<input type='hidden' name='option' value='com_comprofiler' />";
		$return .="<input type='hidden' name='task' value='userProfile' />";
		$return .="<input type='hidden' name='user' value='".$user->user_id."' />";
		$return .="<input type='hidden' name='tab' value='".$tab->tabid."' />";
		$return .="<table width='100%'>";
		if($myauctions) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_TITLE").'</th>';
			$return .= '<th class="list_ratings_header" width="100">'.JText::_("COM_RBIDS_START_DATE").'</th>';
			$return .= '<th class="list_ratings_header" width="100">'.JText::_("COM_RBIDS_END_DATE").'</th>';
			$return .= '<th class="list_ratings_header" width="100">'.JText::_("COM_RBIDS_MAX_PRICE").'</th>';

			if($my->id==$user->user_id){
				$return .= '<th class="list_ratings_header" width="100">'.JText::_("COM_RBIDS_LAST_BID").'</th>';
			}

			$return .= '</tr>';
			$k=0;
			foreach ($myauctions as $ma) 
            {
                if($my->id == $user->user_id){
                    $query = "SELECT bid_price from #__rbids where cancel=0 and auction_id='$ma->id' order by id desc limit 1";
                    $database->setQuery($query);
                    $last_bid = $database->loadResult();
                    if($last_bid)
                        $last_bid=$last_bid." ".$ma->currency;
                }
                $return .='<tr class="mywatch'.$k.'">';
                $return .='<td>';
                $return .= '<a href="'.RBidsHelperRoute::getAuctionDetailRoute($ma->id).'">'.$ma->title.'</a>';
                $return .='</td>';
                
                $return .='<td>';
                $return .=JHtml::date($ma->start_date,$cfg->date_format,false);
                $return .='</td>';
                $return .='<td>';
                $return .=JHtml::date($ma->end_date,$cfg->date_format,false);
                $return .='</td>';
                $return .='<td>';
                $return .= $ma->max_price." ".$ma->currency;
                $return .='</td>';
                if($my->id==$user->user_id){
                    $return .= '<td>'.($last_bid?$last_bid:"-").'</td>';
                }
                $return .= "</tr>";
                $k=1-$k;
			}
		} else {
			$return .=	JText::_("COM_RBIDS_NO_AUCTIONS_POSTED");
		}
		if($total>=$cfg->nr_items_per_page)
			$pageslinks = "index.php?option=com_comprofiler&task=userProfile&user=$user->user_id&limitstart=$limitstart&tab=$tab->tabid&Itemid=$Itemid";

		$return .= "<tr height='20px'>";
		$return .= "<td colspan='3' align='center'>";
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td colspan='2' align='center'>";
		$return .= $this->_writePaging($pagingParams,"myauctions_", $cfg->nr_items_per_page, $total);
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "</table>";
		$return .= "</form>";
		$return .= "</div>";

		return $return;
	}
}
?>

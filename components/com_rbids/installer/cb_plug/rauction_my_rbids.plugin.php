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

class getmyrbidsTab extends cbTabHandler {

	function getmyrbidsTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		
        $Itemid=JRequest::getInt('Itemid');

		$database = & JFactory::getDbo();
		$my = & JFactory::getUser();
        $app = &JFactory::getApplication();
		
		$LO = JFactory::getLanguage();
		$LO->load('com_rbids');
        $context='getmyrbidstab';

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

		$query="select count(*) from #__rbids a
            left join #__rbid_auctions b on a.auction_id=b.id
            left join #__users u on u.id=b.userid
            where a.userid = '$user->user_id' and a.cancel=0
            and b.close_offer =0 and  b.close_by_admin=0 and published=1 $w";

		$database->setQuery($query);
		$total = $database->loadResult();

		$pagingParams = $this->_getPaging( array(), array( "rmybids_" ) );
		if (isset($pagingParams["rmybids_limitstart"])) {
			$limitstart=$pagingParams["rmybids_limitstart"];
		}

		$query="select a.id as parent_message,a.bid_price,a.modified as bid_date,
            a.comments as comments,
            a.accept as accept, a.cancel as cancel,
             b.*,u.name as name, u.username
            from #__rbids a
            left join #__rbid_auctions b on a.auction_id=b.id
            left join #__users u on u.id=b.userid
            where a.userid = '$user->user_id' and a.cancel=0
            and b.close_offer =0 and  b.close_by_admin=0 and published=1 $w
        order by a.id desc";
		$database->setQuery($query,$limitstart, $cfg->nr_items_per_page);
		$mybids = $database->loadObjectList();
		
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


		if($mybids) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_TITLE").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_AUCTIONEER").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_START_DATE").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_END_DATE").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_BID_PRICE").'</th>';
			$return .= '</tr>';
			$k=0;
			foreach ($mybids as $mb){
        		 $return .='<tr class="mywatch'.$k.'">';
        		 $return .='<td>';
        		 $return .= '<a href="'.RBidsHelperRoute::getAuctionDetailRoute($mb->id).'">'.$mb->title.'</a>';
        		 $return .='</td>';
        		 $return .='<td>';
        		 $return .= '<a href="'.RBidsHelperRoute::getUserdetailsRoute($mb->userid).'">'.$mb->username.'</a>';
        		 $return .='</td>';
        		 $return .='<td>';
        		 $return .= JHtml::date($mb->start_date,$cfg->date_format,false);
        		 $return .='</td>';
        		 $return .='<td>';
        		 $return .= JHtml::date($mb->end_date,$cfg->date_format,false);
        		 $return .='</td>';
        		 $return .='<td>';
        		 $return .= $mb->bid_price." ".$mb->currency;
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
		$return .= $this->_writePaging($pagingParams,"rmybids_", $cfg->nr_items_per_page, $total);
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "</table>";
		$return .= "</form>";
		$return .= "</div>";

		return $return;
	}
}
?>

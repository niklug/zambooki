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

class getmywonrbidsTab extends cbTabHandler {

	function getmywonrbidsTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		
        $Itemid=JRequest::getInt('Itemid');
		 
		$database = & JFactory::getDbo();
		$my = & JFactory::getUser();
        $app = &JFactory::getApplication();
		
		$LO = JFactory::getLanguage();
		$LO->load('com_rbids');
        $context='getmywonrbidstab';

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
		
		
        JHTML::script("ratings.js",'components/com_rbids/js/');

        $query="select count(*)
            from #__rbids a
            left join #__rbid_auctions b on a.auction_id=b.id
            left join #__users u on u.id=b.userid
            where a.userid='$my->id' and accept=1 and a.cancel=0 $w
            ";

		$database->setQuery($query);
		$total = $database->loadResult();

		$pagingParams = $this->_getPaging( array(), array( "rmywonbids_" ) );
		if (isset($pagingParams["rmywonbids_limitstart"])) {
			$limitstart=$pagingParams["rmywonbids_limitstart"];
		}

        $query="select a.id as parent_message,a.bid_price,a.modified as bid_date,
            a.accept as accept, a.cancel as cancel,
             b.*, u.name as name, u.username,
             r.rating
            from #__rbids a
            left join #__rbid_auctions b on a.auction_id=b.id
            left join #__users u on u.id=b.userid
    	    left join #__rbid_rate r on r.voter='$my->id' and r.auction_id=b.id
            where a.userid='$my->id' and accept=1 and a.cancel=0 $w
            order by id desc";

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
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_END_DATE").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_BID_PRICE").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_RATE").'</th>';
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
    			 $return .= JHtml::date($mb->closed_date,$cfg->date_format,false);
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= $mb->bid_price." ".$mb->currency;
    			 $return .='</td>';
    			 $return .='<td>';
    			 
    			  
    			 if ($mb->rating){
    			     $return .=	"<span class='rating'>".$mb->rating."</span>";
    			 }else{
        			 $return .= '<a href="'.RBidsHelperRoute::getShowRateAuctionRoute($mb->id,$mb->userid).'">'.JText::_("COM_RBIDS_RATE").'</a>';
    			 }
    			 $return .='</td>';
    			 $return .= "</tr>";
    			 $k=1-$k;

			 }
		} else {
			$return .=	JText::_("COM_RBIDS_NO_WON_BIDS_YET");
		}
        
		$pageslinks = "index.php?option=com_comprofiler&task=userProfile&user=$user->user_id&tab=$tab->tabid";

		$return .= "<tr height='20px'>";
		$return .= "<td colspan='3' align='center'>";
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td colspan='2' align='center'>";
		$return .= $this->_writePaging($pagingParams,"rmywonbids_", $cfg->nr_items_per_page, $total);
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "</form>";
		$return .= "</table>";
		$return .= "</div>";

		return $return;
	}
}
?>

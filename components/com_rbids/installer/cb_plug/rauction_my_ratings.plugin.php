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

class getmyrratingsTab extends cbTabHandler {

	function getmyrratingsTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		
        $Itemid=JRequest::getInt('Itemid');
		
		$LO = JFactory::getLanguage();
		$LO->load('com_rbids');
		
		$database = & JFactory::getDbo();
		$my = & JFactory::getUser();
        $app = &JFactory::getApplication();
        $context='getmyrratingstab';

		if(!file_exists(JPATH_ROOT.DS."components".DS."com_rbids".DS."rbids.php")){
			  return "<div>You must First install <a href='http://www.thefactory.ro/shop/joomla-components/auction-factory.html'> Reverse Auction Factory </a></div>";
		}
		
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."options.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."tools.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."route.php");
		
        $cfg=new RbidConfig();
        
        JHTML::_("behavior.mootools");
		JHTML::script("ratings.js",'components/com_rbids/js/');
        $doc=&JFactory::getDocument();
        $doc->addStyleDeclaration("#auction_star{height:12px;margin:0px;padding:0px;}");
        $doc->addScriptDeclaration("var image_link_dir='".JURI::root()."components/com_rbids/images/';");
        
        $cfg=new RbidConfig();
		$limitstart = $app->getUserStateFromRequest($context.'.limitstart','limitstart',0,'INT');
    
		$query = "select count(*) from #__rbid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__rbid_auctions a on r.auction_id = a.id
              where r.user_rated = '$user->user_id' ";

		$database->setQuery($query);
		$total = $database->loadResult();

		$pagingParams = $this->_getPaging( array(), array( "rmyratings_" ) );
		if (isset($pagingParams["rmyratings__limitstart"])) {
			$limitstart=$pagingParams["rmyratings__limitstart"];
		}

		$query = "select r.*,us.username,a.title from #__rbid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__rbid_auctions a on r.auction_id = a.id
              where r.user_rated = '$user->user_id' ";
		$database->setQuery($query,$limitstart, $cfg->nr_items_per_page);
		$myratings = $database->loadObjectList();
		
		$pagingParams["limitstart"] = $limitstart;
		$pagingParams["limit"] = $cfg->nr_items_per_page;
		

		$return = "";
		$return .= "\t\t<div>\n";
		$return .='<form name="topForm'.$tab->tabid.'" action="index.php" method="post">';
		$return .="<input type='hidden' name='option' value='com_comprofiler' />";
		$return .="<input type='hidden' name='task' value='userProfile' />";
		$return .="<input type='hidden' name='user' value='".$user->user_id."' />";
		$return .="<input type='hidden' name='tab' value='".$tab->tabid."' />";
		$return .="<input type='hidden' name='act' value='' />";
		$return .="<table width='100%'>";
		if($myratings) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_USERNAME").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_TITLE").'</th>';
			$return .= '<th class="list_ratings_header">'.JText::_("COM_RBIDS_RATE").'</th>';
			$return .= '</tr>';
			$k=0;
			foreach ($myratings as $mr){			
    			 $return .='<tr class="myrating'.$k.'">';
    			 $return .='<td width="15%" >';
    			 $return .= "<a href='".RBidsHelperRoute::getUserdetailsRoute($mr->voter)."'>$mr->username</a>";
    			 $return .= "</td>";
    			 $return .="<td width='*%'>";
    			 $return .="<a href='".RBidsHelperRoute::getAuctionDetailRoute($mr->auction_id)."'>$mr->title</a>";
    			 $return .="</td>";
    			 $return .="<td width='90'>";
				 $return .=	"<span class='rating'>".$mr->rating."</span>";
    			 $return .="</td>";
    			 $return .="</tr>";
    			 $return .="<tr class='myrating".($k)."'>";
    			 $return .="<td colspan='3'><div class='msg_text'>";
    			 $return .= $mr->message;
    			 $return .= "</div></td>";
    			 $return .= "</tr>";
    			 $k=1-$k;
			 }
		} else {
			$return .=	"".JText::_("COM_RBIDS_NO_RATINGS_YET")."";
		}
		if($total>=$cfg->nr_items_per_page)
			$pageslinks = "index.php?option=com_comprofiler&task=userProfile&user=$user->user_id&limitstart=$limitstart&tab=$tab->tabid&Itemid=$Itemid";
            
		$return .= "<tr height='20px'>";
		$return .= "<td colspan='3' align='center'>";
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td colspan='2' align='center'>";
		$return .= $this->_writePaging($pagingParams,"rmyratings_", $cfg->nr_items_per_page, $total);
		$return .= "</td>";
		$return .= "</tr>";

		$return .= "</table>";
		$return .= "</form>";
		$return .= "</div>";
		return $return;
	}
}

?>

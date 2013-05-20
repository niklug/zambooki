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

class myrTaskPad extends cbTabHandler {

	function getmywatchlistTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		
        $Itemid=JRequest::getInt('Itemid');

		$database = & JFactory::getDbo();
		$my = & JFactory::getUser();
		$LO = JFactory::getLanguage();
		$LO->load('com_rbids');
		
		if($my->id!=$user->user_id || !$my->id){
			return null;
		}

		if(!file_exists(JPATH_ROOT.DS."components".DS."com_rbids".DS."rbids.php")){
			  return "<div>You must First install <a href='http://www.thefactory.ro/shop/joomla-components/auction-factory.html'> Reverse Auction Factory </a></div>";
		}
		
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."options.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."tools.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."route.php");
        
        $cfg=new RbidConfig();

		$tasklist=array(
		  'newauction'=>'f_newauction.png',
		  'myauctions'=>'f_myauctions.png ',
		  'mybids'=>'f_mybids.png',
		  'mywonbids'=>'f_mywonbids.png',
		  'watchlist&controller=watchlist'=>'f_mywatchlist.png',
		  'listcats'=>'f_listcats.png',
		  'listauctions'=>'f_listauctions.png',
		  'search'=>'f_search.png'
		);
		$isSeller=true;
		$isBidder=true;
        if ($cfg->enable_acl){
            $user_groups=JAccess::getGroupsByUser($user->user_id);
            $isBidder=count(array_intersect($user_groups,$cfg->bidder_groups))>0;
            $isSeller=count(array_intersect($user_groups,$cfg->seller_groups))>0;
        }
        
		$isVerified=false;
		$isPowerseller=false;
		
		$isVerified_field = "";
		$isPowerseller_field = "";
		
		$database->setQuery("SELECT * FROM #__rbid_fields_assoc");
		$qq = $database->loadObjectList("field");
		
        if(isset($qq["verified"]) && $qq["verified"]!="") $isVerified_field = $qq["verified"]->assoc_field;		
		if(isset($qq["powerseller"]) && $qq["powerseller"]!="")	$isPowerseller_field = $qq["powerseller"]->assoc_field;
		
		if($isVerified_field)
			$isVerified = $user->$isVerified_field;
		if($isPowerseller_field)
			$isPowerseller = $user->$isPowerseller_field;

		$database->setQuery("SELECT * FROM #__rbid_payment_balance b  WHERE b.userid = '$my->id'");
		$balance = $database->loadObject();

		$database->setQuery("SELECT * FROM #__rbid_currency c  WHERE `default`=1");
		$default_currency = $database->loadObject();

		$return = "\t\t<div>\n";
   		$return .="<table width='100%'>";
		$return .= "\t\t<tr><td colspan=4><div style='padding-left:120px;'>\n";
		if ($isSeller) {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/f_can_sell1.gif' border=0 width=25>\n";
		}else {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/f_can_sell2.gif' border=0 width=25>\n";
		}
		if ($isBidder) {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/f_can_buy1.gif' border=0 width=25>\n";
		}else {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/f_can_buy2.gif' border=0 width=25>\n";
		}
		if ($isVerified) {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/verified_1.gif' border=0 width=25>\n";
		}else {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/verified_0.gif' border=0 width=25>\n";
		}
		if ($isPowerseller) {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/powerseller1.png' border=0 width=25>\n";
		}else {
    		$return .= "\t\t<img style='margin-right:70px;' src='".JUri::root()."/components/com_rbids/images/user/powerseller0.png' border=0 width=25 >\n";
		}
		$return .= "\t\t</div></td></tr>\n";
		$return .= "\t\t<tr><td colspan=4><div style='padding-left:80px;'>\n";
   		$return .= "\t\t<div style='width:100px;float:left;text-align:center;'>".JText::_("COM_RBIDS_SELLER_GROUP").":<br />".($isSeller?JText::_("COM_RBIDS_YES"):JText::_("COM_RBIDS_NO"))."</div>\n";
   		$return .= "\t\t<div style='width:100px;float:left;text-align:center;'>".JText::_("COM_RBIDS_BIDDER_GROUP").":<br />".($isBidder?JText::_("COM_RBIDS_YES"):JText::_("COM_RBIDS_NO"))."</div>\n";
   		$return .= "\t\t<div style='width:100px;float:left;text-align:center;'>".JText::_("COM_RBIDS_VERIFIED").":<br />".($isVerified?JText::_("COM_RBIDS_YES"):JText::_("COM_RBIDS_NO"))."</div>\n";
   		$return .= "\t\t<div style='width:100px;float:left;text-align:center;'>".JText::_("COM_RBIDS_POWERSELLER").":<br />".($isPowerseller?JText::_("COM_RBIDS_YES"):JText::_("COM_RBIDS_NO"))."</div>\n";
		$return .= "\t\t</div></td></tr>\n";
		$return .= "\t\t<tr><td colspan=4>\n";

		$keys=array_keys($tasklist);

   		$return .= "<table width='100%'><tr>";
   		for ($i=0;$i<count($keys)/2;$i++){
   		    $f_task =  JROUTE::_("index.php?option=com_rbids&task=".$keys[$i]."&Itemid=".RBidsHelperRoute::getItemid(array('task'=>$keys[$i])));
   		    $return .= "<td width='100' align='center'><a href='$f_task'><img src='".JUri::root()."/components/com_rbids/images/menu/".$tasklist[$keys[$i]]."' border=0></a></td>";
   		}
   		$return .= "</tr><tr>";
   		for ($i=count($keys)/2;$i<count($keys);$i++){
   		    $f_task=JROUTE::_("index.php?option=com_rbids&task=".$keys[$i]."&Itemid=".RBidsHelperRoute::getItemid(array('task'=>$keys[$i])));
   		    $return .= "<td width='100' align='center'><a href='$f_task'><img src='".JUri::root()."/components/com_rbids/images/menu/".$tasklist[$keys[$i]]."' border=0></a></td>";
   		}
		$return .= "\t\t</tr></table></td></tr>\n";

	
        $return	.= '<tr>';
        $return .= '<th class="list_ratings_header" colspan=4><hr></th>';
        $return .= '</tr>';
        $return	.= '<tr>';
        $return .= '<th class="list_ratings_header" colspan=4>'.
        JText::_('COM_RBIDS_CURRENT_BALANCE').': '.($balance?($balance->balance." ".$balance->currency):"0 ".$default_currency->name);
        $return .= '&nbsp <a href="'.RBidsHelperRoute::getAddFundsRoute().'">'.JText::_('COM_RBIDS_ADD_FUNDS_TO_YOUR_BALANCE').'</a></th>';
        $return .= '</tr>';
        $return	.= '<tr>';
        $return .= '<th class="list_ratings_header" colspan=4><a href="'.RBidsHelperRoute::getPaymentsHistoryRoute().'">'.JText::_("COM_RBIDS_SEE_MY_PAYMENTS_HISTORY").'</a></th>';
        $return .= '</tr>';
        
		$return .= "</table>";
		$return .= "</div>";

		//$return ="";
		return $return;
	}
}
?>

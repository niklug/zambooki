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
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');

/**
 * @package		RBids
 */
class rbidsModelRatings extends JModel
{
	var $_name='ratings';
	var $name='ratings';
    
    function canRate($userid,$auction_id)
    {
        $db=&$this->getDBO();
		$db->setQuery("SELECT COUNT(*) as c FROM #__rbid_rate WHERE voter ='{$userid}' and auction_id = '$auction_id' ");
		return !($db->loadResult()>0);
        
    }
    function getUserRatings($userid)
    {
		
        $db=&$this->getDBO();
		$db->setQuery("SELECT sum(rating) as rating, ".
            "count(rating) as count FROM `#__rbid_rate` WHERE user_rated ='{$userid}' and rate_type='bidder' ");
        $r1 = $db->loadObject();
        
        $result["rating_bidder"]=round(($r1->count>0)?($r1->rating/$r1->count):0,1);
        $result["count_bidder"]=$r1->count;

		$db->setQuery("SELECT sum(rating) as rating, ".
            "count(rating) as count FROM `#__rbid_rate` WHERE user_rated ='{$userid}' and rate_type='auctioneer' ");
        $r2 = $db->loadObject();
        
        $result["rating_bidder"]=round(($r2->count>0)?($r2->rating/$r2->count):0,1);
        $result["count_bidder"]=$r2->count;
        
        $result["rating_overall"]=round(($r1->count+$r2->count>0)?(($r1->rating+$r2->rating)/($r1->count+$r2->count)):0,1);
        $result["count_overall"]=($r1->count+$r2->count);

        return $result;   
    }
    function getRatingsList($userid)
    {
        $db=&$this->getDBO();
		$query = "select r.*,u.username,a.title from #__rbid_rate r
			  left join #__users u on r.voter = u.id
			  left join #__rbid_auctions a on r.auction_id = a.id
	    	  where r.user_rated = '{$userid}'
		";
		$db->setQuery($query);
		return $db->loadObjectList();
        
    }
	
}

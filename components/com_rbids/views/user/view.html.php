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

jimport( 'joomla.application.component.view');

class rbidsViewUser extends RBidsSmartyView
{
	function display($tpl = null)
	{

		JHTML::_("behavior.modal");
        JHTML::script("ratings.js",'components/com_rbids/js/');
		switch($tpl){
			case "t_myuserdetails.tpl":
        		JHTML::_('behavior.formvalidation');
        		JHTML::script("auction_edit.js",'components/com_rbids/js/');
            break;
			case "t_search_users.tpl":
        		JHTML::_("behavior.calendar");
                JHTML::script("joomla.javascript.js","includes/js/");
            break;
		}
        parent::display($tpl);
	}
	
}
?>

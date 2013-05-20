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

class rbidsViewSearch extends RBidsSmartyView
{

	function display($tmpl)
    {

		JHTML::_("behavior.calendar");
		JHTML::script("joomla.javascript.js","includes/js/");
        JHTML::script('date.js','components/com_rbids/js/');
	    parent::display($tmpl);
		
	}
}

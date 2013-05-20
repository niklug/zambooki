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

class rbidsViewRatings extends RBidsSmartyView
{
    function display($tmpl)
    {
		JHTML::_("behavior.modal");
		JHTML::script("ratings.js",'components/com_rbids/js/');
		$this->smarty->assign("page_title" , JText::_('COM_RBIDS_RATE_AUCTION'));
		parent::display($tmpl);
    }

}

?>

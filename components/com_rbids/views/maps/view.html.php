<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');


	jimport('joomla.application.component.view');

	class rbidsViewMaps extends RBidsSmartyView
	{
		function display($tmpl)
		{

			JHTML::_("behavior.mootools");

			$jdoc = & JFactory::getDocument();
			$js_declaration = " window.addEvent('domready', function(){load_gmaps(); } ); ";
			$jdoc->addScriptDeclaration($js_declaration);

			$this->assign("page_title", JText::_("COM_RBIDS_LIST_AUCTIONS"));

			parent::display($tmpl);
		}

	}

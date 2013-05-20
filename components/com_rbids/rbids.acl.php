<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JRBidsACL
	{

		/* Unmapped tasks are considered Allowed for all */
		// !!!! Tasks are Case Sensitive !!!!
		public $taskmapping = array(
			/* Seller tasks */
			"myauctions" => "seller",
			"newauction" => "seller",
			"form" => "seller",
			"accept" => "seller",
			"save" => "seller",
			"cancelauction" => "seller",
			"editauction" => "seller",
			"republish" => "seller",
			"set_featured" => "seller",
			"buy_listing" => "seller",
			"downloadusernda" => "seller",

			/* Buyer/Bidder tasks */
			"mybids" => "bidder",
			"sendbid" => "bidder"

		);
		// !!!! Tasks are Case Sensitive !!!!
		public $publicTasks = array(
			"viewbids",
			"details",
			"listauctions",
			"search",
			'show_search',
			"showsearchresults",
			"showSearchResults",
			"categories",
			"tree",
			"googlemap",
			"googlemaps",
			"searchusers",
			"showusers",
			"searchusers",
			"showusers",
			"searchBookmarks",
			"showBookmarks",
			"tags",
			"withdrawcancel",
			"withdrawreturning"

		);
		// !!!! Tasks are Case Sensitive !!!!
		public $anonTasks = array(
			"userdetails",
			"saveuserdetails",
			"saveUserDetails",
			"googlemap_tool",

			"showrateauction"

		);


		public function __construct()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			if ($cfg->allow_guest_messaging == 1) {
				array_push($this->publicTasks, "savemessage");
			}
		}

	} // End Class

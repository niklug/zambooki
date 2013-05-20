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

	class RBidsHelperLinksAuction
	{
		/**
		 * getLinks
		 *
		 * @param $auction
		 *
		 * @return array
		 */
		function getLinks($auction)
		{
			static $links;
			if (!$auction->id) return array();
			if (isset($links[$auction->id])) return $links[$auction->id];

			$links[$auction->id] = array();
			$links[$auction->id]['otherauctions'] = RBidsHelperRoute::getAuctionListRoute(array("userid" => $auction->userid));
			$links[$auction->id]['auctions_listing'] = RBidsHelperRoute::getAuctionListRoute();
			$links[$auction->id]['bids'] = RBidsHelperRoute::getAuctionDetailRoute($auction->id);
			$links[$auction->id]['edit'] = RBidsHelperRoute::getAuctionEditRoute($auction->id);
			$links[$auction->id]['cancel'] = RBidsHelperRoute::getAuctionCancelRoute($auction->id);
			$links[$auction->id]['filter_cat'] = RBidsHelperRoute::getAuctionListRoute(array("cat" => $auction->cat));
			$links[$auction->id]['republish'] = RBidsHelperRoute::getAuctionRepublishRoute($auction->id);

			$links[$auction->id]['add_to_watchlist'] = RBidsHelperRoute::getAddToWatchlistRoute($auction->id);
			$links[$auction->id]['del_from_watchlist'] = RBidsHelperRoute::getDelFromWatchlistRoute($auction->id);
			$links[$auction->id]['auctioneer_profile'] = RBidsHelperRoute::getUserdetailsRoute($auction->userid);

			$links[$auction->id]['report'] = RBidsHelperRoute::getReportAuctionRoute($auction->id);

			$links[$auction->id]['new_auction'] = RBidsHelperRoute::getNewAuctionRoute();
			$links[$auction->id]['bulkimport'] = RBidsHelperRoute::getBulkImportRoute();
			$links[$auction->id]['terms'] = RBidsHelperRoute::getTermsAndConditionsRoute();

			$links[$auction->id]['download_file'] = RBidsHelperRoute::getDownloadFileRoute($auction->id, 'attach');
			$links[$auction->id]['download_nda'] = RBidsHelperRoute::getDownloadFileRoute($auction->id, 'nda');
			$links[$auction->id]['deletefile_file'] = RBidsHelperRoute::getDeleteFileRoute($auction->id, 'attach');
			$links[$auction->id]['deletefile_nda'] = RBidsHelperRoute::getDeleteFileRoute($auction->id, 'nda');

			$links[$auction->id]['tags'] = '';

			$tags = $auction->get('tags');

			if (!is_array($tags)) $tags = explode(',', $tags);
			for ($i = 0; $i < count($tags); $i++)

				if ($tags[$i]) {
					$href = RBidsHelperRoute::getTagsRoute($tags[$i]);
					$links[$auction->id]['tags'] .= "<span class='auction_tag'><a href='$href'>" . $tags[$i] . "</a>" . (($i + 1 < count($tags)) ? "," : "") . "</span>";
				}
			return $links[$auction->id];
		}

		/**
		 * get
		 *
		 * @param $auction
		 * @param $params
		 *
		 * @return string
		 */
		function get($auction, $params)
		{
			$linktype = $params;
			if (is_array($params)) $linktype = $params[0];
			if (!$linktype) {
				if (JDEBUG)
					JError::raiseNotice(113, JText::sprintf("Link Type '%s' is unknown!", $linktype));
				return "";

			}
			$links = self::getLinks($auction);
			if (isset($links[$linktype]))
				return $links[$linktype];
			else {
				if (JDEBUG)
					JError::raiseNotice(113, JText::sprintf("Link Type '%s' is unknown!", $linktype));
				return "";

			}
		}

	}

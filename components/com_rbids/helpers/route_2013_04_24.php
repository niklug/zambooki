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

	jimport('joomla.application.component.helper');

	class RBidsHelperRoute
	{
		/**
		 * @param null $needles
		 *
		 * @return string
		 */
		static function getItemid($needles = null)
		{
			$Itemid = JRequest::getInt('Itemid');
			if (!$Itemid) $Itemid = RBidsHelperTools::getMenuItemId($needles);

			if ($Itemid) return "&Itemid=" . $Itemid;

			return "";
		}

		/**
		 * @param      $auctionid
		 * @param null $anchor
		 * @param bool $xhtml
		 *
		 * @return string
		 */
		static function getAuctionDetailRoute($auctionid, $anchor = null, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=details&id={$auctionid}" . self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml) . $anchor;
		}

		/**
		 * @param      $auctionid
		 * @param      $userid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getShowRateAuctionRoute($auctionid, $userid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=ratings&task=showrateauction&user_rated={$userid}&id={$auctionid}" . self::getItemid(array('task' => 'UserProfile'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param null $userid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getUserRatingsRoute($userid = null, $xhtml = true)
		{
			if ($userid)
				$link = "index.php?option=com_rbids&controller=ratings&task=userratings&id={$userid}" . self::getItemid(array('task' => 'UserProfile'));
			else
				$link = "index.php?option=com_rbids&controller=ratings&task=myratings" . self::getItemid(array('task' => 'userdetails'));
			return JRoute::_($link, $xhtml);
		}


		/**
		 * @param null   $catid
		 * @param string $task
		 * @param null   $catslug
		 * @param string $filter_letter
		 * @param bool   $xhtml
		 *
		 * @return The
		 */
		static function getCategoryRoute($catid = null, $filter_letter = 'all', $task = 'categories', $catslug = null, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task={$task}" . (($catid) ? ("&cat={$catid}") : ("")) . "&filter_letter={$filter_letter}" . $catslug . self::getItemid(array('task' => 'categories'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param null $filters
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAuctionListRoute($filters = null, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=listauctions";
			if (is_array($filters)) {
				foreach ($filters as $k => $v)
					$link .= "&$k=$v";
			} elseif ($filters) $link .= $filters;
			$link .= self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param null $userid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getOtherAuctionListRoute($userid = null, $xhtml = true)
		{
			if ($userid) {
				$link = "index.php?option=com_rbids&task=listauctions&userid={$userid}";
			} else {
				$link = "index.php?option=com_rbids&task=listauctions";
			}

			$link .= self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $tag
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getTagsRoute($tag, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=tags&tag={$tag}";
			$link .= self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);

		}

		/**
		 * @param      $catid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAddToCatWatchlist($catid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=watchlist&task=addwatchcat&cat={$catid}" . self::getItemid(array('task' => 'categories'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $catid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getDelToCatWatchlist($catid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=watchlist&task=delwatchcat&cat={$catid}" . self::getItemid(array('task' => 'categories'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $id
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAuctionEditRoute($id, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=editauction&id=$id" . self::getItemid(array('task' => 'editauction', 'task' => 'newauction', 'task' => 'form'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $id
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAuctionCancelRoute($id, $xhtml = true)
		{ //cancelauction
			$link = "index.php?option=com_rbids&task=cancelauction&id=$id" . self::getItemid(array('task' => 'editauction', 'task' => 'newauction', 'task' => 'form'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $id
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAuctionRepublishRoute($id, $xhtml = true)
		{ //cancelauction
			$link = "index.php?option=com_rbids&task=republish&id=$id" . self::getItemid(array('task' => 'editauction', 'task' => 'newauction', 'task' => 'form'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getNewAuctionRoute($xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=form" . self::getItemid(array('task' => 'editauction', 'task' => 'newauction', 'task' => 'form'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $catid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getNewAuctionInCategoryRoute($catid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=form&category=" . $catid . self::getItemid(array('task' => 'editauction', 'task' => 'newauction', 'task' => 'form'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param null $userid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getUserdetailsRoute($userid = null, $xhtml = true)
		{
			if (!$userid)
				$link = "index.php?option=com_rbids&controller=user&task=userdetails" . self::getItemid(array('task' => 'userdetails'));
			else
				$link = "index.php?option=com_rbids&controller=user&task=UserProfile&id=$userid" . self::getItemid(array('task' => 'UserProfile', 'task' => 'userdetails'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param null $userid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getUserInvitedAuctionsRoute($userid = null, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=myinvitedauctions" . self::getItemid(array('task' => 'userdetails'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getTermsAndConditionsRoute($xhtml = true)
		{
			$link = 'index.php?option=com_rbids&task=terms_and_conditions&format=raw';
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $auctionid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAddToWatchlistRoute($auctionid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=watchlist&task=addwatchlist&id={$auctionid}" . self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $auctionid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getDelFromWatchlistRoute($auctionid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=watchlist&task=delwatch&id={$auctionid}" . self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $auctionid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getReportAuctionRoute($auctionid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=report_auction&id={$auctionid}" . self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getBulkImportRoute($xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=bulkimport" . self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param        $auctionid
		 * @param string $filetype
		 * @param bool   $xhtml
		 *
		 * @return The
		 */
		static function getDownloadFileRoute($auctionid, $filetype = 'attach', $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=attachements&task=downloadfile&id={$auctionid}&file={$filetype}" . self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param        $auctionid
		 * @param string $filetype
		 * @param bool   $xhtml
		 *
		 * @return The
		 */
		static function getDeleteFileRoute($auctionid, $filetype = 'delete', $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=attachements&task=deletefile&id={$auctionid}&file={$filetype}" . self::getItemid(array('task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getSelectCategoryRoute($xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=selectcat" . self::getItemid(array('task' => 'form', 'task' => 'editauction', 'task' => 'new'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $orderid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getCheckoutRoute($orderid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=orderprocessor.checkout&orderid=$orderid" . self::getItemid(array('task' => 'form', 'task' => 'editauction', 'task' => 'new'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAddFundsRoute($xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=payments.history" . self::getItemid(array('task' => 'userdetails', 'controller' => 'user'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getPaymentsHistoryRoute($xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=payments.history" . self::getItemid(array('task' => 'userdetails', 'controller' => 'user'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $auctionid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getFeaturedRoute($auctionid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=setfeatured&id=" . $auctionid . self::getItemid(array('task' => 'viewbids', 'task' => 'details'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $bidid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getAcceptBid($bidid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&task=accept&bid={$bidid}" . self::getItemid(array('task' => 'viewbids', 'task' => 'details', 'task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $auctionid
		 * @param      $userid
		 * @param bool $xhtml
		 *
		 * @return The
		 */
		static function getDownloadUserNDA($auctionid, $userid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=attachements&task=downloadusernda&id={$auctionid}&uid={$userid}" . self::getItemid(array('task' => 'viewbids', 'task' => 'details', 'task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}

		/**
		 * @param      $bidid
		 * @param      $auctionid
		 * @param bool $xhtml
		 *
		 * @internal param $userid
		 * @return The
		 */
		static function getDownloadBidAttach($bidid, $auctionid, $xhtml = true)
		{
			$link = "index.php?option=com_rbids&controller=attachements&task=downloadbidattach&id={$bidid}&auctionid={$auctionid}" . self::getItemid(array('task' => 'viewbids', 'task' => 'details', 'task' => 'listauctions'));
			return JRoute::_($link, $xhtml);
		}
	}




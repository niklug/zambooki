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

	class AttachementsController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		function downloadFile()
		{
			$id = JRequest::getInt('id');

			@set_time_limit(0);

			$auction =& JTable::getInstance('auctions', 'Table');
			if (!$auction->load($id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				return;
			}

			$filetype = JRequest::getVar('file', 'attach');

			if ($filetype == 'nda') {
				$name = $auction->NDA_file;
				$filepath = AUCTION_UPLOAD_FOLDER . $auction->id . ".nda";
			} else {
				$name = $auction->file_name;
				$filepath = AUCTION_UPLOAD_FOLDER . $auction->id . ".attach";
			}

			if (!file_exists($filepath)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_FILE_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($id));
				return;
			}

			ob_clean();
			@ignore_user_abort(true);
			@set_time_limit(0);

			header("Pragma: public");
			header("Expires: 0"); // set expiration time
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=\"" . $name . "\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-size: " . filesize($filepath));
			header("Content-Length: " . filesize($filepath));

			if ($stream = fopen($filepath, 'rb')) {
				while (!feof($stream) && connection_status() == 0)
					echo(fread($stream, 1024 * 4));
				fclose($stream);
				exit;
			} else {
				JError::raiseWarning(510, JText::_("COM_RBIDS_ERROR_READING_FILE"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($id));
				return;
			}
		}

		function downloadUserNda()
		{

			jimport('joomla.filesystem.file');
			$database = &JFactory::getDbo();
			$my = &JFactory::getUser();
			$id = JRequest::getInt('id');
			$uid = JRequest::getInt('uid');

			@set_time_limit(0);

			$auction =& JTable::getInstance('auctions', 'Table');
			if (!$auction->load($id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				return;
			}

			$name = "auct{$id}-{$uid}.fil";
			$filepath = AUCTION_UPLOAD_FOLDER . $name;

			if (!file_exists($filepath)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_FILE_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($id));
				return;
			}

			$database->setQuery('SELECT * FROM #__rbid_attachments WHERE auctionId=' . $database->Quote($id) . ' AND userid=' . $database->Quote($uid) . ' ');
			$attachment = $database->loadObject();
			$fileName = $attachment->fileName . '.' . $attachment->fileExt;

			ob_clean();
			@ignore_user_abort(true);
			@set_time_limit(0);

			header("Pragma: public");
			header("Expires: 0"); // set expiration time
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-size: " . filesize($filepath));
			header("Content-Length: " . filesize($filepath));

			if ($stream = fopen($filepath, 'rb')) {
				while (!feof($stream) && connection_status() == 0)
					echo(fread($stream, 1024 * 4));
				fclose($stream);
				exit;
			} else {
				JError::raiseWarning(510, JText::_("COM_RBIDS_ERROR_READING_FILE"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($id));
				return;
			}
		}

		function downloadBidAttach()
		{

			jimport('joomla.filesystem.file');
			$database = &JFactory::getDbo();
			$my = &JFactory::getUser();
			// Bid id
			$id = JRequest::getInt('id');
			// Auction id
			$auctionId = JRequest::getInt('auctionid');

			@set_time_limit(0);

			$auction =& JTable::getInstance('auctions', 'Table');
			if (!$auction->load($auctionId)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				return;
			}

			$name = "bid{$id}-auct{$auctionId}.attach";
			$filepath = AUCTION_UPLOAD_FOLDER . $name;

			if (!file_exists($filepath)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_FILE_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auctionId));
				return;
			}

			$database->setQuery("SELECT * FROM `#__rbids` WHERE `auction_id` = " . $database->Quote($auctionId) . " AND `id` = " . $database->Quote($id));
			$attachment = $database->loadObject();
			$fileName = $attachment->file_name;

			ob_clean();
			@ignore_user_abort(true);
			@set_time_limit(0);

			header("Pragma: public");
			header("Expires: 0"); // set expiration time
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-size: " . filesize($filepath));
			header("Content-Length: " . filesize($filepath));

			if ($stream = fopen($filepath, 'rb')) {
				while (!feof($stream) && connection_status() == 0)
					echo(fread($stream, 1024 * 4));
				fclose($stream);
				exit;
			} else {
				JError::raiseWarning(510, JText::_("COM_RBIDS_ERROR_READING_FILE"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auctionId));
				return;
			}
		}



		function deletefile()
		{

			$id = JRequest::getInt('id');
			$type = JRequest::getCmd('file');
			jimport('joomla.filesystem.file');

			$rauction = JTable::getInstance('auctions', 'table');
			$rauction->load($id);

			$fileName = '';
			switch ($type) {
				case 'nda':
					$rauction->NDA_file = '';
					$fileName = AUCTION_UPLOAD_FOLDER . DS . JFile::makeSafe($id . '.' . $type);
					break;
				case 'attach':
					$rauction->file_name = '';
					$fileName = AUCTION_UPLOAD_FOLDER . DS . JFile::makeSafe($id . '.' . $type);
					break;
			}

			$rauction->store();

			if (JFile::exists($fileName)) {
				JFile::delete($fileName);
			}

			$this->setRedirect(JRoute::_('index.php?option=' . APP_EXTENSION . '&task=editauction&id=' . $id, false));
		}
	}

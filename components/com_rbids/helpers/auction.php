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
	class RBidsHelperAuction
	{
		/**
		 * getOrderItemsForAuction
		 *
		 * @static
		 *
		 * @param      $auctionid
		 * @param null $itemname
		 *
		 * @return mixed
		 */
		static function getOrderItemsForAuction($auctionid, $itemname = null)
		{
			$db =& JFactory::getDbo();
			$db->setQuery("select * from `#__rbid_payment_orderitems` oi
                        left join `#__rbid_payment_orders` o on oi.orderid=o.id
                        where iteminfo='{$auctionid}'
                        " . ($itemname ? " and itemname='$itemname'" : ""));
			return $db->loadObjectList();
		}

		/**
		 * getCSVData
		 *
		 * @static
		 *
		 * @param $fieldname
		 * @param $data
		 * @param $header
		 *
		 * @return string
		 */
		static function getCSVData($fieldname, $data, $header)
		{
			$fieldname = strtolower($fieldname);
			$header = array_change_key_case($header, CASE_LOWER);
			if (isset($header[$fieldname]) && isset($data[$header[$fieldname]]))
				return trim($data[$header[$fieldname]]);
			else
				return "";
		}

		/**
		 * moveTempImage
		 *
		 * @static
		 *
		 * @param $imagename
		 * @param $new_imagename
		 *
		 * @return array
		 */
		static function moveTempImage($imagename, $new_imagename)
		{
			$errors = array();
			if (!file_exists($imagename)) {
				$errors[] = JText::_("COM_RBIDS_IMAGE_MISSING_FROM_ARCHIVE") . ": " . JFile::getName($imagename);
				return $errors;
			}
			$ext = JFile::getExt($imagename);
			if (!TableAuctions::isAllowedImage($ext)) {
				$errors[] = JText::_("COM_RBIDS_USUPPORTED_IMAGE_EXTENSION") . ": " . JFile::getName($imagename);
				return $errors;
			}
			$cfg = JTheFactoryHelper::getConfig();
			if (filesize($imagename) > $cfg->max_picture_size * 1024) {
				$errors[] = JText::_("COM_RBIDS_IMAGE_IS_TOO_LARGE") . ": " . JFile::getName($imagename);
				return $errors;
			}
			$imgTrans = new JTheFactoryImages();
			if (file_exists($new_imagename))
				JFile::delete($new_imagename);
			if (JFile::move($imagename, $new_imagename)) {
				$imgTrans->resize_image($new_imagename, $cfg->thumb_width, $cfg->thumb_height, 'resize');
				$imgTrans->resize_image($new_imagename, $cfg->medium_width, $cfg->medium_height, 'middle');
			} else {
				$errors[] = JText::_("COM_RBIDS_CAN_NOT_MOVE") . ": " . JFile::getName($imagename);
			}
			return $errors;
		}

		/**
		 * ImportFromCSV
		 *
		 * @static
		 * @return array
		 */
		static function ImportFromCSV()
		{
			jimport('joomla.filesystem.path');

			$database = JFactory::getDBO();
			$my = JFactory::getUser();
			$cfg = JTheFactoryHelper::getConfig();
			$app = JFactory::getApplication();
			$config = JFactory::getConfig();
			@set_time_limit(0);

			jimport('joomla.filesystem.path');
			jimport('joomla.filesystem.file');
			require_once(JPATH_COMPONENT_SITE . DS . 'thefactory' . DS . 'front.images.php');

			$auction = JTable::getInstance('auctions', 'Table');
			$tags = JTable::getInstance('tags', 'Table');
			$imgtable = & JTable::getInstance('pictures', 'Table');

			$errors = array();

			if (!isset($_FILES['csv']['tmp_name']) || !is_uploaded_file($_FILES['csv']['tmp_name'])) {
				$errors[] = JText::_("COM_RBIDS_UPLOAD_A_CSV_FILE");
				return $errors;
			}

			$csv_fname = $_FILES['csv']['name'];
			$ext = JFile::getExt($csv_fname);
			if (!in_array(strtolower(JFile::getExt($csv_fname)), array("csv", "txt"))) {
				$errors[] = JText::_("COM_RBIDS_EXTENSION_NOT_ALLOWED");
				return $errors;
			}

			$csv_fname = $config->get('tmp_path') . DS . $csv_fname;

			if (!JFile::upload($_FILES['csv']['tmp_name'], $csv_fname)) {
				$errors[] = JText::_("COM_RBIDS_ERROR_UPLOADING_CSV_FILE");
				return $errors;
			}

			if (isset($_FILES['arch']['tmp_name']) && $_FILES['arch']['tmp_name']) {
				$tmp_imgpath = JPath::clean($config->get('tmp_path') . DS . md5(microtime() . rand(0, 100)));
				JFolder::create($tmp_imgpath);
				jimport('joomla.filesystem.archive');
				$zip = JArchive::getAdapter('zip');

				if (($res = $zip->extract($_FILES['arch']['tmp_name'], $tmp_imgpath)) !== TRUE) {
					$errors[] = JText::_("COM_RBIDS_ERROR_EXTRACTING_ZIP") . ": " . $res;
				}
			}

			$handle = fopen($csv_fname, "r");

			//Read CSV Header
			$header = fgetcsv($handle, 30000, "\t");
			if (is_array($header)) $header = array_flip($header);

			while (($data = fgetcsv($handle, 30000, "\t")) !== FALSE) {
				$auction->id = null;
				$auction->title = JFilterOutput::cleanText(self::getCSVData("title", $data, $header));
				$auction->shortdescription = JFilterOutput::cleanText(self::getCSVData("shortdescription", $data, $header));
				$auction->description = preg_replace('/<script[^>]*?>.*?<\/script>/si', '', self::getCSVData("description", $data, $header));
				$auction->userid = self::getCSVData("userid", $data, $header);
				if (!$auction->userid) $auction->userid = $my->id;
				$auction->published = 1;
				$auction->max_price = self::getCSVData("maxprice", $data, $header);
				$auction->currency = self::getCSVData("currency", $data, $header);
				$auction->start_date = self::getCSVData("start_date", $data, $header);
				$auction->end_date = self::getCSVData("end_date", $data, $header);
				$auction->picture = self::getCSVData("picture", $data, $header);
				$auction->cat = self::getCSVData("category", $data, $header);
				$auction->store(true);

				$tag = self::getCSVData("tags", $data, $header);
				if ($tag)
					$tags->setTags($auction->id, $tag);

				$nrimages = 0;
				$images = self::getCSVData("images", $data, $header);
				$arr_pics = explode(",", $images);
				if ($auction->picture) {
					$e = self::moveTempImage($tmp_imgpath . DS . $auction->picture,
						AUCTION_PICTURES_PATH . DS . "main_{$auction->id}." . JFile::getExt($auction->picture));
					if (count($e)) {
						$auction->picture = ''; //remove main pic
						$errors = array_merge($errors, $e);
					} else {
						$auction->picture = "main_{$auction->id}." . JFile::getExt($auction->picture);
						$nrimages++;
					}
				}
				$auction->store();

				foreach ($arr_pics as $image) {
					if ($nrimages >= $cfg->maxnr_images)
						continue;
					$image = trim($image);
					$file_name = "img_{$auction->id}_{$nrimages}." . JFile::getExt($image);
					$e = self::moveTempImage($tmp_imgpath . DS . $image,
						AUCTION_PICTURES_PATH . DS . $file_name);
					if (count($e)) {
						$errors = array_merge($errors, $e);
					} else {
						$imgtable->id = null;
						$imgtable->auction_id = $auction->id;
						$imgtable->userid = $my->id;
						$imgtable->picture = $file_name;
						$imgtable->modified = gmdate('Y-m-d H:i:s');
						$imgtable->store();
						$nrimages++;
					}
				}
			}
			fclose($handle);

			if (JFolder::exists($tmp_imgpath))
				JFolder::delete($tmp_imgpath);
			if (file_exists($csv_fname))
				JFile::delete($csv_fname);

			return $errors;
		}

	}


?>

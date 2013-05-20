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
	 * @subpackage: CBPlugins
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	jimport('joomla.application.component.model');
	jimport('joomla.application.component.helper');

	class rbidsModelRbid extends JModel
	{

		var $_name = 'rbid';
		var $name = 'rbid';

		/**
		 * @param $auct
		 */
		public function bindAuction(&$auct)
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$task = JRequest::getCmd('task');
			$auct->id = JRequest::getVar('id', null);
			$auct->load($auct->id);
			$isAdmin = JRequest::getInt('isAdmin', 0);

			$auct->bind($_REQUEST);
			$auct->cat = JRequest::getInt("cat");
			$auct->shortdescription = JRequest::getVar('shortdescription', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$auct->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$auct->tags = JRequest::getVar("tags");

			$customFieldsList = CustomFieldsFactory::getFieldsList('auctions');

			// Dates Custom Fields are saved in Unix timestamp
			foreach ($customFieldsList as $cfield) {
				if ('date' == $cfield->ftype) {

					if (empty($auct->{$cfield->db_name})) {
						continue;
					}
					// ToDo Factory: Refactor next 3 lines to use JForm
					$params = explode("\n", $cfield->params);
					$param = explode("=", $params[1]);
					$date_format = $param[1];
					// Var to save in db
					$auct->{$cfield->db_name} = strtotime(
						RBidsHelperDateTime::DateToIso(
						// Var from _POST
							$auct->{$cfield->db_name},
							$date_format
						)
					);

				}
			}

			// edit custom field prep
			if ($auct->id) {

				if ($auct->published && !$isAdmin) {
					$old_auction =& JTable::getInstance('auctions', 'Table');
					$old_auction->load($auct->id);
					// do not edit these fields
					$auct->start_date = $old_auction->start_date;
					$auct->end_date = $old_auction->end_date;

					if (!$cfg->auctionpublish_enable)
						$auct->published = $old_auction->published;
					$auct->auction_type = $old_auction->auction_type;
					$auct->title = $old_auction->title;
					$auct->approved = $old_auction->approved;
				} else {

					$end_hour = JRequest::getVar('end_hour', '00');
					$end_minutes = JRequest::getVar('end_minutes', '00');

					$auct->end_date = RBidsHelperDateTime::DateToIso($auct->end_date);
					if ($cfg->enable_hour == 1 && !$isAdmin) {
						$auct->end_date .= " $end_hour:$end_minutes";
					}

					$auct->start_date = RBidsHelperDateTime::DateToIso($auct->start_date);

					$auct->start_date = RBidsHelperDateTime::isoDateToUTC($auct->start_date); //store all in UTC
					$auct->end_date = RBidsHelperDateTime::isoDateToUTC($auct->end_date); //store all in UTC
				}

				if ($auct->file_name != "")
					$this->file_name = $auct->file_name;

				$this->attachment = $auct->file_name;
				if (isset($_FILES["attachment"])) {
					$this->attachment = null;
					if ($cfg->enable_attach && is_uploaded_file(@$_FILES["attachment"]['tmp_name']))
						$this->attachment = $_FILES["attachment"];
				}
				$this->NDA_file = $auct->NDA_file;
				if (isset($_FILES["NDA_file"])) {
					$this->NDA_file = null;
					if ($cfg->nda_option && is_uploaded_file(@$_FILES["NDA_file"]['tmp_name']))
						$this->NDA_file = $_FILES["NDA_file"];
				}

			} else {

				// new custom field prep

				$end_hour = JRequest::getVar('end_hour', '00');
				$end_minutes = JRequest::getVar('end_minutes', '00');

				$auct->end_date = RBidsHelperDateTime::DateToIso($auct->end_date);
				if ($cfg->enable_hour == 1) {
					$auct->end_date .= " $end_hour:$end_minutes";
				}

				$auct->start_date = RBidsHelperDateTime::DateToIso($auct->start_date);

				$auct->start_date = RBidsHelperDateTime::isoDateToUTC($auct->start_date); //store all in UTC
				$auct->end_date = RBidsHelperDateTime::isoDateToUTC($auct->end_date); //store all in UTC


				$user = &JFactory::getUser();
				$auct->userid = $user->id;

				$this->attachment = null;
				if (isset($_FILES["attachment"])) {
					if ($cfg->enable_attach && is_uploaded_file(@$_FILES["attachment"]['tmp_name']))
						$this->attachment = $_FILES["attachment"];
				}


				$this->NDA_file = null;
				if (isset($_FILES["NDA_file"])) {
					if ($cfg->nda_option && is_uploaded_file(@$_FILES["NDA_file"]['tmp_name']))
						$this->NDA_file = $_FILES["NDA_file"];
				}
				if (!$cfg->auctionpublish_enable)
					$auct->published = $cfg->auctionpublish_val;
				if (!$cfg->auctiontype_enable)
					$auct->auction_type = $cfg->auctiontype_val;
			}

		}

		/**
		 * @param $auct
		 *
		 * @return array
		 */
		public function saveAuction($auct)
		{
			$isNew = !(bool)$auct->id;

			$database = &JFactory::getDBO();
			$cfg =& JTheFactoryHelper::getConfig();

			$error = $this->validateSave($auct);
			if (count($error) > 0) return $error;

			$tags = $auct->tags;
			unset($auct->tags);
			if ($cfg->admin_approval) {
				if (!$auct->id)
					$auct->approved = 0;
			} else
				$auct->approved = 1;

			// Save auction HERE
			if (!$e = $auct->store()) {
				$error[] = $e;
				return $error;
			}

			// TAGS
			$tag_obj =& JTable::getInstance('tags', 'Table');
			$tag_obj->setTags($auct->id, $tags);

			$this->deleteFiles($auct);

			// UPLOAD FILES
			$msg = $this->uploadFiles($auct);

			if (strlen($msg)) {
				$error[] = $msg;
			}

			$this->uploadAtachment($auct);
			$this->uploadNDA_file($auct);

			if (AUCTION_TYPE_INVITE == $auct->auction_type) { // Auction type 5
				$this->saveInvites($auct);
			}
			return $error;
		}

		/**
		 * @param $auct
		 *
		 * @return bool
		 */
		public function saveInvites($auct)
		{
			$cfg =& JTheFactoryHelper::getConfig();

			$invited = JRequest::getVar('rbidsCookInvites', '', 'cookie', 'string');
			@list($controlValue, $u, $g) = explode('#', $invited);
			if (!$controlValue) {
				//nothing changed
				return true;
			}
			$invitedUsers = empty($u) ? array() : explode(',', $u);
			$invitedGroups = empty($g) ? array() : explode(',', $g);

			$invites = JModel::getInstance('invites', 'rbidsModel');
			$invites->loadAuction($auct->id);

			//save user invites
			if ('users' == $cfg->aucttype_invite_interface) {
				$invites->save('user', $invitedUsers);
			}
			//save group invites
			if ('groups' == $cfg->aucttype_invite_interface) {
				$invites->save('group', $invitedGroups);
			}

			//save group && users invites
			if ('both' == $cfg->aucttype_invite_interface) {
				$invites->save('user', $invitedUsers);
				$invites->save('group', $invitedGroups);
			}
			//reset cookie
			return setcookie('rbidsCookInvites');
		}

		/**
		 * @param $auction
		 */
		public function deleteFiles($auction)
		{
			$my = JFactory::getUser();
			$database = &JFactory::getDBO();

			$delete_pictures = JRequest::getVar('delete_pictures', null);
			$delete_main_picture = JRequest::getVar('delete_main_picture', null);
			$delete_atachment = JRequest::getVar('delete_atachment', null);

			$id = (int)$auction->id;
			if ($delete_main_picture) {
				$query = "select picture from `#__rbid_auctions` where id='$id'";
				$database->setQuery($query);
				$main_pic = $database->loadResult();

				$query = "update #__rbid_auctions set picture = '' where id = $id";
				$database->setQuery($query);
				$database->query();

				@unlink(AUCTION_PICTURES_PATH . $main_pic);
				@unlink(AUCTION_PICTURES_PATH . "small_" . $main_pic);
				@unlink(AUCTION_PICTURES_PATH . "middle_" . $main_pic);
			}
			if ($delete_atachment) {
				$query = "update #__rbid_auctions set atachment = '' where id = $id";
				$database->setQuery($query);
				$database->query();

				@unlink(AUCTION_UPLOAD_FOLDER . "{$id}.attach");
			}

			if ($delete_pictures)
				foreach ($delete_pictures as $dele_id) {
					$query = "select picture from #__rbid_pictures where id=$dele_id and userid=$my->id";
					$database->setQuery($query);
					$pic = $database->loadResult();

					$query = "delete from #__rbid_pictures where id='$dele_id' and userid='$my->id'";
					$database->setQuery($query);
					$database->query();

					@unlink(AUCTION_PICTURES_PATH . $pic);
					@unlink(AUCTION_PICTURES_PATH . "small_" . $pic);
					@unlink(AUCTION_PICTURES_PATH . "middle_" . $pic);

				}
		}

		/**
		 * @param $auction
		 */
		public function uploadAtachment($auction)
		{

			if (isset($this->attachment)) {

				$file_name = "{$auction->id}.attach";
				$path = AUCTION_UPLOAD_FOLDER . "$file_name";

				if (move_uploaded_file($this->attachment['tmp_name'], $path)) {
					$auction->file_name = $this->attachment["name"];
					$auction->store();
				}
			}
		}

		/**
		 * @param $auction
		 */
		public function uploadNDA_file($auction)
		{

			if (isset($this->NDA_file)) {

				$file_name = "{$auction->id}.nda";

				$path = AUCTION_UPLOAD_FOLDER . "$file_name";

				if (move_uploaded_file($this->NDA_file['tmp_name'], $path)) {
					$auction->NDA_file = $this->NDA_file["name"];
					$auction->store();
				}
			}
		}


		/**
		 * @param $auction
		 * @param $oldid
		 * @param $delete_main_picture
		 * @param $delete_pictures
		 */
		public function moveOldFiles($auction, $oldid, $delete_main_picture, $delete_pictures)
		{

			jimport('joomla.filesystem.file');

			$my = &JFactory::getUser();
			$database = &JFactory::getDBO();
			$database->setQuery("SELECT count(*) FROM #__rbid_auctions WHERE id='$oldid' and userid='$my->id'");
			if (!$database->loadResult()) {
				echo JText::_('COM_RBIDS_ALERTNOTAUTH');
				exit;
			}

			$database->setQuery("SELECT picture FROM #__rbid_auctions WHERE id=$oldid");
			$oldpic = $database->loadResult();

			$new_id = $auction->id;

			if (!empty($oldpic) && !$delete_main_picture) {
				if (file_exists(AUCTION_PICTURES_PATH . $oldpic)) {

					$new_pic_name = "main_{$new_id}." . JFile::getExt($oldpic);
					copy(AUCTION_PICTURES_PATH . $oldpic, AUCTION_PICTURES_PATH . $new_pic_name);
					copy(AUCTION_PICTURES_PATH . "middle_$oldpic", AUCTION_PICTURES_PATH . "middle_$new_pic_name");
					copy(AUCTION_PICTURES_PATH . "resize_$oldpic", AUCTION_PICTURES_PATH . "resize_$new_pic_name");
					$auction->picture = $new_pic_name;
					$auction->store();
				}
			}
			if ($delete_main_picture) {
				$auction->picture = '';
				$auction->store();

			}

			$database->setQuery("SELECT * FROM #__rbid_pictures WHERE auction_id=$oldid");
			$pictures = $database->loadObjectList();
			for ($i = 0; $i < count($pictures); $i++) {
				$ext = JFile::getExt($pictures[$i]->picture);
				if ($auction->isAllowedImage($ext) && !in_array($pictures[$i]->id, $delete_pictures)) {

					if (file_exists(AUCTION_PICTURES_PATH . $pictures[$i]->picture)) {
						$pic = & JTable::getInstance('pictures', 'Table');

						$pic->auction_id = $auction->id;
						$pic->userid = $auction->userid;
						$pic->modified = gmdate('Y-m-d H:i:s');
						$pic->store();
						$pic->picture = $auction->id . "_img_$pic->id.$ext";
						$pic->store();

						copy(AUCTION_PICTURES_PATH . $pictures[$i]->picture, AUCTION_PICTURES_PATH . $pic->
							picture);
						copy(AUCTION_PICTURES_PATH . "middle_" . $pictures[$i]->picture, AUCTION_PICTURES_PATH .
							"middle_" . $pic->picture);
						copy(AUCTION_PICTURES_PATH . "resize_" . $pictures[$i]->picture, AUCTION_PICTURES_PATH .
							"resize_" . $pic->picture);
					}
				}
			}
		}

		/**
		 * @param $auction
		 *
		 * @return string
		 */
		public function uploadFiles(&$auction)
		{
			jimport('joomla.filesystem.file');
			$msg = '';

			$db = & JFactory::getDBO();
			$cfg =& JTheFactoryHelper::getConfig();
                        
                        $CPhotoid = JRequest::getInt('cphotoid');
                        $DelCPhoto = JRequest::getVar('delete_cphoto_picture');
                        
                        $addCPhoto = ($CPhotoid && !$DelCPhoto) ? true: false;
                        
                        
			if ($cfg->disable_images) return null; //No image processing

			require_once(JPATH_COMPONENT_SITE . DS . 'thefactory' . DS . 'front.images.php');
			$imgTrans = new JTheFactoryImages();

			$my = &JFactory::getUser();
			$database = &JFactory::getDBO();
			$oldid = JRequest::getVar('oldid', 0);
			$delete_pictures = JArrayHelper::getValue($_POST, 'delete_pictures', null);

			if (!count($delete_pictures))
				$delete_pictures = array();

			$delete_main_picture = JRequest::getVar('delete_main_picture', '');
			$delete_atachment = JRequest::getVar('delete_atachment', '');

			if (!is_dir(AUCTION_PICTURES_PATH))
				@mkdir(AUCTION_PICTURES_PATH, 0755);

			if ($oldid) {
				//Repost
				$this->moveOldFiles($auction, $oldid, $delete_main_picture, $delete_pictures);
			}
			$nrfiles = 0;

			foreach ($_FILES as $k => $file) {

				if (substr($k, 0, 7) != "picture")
					continue;

				if (!isset($file['name']) || $file['name'] == "")
					continue;

				if (!is_uploaded_file(@$file['tmp_name'])) {
					continue;
				}

				if (filesize(@$file['tmp_name']) > $cfg->max_picture_size * 1024) {
					continue;
				}

				$fname = $file['name'];

				$ext = JFile::getExt($fname);

				if (!$auction->isAllowedImage($ext)) {
					$msg .= JText::_("COM_RBIDS_EXTENSION_NOT_ALLOWED") . ': ' . $file['name'];
					continue;
				}
				if ($k == "picture_0" && (!$auction->picture || $delete_main_picture) && !$addCPhoto) {
					$file_name = "main_{$auction->id}.{$ext}";

					$auction->picture = $file_name;
					$auction->store();
				} else {
					if ($nrfiles >= $cfg->maxnr_images)
						continue;

					$pic = & JTable::getInstance('pictures', 'Table');
					$pic->id = 0;
					$pic->auction_id = $auction->id;
					$pic->userid = $my->id;
					$pic->picture = "temp";
					$pic->modified = gmdate('Y-m-d H:i:s');
					$pic->store();

					$file_name = $auction->id . "_img_$pic->id.$ext";
					$pic->picture = $file_name;
					$pic->store();
					$nrfiles++;
				}

				$path = AUCTION_PICTURES_PATH . "/$file_name";


				$res = move_uploaded_file($file['tmp_name'], $path);

				if ($res) {
					@chmod($path, 0755);

					$s = $imgTrans->resize_image(AUCTION_PICTURES_PATH . $file_name, $cfg->thumb_width, $cfg->thumb_height, 'resize');
					if (!$s) {
						$msg .= $file['name'] . " thumb - " . JText::_('COM_RBIDS_ERR_LOADING_PICTURE') . "<br /><br />";
					}
					$s = $imgTrans->resize_image(AUCTION_PICTURES_PATH . $file_name, $cfg->medium_width, $cfg->medium_height, 'middle');
					if (!$s) {
						$msg .= $file['name'] . " middle - " . JText::_('COM_RBIDS_ERR_LOADING_PICTURE') . "<br /><br />";
					}

				} else {
					$msg .= $file['name'] . "- " . JText::_('COM_RBIDS_ERR_UPLOAD_FAIL') . "<br /><br />";
				}

			}
                        
                        if($addCPhoto){//add CPhoto      
                            $CPhoto = $db->setQuery("SELECT * FROM #__community_photos WHERE id=".$CPhotoid)->loadObject();             
                            $CPhoto_filename = JPATH_BASE . DS . $CPhoto->original; 
                            
                            $ext = JFile::getExt($CPhoto_filename);
                            if (!$auction->isAllowedImage($ext)) {
                                $msg .= JText::_("COM_RBIDS_EXTENSION_NOT_ALLOWED") . ': ' . $file['name'];
                                continue;
                            }
                           
                            $file_name = "main_{$auction->id}.{$ext}";
                            if (!is_dir(AUCTION_PICTURES_PATH)) @mkdir(AUCTION_PICTURES_PATH, 0755);
                            $path = AUCTION_PICTURES_PATH . DS . $file_name;
                            $res = JFile::copy($CPhoto_filename, $path);
                            $auction->picture = $file_name;
                            $auction->store();
                            if($res){
                                    @chmod($path, 0755);

                                    $s = $imgTrans->resize_image(AUCTION_PICTURES_PATH . $file_name, $cfg->thumb_width, $cfg->thumb_height, 'resize');
                                    if (!$s) {
                                            $msg .= $file['name'] . " thumb - " . JText::_('COM_RBIDS_ERR_LOADING_PICTURE') . "<br /><br />";
                                    }
                                    $s = $imgTrans->resize_image(AUCTION_PICTURES_PATH . $file_name, $cfg->medium_width, $cfg->medium_height, 'middle');
                                    if (!$s) {
                                            $msg .= $file['name'] . " middle - " . JText::_('COM_RBIDS_ERR_LOADING_PICTURE') . "<br /><br />";
                                    }

                            } else {
                                    $msg .= $file['name'] . "- " . JText::_('COM_RBIDS_ERR_UPLOAD_FAIL') . "<br /><br />";
                            }
                            
                        }
			return $msg;

		}

		/**
		 * @param $item
		 *
		 * @return array
		 */
		public function validateSave($item)
		{

			$cfg =& JTheFactoryHelper::getConfig();
			$error = array();
			// Title validation
			if ($item->title == "" || !isset($item->title))
				$error[] = JText::_("COM_RBIDS_TITLE_CAN_NOT_BE_EMPTY");

			// Dates validations
			if (!$item->id) {

				$start_date = $item->start_date;
				$end_date = $item->end_date;

				if (!$start_date) {
					$error[] = JText::_("COM_RBIDS_ERR_START_DATE_VALID");
				}
				if (!$end_date) {
					$error[] = JText::_("COM_RBIDS_ERR_END_DATE_VALID");
				}

				$year = date("Y", strtotime($start_date));
				$month = date("m", strtotime($start_date));
				$day = date("d", strtotime($start_date));
				$year_end = date("Y", strtotime($end_date));
				$month_end = date("m", strtotime($end_date));
				$day_end = date("d", strtotime($end_date));

				if ($year < 1900 && $year > 2200) {
					$error[] = JText::_("COM_RBIDS_ERR_START_DATE_VALID") . "<br />";
				}
				if ($month < 1 && $month > 12) {
					$error[] = JText::_("COM_RBIDS_ERR_START_DATE_VALID") . "<br />";
				}
				if ($day < 1 && $day > 31) {
					$error[] = JText::_("COM_RBIDS_ERR_START_DATE_VALID") . "<br />";
				}


				if ($year_end < 1900 && $year_end > 2200) {
					$error[] = JText::_("COM_RBIDS_ERR_END_DATE_VALID") . "<br />";
				}
				if ($month_end < 1 && $month_end > 12) {
					$error[] = JText::_("COM_RBIDS_ERR_END_DATE_VALID") . "<br />";
				}
				if ($day_end < 1 && $day_end > 31) {
					$error[] = JText::_("COM_RBIDS_ERR_END_DATE_VALID") . "<br />";
				}

				$datedif = mktime(0, 0, 0, $month_end, $day_end, $year_end) - mktime(0, 0, 0, $month, $day, $year);
				if ($datedif < 0) {
					$error[] = JText::_("COM_RBIDS_ERR_END_BIGGER_THAN_START") . "<br />";
				}
				$datedif = mktime(0, 0, 0, $month_end, $day_end, $year_end) - mktime(0, 0, 0, $month, $day, $year);

				if ($cfg->availability > 0)
					if (floor($datedif / 60 / 60 / 24) >= $cfg->availability * 31) {
						$error[] = sprintf(JText::_("COM_RBIDS_ERROR_MAX_AVAILABILITY"), $cfg->availability) . "<br/>";
					}

			}

			/** If you need MAX Price to be required just uncomment the lines below:
			 *
			 *                 if ($item->max_price<=0){
			 *                                 $error[]=JText::_("COM_RBIDS_MAX_PRICE_MUST_BE_GREATER_THEN")."<br />";
			 *                 }
			 */
			//ATTACHEMENT validations
			if ($cfg->enable_attach) {
				if ($this->attachment) {
					if (is_array($this->attachment)) {
						$ext = strtolower(JFile::getExt($this->attachment['name']));
					} else {
						$ext = strtolower(JFile::getExt($this->attachment));
					}
					if ($cfg->attach_extensions) {
						$allowed = explode(",", $cfg->attach_extensions);
						if (!in_array($ext, $allowed)) {
							$error [] = JText::_('COM_RBIDS_ATTACHED_FILE_EXTENSION_NOT_ALLOWED_USE') . $cfg->attach_extensions;
							unset($this->attachment);
						}
					}
					if ($cfg->attach_max_size) {
						if (filesize($this->attachment['tmp_name']) > 1024 * $cfg->attach_max_size) {
							$error [] = JText::_('COM_RBIDS_ATTACHED_FILE_IS_TOO_LARGE_MAXIMUM_ALLOWED') . $cfg->attach_max_size . ' kB';
							unset($this->attachment);
						}
					}
				} elseif ($cfg->attach_compulsory)
					$error[] = JText::_("COM_RBIDS_ERR_ATTACHMENT_COMPULSORY") . "<br />";
			}

			// NDA File validation
			if ($cfg->nda_option) {
				if ($this->NDA_file) {
					if (is_array($this->NDA_file)) {
						$ext = strtolower(JFile::getExt($this->NDA_file['name']));
					} else {
						$ext = strtolower(JFile::getExt($this->NDA_file));
					}
					if ($cfg->nda_extensions != "") {
						$allowed = explode(",", $cfg->nda_extensions);
						if (!in_array($ext, $allowed)) {
							$error[] = JText::_("COM_RBIDS_NDA_FILE_EXTENSION_NOT_ALLOWED_USE") . $cfg->nda_extensions;
							unset($this->NDA_file);
						}
					}
				} elseif ($cfg->nda_compulsory)
					$error[] = JText::_('COM_RBIDS_NDA_FILE_COMPULSORY') . "<br />";
			}
			if (!$cfg->disable_images && $cfg->main_picture_require) {
				$has_main_picture = false;

				$oldid = JRequest::getVar('oldid', 0);
				$delete_main_picture = JRequest::getVar('delete_main_picture', '');

				if ($oldid || $item->id) {
					//repost or edit existing
					$db =& $this->getDbo();
					$oldAuction =& JTable::getInstance('auctions', 'Table');
          $has_main_picture = isset($_FILES['picture_0']) && (is_uploaded_file(@$_FILES['picture_0']['tmp_name']));
					if (!$delete_main_picture && $oldAuction->load($oldid ? $oldid : $item->id)) {
						$has_main_picture = $has_main_picture|| ($oldAuction->picture !== '');
					}

				} else {

					if (isset($_FILES['picture_0'])) {
						$file = $_FILES['picture_0'];
						$has_main_picture = true;
						if (!is_uploaded_file(@$file['tmp_name'])) {
							$error[] = $file['name'] . "- " . JText::_("COM_RBIDS_ERR_UPLOAD_FAIL");
							$has_main_picture = false;
						}

						if (filesize($file['tmp_name']) > $cfg->max_picture_size * 1024) {
							$error[] = $file['name'] . "- " . JText::_("COM_RBIDS_ERR_IMAGESIZE_TOO_BIG");
							$has_main_picture = false;
						}
						if (isset($file['name']) && $file['name']) {
							$ext = strtolower(JFile::getExt($file['name']));
							if (!$item->isAllowedImage($ext)) {
								$error[] = JText::_("COM_RBIDS_EXTENSION_NOT_ALLOWED") . ': ' . $ext;
								$has_main_picture = false;
							}
						} else {
							$has_main_picture = false;
						}
					}
				}
				if (!$has_main_picture)
					$error[] = JText::_("COM_RBIDS_ERR_PICTURE_IS_REQUIRED");
			}
			foreach ($_FILES as $k => $file) {
				if (substr($k, 0, 7) != "picture")
					continue;

				if (!isset($file['name']) || $file['name'] == "")
					continue;

				if (!is_uploaded_file(@$file['tmp_name'])) {
					continue;
				}

				if (filesize(@$file['tmp_name']) > $cfg->max_picture_size * 1024) {
					$error[] = $file['name'] . " - " . JText::_("COM_RBIDS_ERR_IMAGESIZE_TOO_BIG");
				}
			}
			//Custom Validations
			if (!$item->check())
				$error = array_merge($error, $item->_validation_errors);


			return $error;
		}

		/**
		 * @return mixed
		 */
		public function getNrFieldsWithFilters()
		{
			$database = &$this->getDBO();
			$database->setQuery("select count(*) from #__rbid_fields where page='auctions' and categoryfilter=1");
			return $database->loadResult();
		}


	}

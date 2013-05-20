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

// Check to ensure this file is included in Joomla!
	jimport('joomla.application.component.controller');

	class RbidsController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		public function __construct($config = array())
		{
			parent::__construct($config);

			// Register Extra tasks
			$this->registerTask('new', 'form');
			$this->registerTask('editauction', 'form');
			$this->registerTask('newauction', 'form');
			$this->registerTask('republish', 'form');
			$this->registerTask('details', 'viewbids');
			$this->registerTask('listcats', 'categories');

			$this->registerTask('showSearchResults', 'listauctions');
			$this->registerTask('tags', 'listauctions');


			$this->registerTask('showsearch', 'show_search');
			$this->registerTask('search', 'show_search');

			$this->registerTask('tree', 'categories');
		}

		/**
		 *          CATEGORY TASKS
		 */
		public function SelectCat()
		{
			//TODO:optimize category selection
			$task = JRequest::getCmd('task');
			$catModel =& RBidsHelperTools::getCategoryModel();
			$items = $catModel->getCategoryTree();

			$task = ($task == "selectcat") ? "edit" : $task;

			$view = $this->getView('category', 'html');
			$view->assign("task", $task);
			$view->assign("categories", $items);
			$view->display("t_catselect.tpl");
		}

		public function Categories()
		{
			$task = JRequest::getCmd('task');
			$filter_cat = JRequest::getInt("cat", 0);
			$filter_letter = JRequest::getString('filter_letter', 'all');

			$categoryModel =& RBidsHelperTools::getCategoryModel();
			if (!$categoryModel->getCategoryCount($filter_cat)) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(array('cat' => $filter_cat)));
				return;
			}


			if ($task == "tree") {
				$depth_level = null; //endless
				$template = "t_category_tree.tpl";
				RBidsHelperView::loadJQuery();
			} else {
				$depth_level = 1;
				$template = "t_categories.tpl";
			}

			$user = & JFactory::getUser();

			$rBidsCatModel =& JModel::getInstance('RCategory', 'rbidsModel');
			$categories = $rBidsCatModel->getRbidsCategoryTree($filter_cat, $depth_level, $user->id, $filter_letter);

			RBidsHelperView::prepareCategoryTree($categories, $task);

			$current_cat = null;

			if ($filter_cat) {
				$current_cat = & RBidsHelperTools::getCategoryTable();
				$current_cat->load($filter_cat);
			}
			$view = $this->getView('category', 'html');
			$view->assignRef("filter_letter", RBidsHelperView::buildLetterFilter($filter_cat));
			$view->assignRef("current_cat", $current_cat);
			$view->assign("categories", $categories);
			$view->display($template);
		}

		/**
		 *          AUCTION LISTING TASKS
		 */

		/**
		 * RbidsController::Form()
		 *     New or Edit Auctions
		 *
		 * @return void
		 */
		public function Form()
		{

			JHtml::_('behavior.framework');
			JHtml::_('behavior.calendar');
			$doc = JFactory::getDocument();
                        $database = & JFactory::getDBO();
			$doc->addScript('components/com_rbids/js/Stickman.MultiUpload.js');
			$doc->addScript('components/com_rbids/js/auction_edit.js');
			$doc->addScript('components/com_rbids/js/date.js');
                        
			$id = JRequest::getInt('id');
			$task = JRequest::getCmd('task');
			$category = JRequest::getInt('category');
                        $CPhotoid = JRequest::getInt('photoid',false);
                        $CAlbumid = JRequest::getInt('albumid',false);
                        $tags = JRequest::getString('tags');
                        $CPhoto = '';
                        
			$cfg =& JTheFactoryHelper::getConfig();
			$my = JFactory::getUser();
			$isSuperAdmin = $my->authorise('core.admin');

			$categoryModel =& RBidsHelperTools::getCategoryModel();

			$auction = & JTable::getInstance('auctions', 'Table');
			if ($id) $auction->load($id);
			$auction->loadFromSession();
                        
			if ($id) $auction->id = $id; // Force ID if EDIT
			if ($category) $auction->cat = $category; //Force Category if chosen
// hack begin
                        $auction->tags = $tags;
// hack end
                        if($CPhotoid){
                            $CPhoto = $database->setQuery("SELECT * FROM #__community_photos WHERE id=".$CPhotoid)->loadObject();
                        }
                        $auction->CPhoto = $CPhoto;
                        if ($id && !$auction->isMyAuction()) {
				JError::raiseWarning(503, JText::_("COM_RBIDS_YOU_CAN_EDIT_ONLY_YOUR_AUCTIONS"));
				return;
			}

			if ($id && $auction->close_by_admin) {
				JError::raiseWarning(503, JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}

			if ($cfg->workflow == 'catpage' && !$auction->cat) {
				// Category selection is mandatory
				$nrCats = $categoryModel->getCategoryCount();
				if ($nrCats && !$id) {
					//New Auction -> go to Select Categories
					$this->setRedirect(RBidsHelperRoute::getSelectCategoryRoute());
					return;
				}
				$cat_obj = $categoryModel->getFirstCategory();
				$category = $cat_obj->id;
				$_REQUEST['category'] = $category;
				$_GET['category'] = $category;
				$_POST['category'] = $category;
				$auction->cat = $category;
			}

			$auctionModel = & JModel::getInstance('RBid', 'rbidsModel');

			$userprofile = RBidsHelperTools::getUserProfileObject();
			$userprofile->getUserProfile();


			if (($isSuperAdmin && $cfg->allow_sellers_edit) && ($task != 'editauction' && !$auction->published)) {
				$lists['userid'] = JHtml::_('Invite.selectSeller', $auction);
			} else {
				$lists['userid'] = $my->username;
			}

			$editor = & JFactory::getEditor();
			$lists["description"] = $editor->display('description', $auction->description, '100%', '400', '60', '15');

			if ($cfg->workflow == 'catpage') {
				$catname = $categoryModel->getCategoryPathString($auction->cat);
				$lists['cats'] = '<input type="hidden" name="cat" value="' . $auction->cat . '" /><span>' . $catname . '</span>';
			} else
				$lists['cats'] = JHtml::_('factorycategory.select', 'cat', "onchange='reverseRefreshCustomFields(this);'", $auction->cat);
			$lists['currency'] = JHtml::_('currency.selectlist', 'currency', '', $auction->currency);


			if (!$cfg->auctiontype_enable && $cfg->auctiontype_val) {
				$lists['auctiontype'] = "<input type='hidden' name='auction_type' value='" . $cfg->auctiontype_val . "' >";
			} else {
				$mode = $auction->id ? 'form' : 'cookie';
				$lists['inviteSettingsContainer'] =
					'<div id="inviteSettingsContainer" style="display:' . (($auction->auction_type == AUCTION_TYPE_INVITE) ? 'inline' : 'none') . '">' .
						JHTML::link("index.php?option=" . APP_EXTENSION . "&controller=invites&task=getlists&auctionId=" . $auction->id . "&mode=" . $mode . "&tmpl=component", JText::_('COM_RBIDS_INVITE_BIDDERS'),
							'class="modal" rel="{handler:\'iframe\',size: {x:550,y:400}, ajaxOptions: { method: \'get\' } }"'
						) .
						'</div>';
				$lists['auctiontype'] = JHtml::_('auctiontype.selectlist', 'auction_type', 'onchange="auctionTypeExtras(this.value);" class="required"', $auction->auction_type);
			}

			if (($cfg->auctionpublish_enable) || ($auction->id && !$auction->published))
				//second part of the IF is for unpublished auctions due to lack of credits
				$lists['published'] = JHtml::_('auctionpublished.selectlist', 'published', '', $auction->published);
			else
				$lists['published'] = "<input type='hidden' name='published' value='" . $cfg->auctionpublish_val . "' >";

			//Date Time
			$lists['end_hour'] = '00';
			$lists['end_minute'] = '00';

			$lists['start_date_html'] = JHtml::_('auctiondate.calendar', $auction->start_date, 'start_date', array('readonly' => 1));
			$lists['end_date_html'] = JHtml::_('auctiondate.calendar', $auction->end_date, 'end_date', array('readonly' => 1));
			if ($auction->end_date) //start and end date are stored as ISO
			{
				if ($cfg->enable_hour) {
					$lists['end_hour'] = JHtml::date($auction->end_date, 'H');
					$lists['end_minute'] = JHtml::date($auction->end_date, 'i');
				}
			}

			$lists['tip_max_availability'] = "<span class='hasTip' title='" . sprintf(JText::_('BID_AUCTION_MAX_AVAILABILITY'), $cfg->availability) . "'>
									<img alt='Tooltip' src='" . JURI::root() . "components/com_rbids/images/tooltip.png' /></span>";

			$fields =& CustomFieldsFactory::getFieldsList("auctions");
			$fields_html = JHtml::_('customfields.displayfieldshtml', $auction, $fields);

			$custom_fields_with_cat = $auctionModel->getNrFieldsWithFilters();

			JFilterOutput::objectHTMLSafe($auction, ENT_QUOTES);
			JTheFactoryEventsHelper::triggerEvent('onBeforeEditAuction', array($auction));


			$view = $this->getView('RBid', 'html');
			if ($task == "republish") {
				$auction->id = null;
				$view->assign("oldid", $id);
			}
                        
                        if(!$auction->id && $userprofile->googleMaps_x != "" && $userprofile->googleMaps_y != "" ){//new auction get profile coordinates
                            $auction->googlex = $userprofile->googleMaps_x;
                            $auction->googley = $userprofile->googleMaps_y;
                        }
                        
                        
                        $view->assign('JURI_BASE',JURI::base());
			$view->assign("terms", (strip_tags($cfg->terms_and_conditions)) ? 1 : 0);
			$view->assign("custom_fields_with_cat", $custom_fields_with_cat ? 1 : 0);
			$view->assign("custom_fields_html", $fields_html);
			$view->assign("custom_fields", $fields);
			$view->assign("auction", $auction);

			$view->assign("hidden_photoid", $CPhotoid);
			$view->assign("hidden_albumid", $CAlbumid);
			$view->assign("user", $userprofile);
			$view->assign("lists", $lists);
			$view->assign('form_token', JHtml::_('form.token'));

			$view->display("t_editauction.tpl");

		}

		public function Save()
		{
                        JHtml::_('behavior.framework');
			$sess = JFactory::getSession();
                        
                        $database = & JFactory::getDBO();
			// Check for request forgeries.
			$id = JRequest::getInt('id');
			//catch files overload error...
                        
                        
                        
                        
                        
                        
			if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
				// Get maximum files size to inform user to not exceed it when upload images
				$max_upload = (int)(ini_get('upload_max_filesize'));
				$max_post = (int)(ini_get('post_max_size'));
				$memory_limit = (int)(ini_get('memory_limit'));
				$maxUploadMB = min($max_upload, $max_post, $memory_limit) . 'MB';

				$msg = sprintf(JText::_('COM_RBIDS_SAVING_AUCTION_UPLOADED_FILES_EXCEED_SIZE_ERROR'), $maxUploadMB);

				if ($id)
					$this->setRedirect(RBidsHelperRoute::getAuctionEditRoute($id), $msg, 'error');
				else
					$this->setRedirect(RBidsHelperRoute::getNewAuctionRoute(), $msg, 'error');
				return null;
			}


			if (!$sess->checkToken()) {
				return 'Error:Invalid Token ';
			}

			if ($sess->has('saveOne', 'rbids')) {
				if ($id) {
					$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($id));
				} else {
					$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				}
			}
			$sess->set('saveOne', 1, 'rbids');

 
                        
			$auction =& JTable::getInstance('auctions', 'Table');
			$model = & JModel::getInstance('RBid', 'rbidsModel');

			$model->bindAuction($auction);


                        
			JTheFactoryEventsHelper::triggerEvent('onBeforeSaveAuction', array($auction));
                        $err = $model->saveAuction($auction);

			$auction->clearSavedSession();

			if (count($err) || !$auction->id) {
				//Some errors!
				$auction->saveToSession();
				$err_message = implode('<br />', $err);

				if ($id)
					$this->setRedirect(RBidsHelperRoute::getAuctionEditRoute($id), $err_message);
				else
					$this->setRedirect(RBidsHelperRoute::getNewAuctionRoute(), $err_message);
				JTheFactoryEventsHelper::triggerEvent('onAfterSaveAuctionError', array($auction, $err));
			} else {                                
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction->id), JText::_("COM_RBIDS_AUCTION_SAVED"));
				JTheFactoryEventsHelper::triggerEvent('onAfterSaveAuctionSuccess', array($auction, $err));
			}
		}

		public function RefreshCategory()
		{
			$id = JRequest::getInt('id');
			$oldid = JRequest::getInt('oldid');

			$auction =& JTable::getInstance('auctions', 'Table');
			$model = & JModel::getInstance('RBid', 'rbidsModel');
			$model->bindAuction($auction);
			$auction->saveToSession();
			if ($oldid)
				$this->setRedirect(RBidsHelperRoute::getAuctionRepublishRoute($oldid));
			elseif ($id)
				$this->setRedirect(RBidsHelperRoute::getAuctionEditRoute($id)); else
				$this->setRedirect(RBidsHelperRoute::getNewAuctionRoute());
		}

		public function CancelAuction()
		{

			$id = JRequest::getInt('id');

			$auction =& JTable::getInstance('auctions', 'Table');
			// load the row from the db table
			$auction->load($id);

			if (!$auction->isMyAuction()) {
				JError::raiseWarning(501, JText::_("COM_RBIDS_THIS_AUCTION_DOES_NOT_BELONG_TO_YOU"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($id));
				return;
			}
			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}

			$auction->close_offer = 1;
			$auction->cancel_reason = JRequest::getVar("cancel_reason", "", "string");
			$auction->closed_date = gmdate('Y-m-d H:i:s');
			JTheFactoryEventsHelper::triggerEvent('onBeforeCancelAuction', array($auction));

			if ($auction->store(true)) {
				JTheFactoryEventsHelper::triggerEvent('onAfterCancelAuction', array($auction));
				$msg = JText::_("COM_RBIDS_AUCTION_WAS_CANCELED");
			} else {
				$msg = JText::_("COM_RBIDS_AUCTION_COULD_NOT_BE_CANCELED");
			}
			$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($id), $msg);
		}

		public function ViewBids()
		{

			$id = JRequest::getInt('id');

			$auction =& JTable::getInstance('auctions', 'Table');
			$my = & JFactory::getUser();
			$cfg =& JTheFactoryHelper::getConfig();
			JTheFactoryHelper::tableIncludePath('category');

			if (!$auction->load($id)) {

				JError::raiseWarning(501, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				return;
			}

			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}
			if ($cfg->admin_approval && !$auction->approved && !$auction->isMyAuction()) {
				JError::raiseWarning(501, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				return;
			}

			if (AUCTION_TYPE_INVITE == $auction->auction_type &&
				!$auction->isMyAuction() &&
				($cfg->allow_only_invited_users && !$auction->isInvited())
			) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_AUCTION_INVITATION_IS_REQUIRED"));
				return;
			}

			JHTML::_("behavior.modal");
			JHTML::script("auctions.js", 'components/com_rbids/js/');
			JHTML::script("ratings.js", 'components/com_rbids/js/');
			if (RBidsHelperDateTime::dateDiff($auction->start_date) < 0 && !$auction->isMyAuction()) {
				JError::raiseWarning(501, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				return;
			}

			if (!$auction->isMyAuction())
				$auction->hit();

			$modelUser = & JModel::getInstance('User', 'rbidsModel');

			$user =& $modelUser->getUserData($auction->userid);

			$bids = $auction->getBidList();

			/* JOOMLA DOCUMENT Meta information */
			$doc = & JFactory::getDocument();
			$doc->setTitle($auction->title);
			$doc->setMetaData('description', strip_tags($auction->shortdescription));
			$doc->setMetaData('abstract', strip_tags($auction->description));
			$doc->setMetaData('keywords', $auction->get('tags'));

			$app =& JFactory::getApplication();
			$pathway = $app->getPathway();
			$pathway->addItem($auction->title, RBidsHelperRoute::getAuctionDetailRoute($auction->id));

			$view = $this->getView('RBid', 'html');

			$view->assign("uploaded_NDA", 0);
			if ($cfg->nda_option == 1 && $auction->NDA_file != "" && $my->id) {
				$nda_file_name = "auct{$auction->id}-{$my->id}.fil";
				if (file_exists(AUCTION_UPLOAD_FOLDER . $nda_file_name))
					$view->assign("uploaded_NDA", 1);
			}

			$jsUrl = JURI::root() . 'components/com_rbids/gallery/js';
			$doc = & JFactory::getDocument();
			$doc->addScript($jsUrl . "/jquery.js");
			$doc->addScriptDeclaration("
			            if(typeof window.jQuery != 'undefined') {
			                jQuery.noConflict();
			            }
		        ");

			$view->assign("invite_button", JHtml::_('invite.invitesButton', $auction));
			$view->assign("terms_and_conditions", (strip_tags($cfg->terms_and_conditions)) ? 1 : 0);
			$view->assign("auction", $auction);
			$view->assign("captcha", RBidsHelperCaptcha::init_captcha());
			$view->assign("auctioneer", $user);
			$view->assign("my", $my);
			$view->assign("bids", $bids);
			$view->display("t_auctiondetails.tpl");
		}


		/**
		 *              SEARCH and LISTING tasks
		 */
		public function Show_Search()
		{
			JHTML::_('behavior.calendar');
			$cfg =& JTheFactoryHelper::getConfig();

			$reload = JRequest::getVar('reload');
			$r = new JObject();

			if ($reload) {
				$session = JFactory::getSession();
				$r = $session->get('registry');
			}

			$lists['cats'] = JHtml::_('factorycategory.select', 'cat', '', $r->get('com_rbids.model_Rbids.filters.cat'), false, false, true);
//			$lists['users'] = JHtml::_('rbiduser.selectlist', 'userid', '', $r->get('com_rbids.model_Rbids.filters.userid'));
			$lists['country'] = JHtml::_('country.selectlist', 'country', 'id="country"', $r->get('com_rbids.model_Rbids.filters.country'));
			$lists['afterd'] = JHtml::_('auctiondate.calendar', RBidsHelperDateTime::DateToIso($r->get('com_rbids.model_Rbids.filters.afterd')), 'afterd', array('readonly' => 1));
			$lists['befored'] = JHtml::_('auctiondate.calendar', RBidsHelperDateTime::DateToIso($r->get('com_rbids.model_Rbids.filters.befored')), 'befored', array('readonly' => 1));
			$lists['keyword'] = $r->get('com_rbids.model_Rbids.filters.keyword');
			$lists['in_description'] = $r->get('com_rbids.model_Rbids.filters.in_description');
			$lists['filter_archive'] = $r->get('com_rbids.model_Rbids.filters.filter_archive');
			$lists['city'] = $r->get('com_rbids.model_Rbids.filters.city');
			$lists['startprice'] = $r->get('com_rbids.model_Rbids.filters.startprice');
			$lists['endprice'] = $r->get('com_rbids.model_Rbids.filters.endprice');
			$lists['currency'] = JHtml::_('currency.selectlist', 'currency', 'id="currency"', $r->get('com_rbids.model_Rbids.filters.currency'));

			$fields = & CustomFieldsFactory::getSearchableFieldsList('auctions');
			$fields_html = JHtml::_('customfields.displaysearchhtml', $fields);

			$view = $this->getView('Search', 'html');
			$view->assign("lists", $lists);
			$view->assign("custom_fields_html", $fields_html);
			$view->assign("custom_fields", $fields);
			$view->display("t_search.tpl");
		}

		public function listauctions()
		{

			$format = JRequest::getVar('format');
			if ($format == 'feed')
				$view = $this->getView('RBids', 'feed');
			else
				$view = $this->getView('RBids', 'html');
			$view->display('t_listauctions.tpl');
		}

		public function myInvitedAuctions()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$auction =& JTable::getInstance('auctions', 'Table');
			$lists = array();
			$lists["invitedAuctions"] = JHtml::_('invite.invitedAuctions');

			$view = $this->getView('user', 'html');

			$view->assign("lists", $lists);
			$view->assign('cfg', $cfg);
			$view->assign('isInvited', $auction->isInvited());

			$view->display("t_my_invited_auctions.tpl");
		}


		public function mybids()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$auction =& JTable::getInstance('auctions', 'Table');

			$view = $this->getView('RBids', 'html');

			$view->assign('cfg', $cfg);
			$view->assign('isInvited', $auction->isInvited());

			$view->display('t_mybids.tpl');
		}

		public function mywonbids()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$auction =& JTable::getInstance('auctions', 'Table');

			$view = $this->getView('RBids', 'html');

			$view->assign('cfg', $cfg);
			$view->assign('isInvited', $auction->isInvited());

			$view->display('t_mywonbids.tpl');
		}

		public function myauctions()
		{
			$view = $this->getView('RBids', 'html');
			$view->assign('new_auction_link', RBidsHelperRoute::getNewAuctionRoute());
			$view->display('t_myauctions.tpl');
		}

		/**
		 *          BIDS AND BIDDING
		 *
		 *
		 *
		 */
		public function SendBid()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$auction_id = JRequest::getInt('id');
			$my = & JFactory::getUser();
			$auction =& JTable::getInstance('auctions', 'Table');

			/*******************************************************************
			 * Checking data validity
			 *******************************************************************/
			// Prevent redirect to Joomla home if user upload a bigger file then upload server limit
			if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
				// Get maximum files size to inform user to not exceed it when upload images
				$max_upload = (int)(ini_get('upload_max_filesize'));
				$max_post = (int)(ini_get('post_max_size'));
				$memory_limit = (int)(ini_get('memory_limit'));
				$maxUploadMB = min($max_upload, $max_post, $memory_limit) . 'MB';

				$msg = sprintf(JText::_('COM_RBIDS_SAVING_AUCTION_UPLOADED_BID_ATTACHMENT_EXCEED_SIZE_ERROR'), $maxUploadMB);

				if ($auction_id)
					$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id), $msg, 'error');
				else
					$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(), $msg, 'error');
				return null;
			}

			// Auction id is invalid
			if (!$auction->load($auction_id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false));
				return;
			}
			// Auction owner cannot bid to it's auction
			if ($auction->userid == $my->id) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_NOT_ALLOWED_TO_BID_OWN_OFFERS"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false));
				return;
			}
			// Cannot place a bid to a closed auction
			if ($auction->close_offer) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_IS_CLOSED"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false));
				return;
			}
			// Cannot place a bid to a closed auction
			if ($auction->published != 1) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false));
				return;
			}
			// Auction start date must be in future otherwise cannot place a bid
			if (RBidsHelperDateTime::dateDiff($auction->start_date) < 0) {
				JError::raiseWarning(501, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false));
				return;
			}
			// Cannot place a bid is auction is closed by admin
			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}
			// User must be invited to place a bid to an invited type auction
			if (AUCTION_TYPE_INVITE == $auction->auction_type &&
				!$auction->isMyAuction() &&
				($cfg->allow_only_invited_users && !$auction->isInvited())
			) {
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), JText::_("COM_RBIDS_AUCTION_INVITATION_IS_REQUIRED"));
				return;
			}

			$amount = JRequest::getFloat('amount', 0);
			$comment = JRequest::getVar('message', '');
			// Bidden amount cannot be bigger than max price
			if ($auction->max_price != 0 && $auction->max_price < $amount) {
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), JText::_("COM_RBIDS_THE_BIDDED_PRICE_IS_BIGGER_THAN_ALLOWED"));
				return;
			}

			/***********************************************************
			 * NDA file upload
			 ***********************************************************/
			$uploaded_NDA = 0;
			if ($cfg->nda_option == 1 && $auction->NDA_file != "" && $my->id) {
				$nda_file_name = "auct{$auction->id}-{$my->id}.fil";
				if (file_exists(AUCTION_UPLOAD_FOLDER . $nda_file_name))
					$uploaded_NDA = 1;
			}

			if ($auction->NDA_file != '' && $uploaded_NDA != 1 && $cfg->nda_option == 1) {
				$errors = $this->uploadNDA();
				if (count($errors)) {
					if (isset($errors['no_file_upload'])) {
						$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), JText::_('COM_RBIDS_NDA_IS_COMPULSORY'));
						return false;
					}
					if (isset($errors['wrong_extension'])) {
						$msg = JText::_('COM_RBIDS_NDA_FILE_EXTENSION_NOT_ALLOWED_USE') . $cfg->nda_extensions;
						$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), $msg);
						return false;
					}
					if (isset($errors['error_uploading'])) {
						$msg = JText::_('COM_RBIDS_ERR_FILE_WRITE_FAILED');
						$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), $msg);
						return false;
					}
				}
			}


			/***********************************************************
			 * Bid attachment file upload
			 ***********************************************************/
			$bid_attachment = '';
			if (isset($_FILES['bid_attachment']['tmp_name'])) {
				$bid_attachment = $_FILES['bid_attachment']['tmp_name'];
			}
			// User must upload an attachment
			if ($cfg->enable_bid_attach) {
				if ($cfg->bid_attach_compulsory && empty($bid_attachment)) {
					$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), JText::_("COM_RBIDS_ERR_BID_ATTACHMENT_COMPULSORY"));
					return;
				}
			}

			$ext = strtolower(JFile::getExt($_FILES['bid_attachment']['name']));
			// Uploaded attachment file extension si denied
			if ($cfg->attach_extensions && !empty($bid_attachment)) {
				$allowed = explode(",", strtolower($cfg->attach_extensions));
				if (!in_array($ext, $allowed)) {
					$msg = JText::_('COM_RBIDS_ATTACHED_FILE_EXTENSION_NOT_ALLOWED_USE') . $cfg->attach_extensions;
					$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), $msg);
					return;
				}
			}
			// Uploaded attachment is bigger than maximum allowed
			if ($cfg->attach_max_size && !empty($bid_attachment)) {
				if (filesize($bid_attachment) > 1024 * $cfg->attach_max_size) {
					$msg = JText::_('COM_RBIDS_ATTACHED_FILE_IS_TOO_LARGE_MAXIMUM_ALLOWED') . $cfg->attach_max_size . ' kB';
					$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), $msg);
					return;
				}
			}

			/*******************************************************************
			 * Everything is ok we can now insert bid
			 *******************************************************************/
			$bid =& JTable::getInstance('rbids', 'Table');
			$bid->userid = $my->id;
			$bid->auction_id = $auction_id;
			$bid->bid_price = $amount;
			$bid->modified = gmdate('Y-m-d H:i:s');
			$bid->accept = 0;
			$bid->cancel = 0;
			$bid->message = $comment;

			JTheFactoryEventsHelper::triggerEvent('onBeforeSaveBid', array($auction, $bid));
			// Upload bid_attachment
			if ($bid->store()) {
				/*******************************************************************
				 * Upload bid attachment
				 *******************************************************************/
				if ($cfg->enable_bid_attach) {
					// Load last bid inserted
					$bid->load($bid->id);

					$file_name = "bid{$bid->id}-auct{$auction->id}.attach";
					$path = AUCTION_UPLOAD_FOLDER . "$file_name";

					if (!empty($bid_attachment) && is_uploaded_file(@$bid_attachment)) {
						if (move_uploaded_file($bid_attachment, $path)) {
							$bid->file_name = $_FILES['bid_attachment']['name'];
							$bid->store();
						}
					}
				}
			}


			JTheFactoryEventsHelper::triggerEvent('onAfterSaveBid', array($auction, $bid));

			$id_msg = JRequest::getInt('idmsg', null);
			$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), JText::_("COM_RBIDS_SUCCESS"));
		}

		public function uploadNDA()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$id = JRequest::getInt('id');
			$mode = JRequest::getVar('mode', 'default');
			$errors = array();
			jimport('joomla.filesystem.file');
			$user = & JFactory::getUser();

			$userNDA = JRequest::getVar('NDA_file', null, 'files');

			if (!$userNDA['tmp_name']) {
				$errors['no_file_upload'] = 1;
				return $errors;
			}

			$ext = strtolower(JFile::getExt($userNDA['name']));
			// Uploaded NDA file extension si denied
			if ($cfg->nda_extensions && !empty($userNDA['tmp_name'])) {
				$allowed = explode(",", strtolower($cfg->nda_extensions));
				if (!in_array($ext, $allowed)) {
					$errors['wrong_extension'] = 1;
					return $errors;
				}
			}

			$file_name = AUCTION_UPLOAD_FOLDER . 'auct' . $id . '-' . $user->id . ".fil";
			if (JFile::exists($file_name))
				JFile::delete($file_name);

			if (JFile::upload($userNDA['tmp_name'], $file_name)) {

				$attachment = JTable::getInstance('attachement', 'Table');
				$attachment->auctionId = $id;
				$attachment->userid = $user->id;
				$attachment->fileName = JFile::stripExt($userNDA['name']);
				$attachment->fileExt = JFile::getExt($userNDA['name']);
				$attachment->fileType = 'nda';
				$attachment->store();

			} else {
				$errors['error_uploading'] = 1;
			}

			return $errors;

		}

		public function Accept()
		{

			$bid_id = JRequest::getInt('bid', 0);

			$auction =& JTable::getInstance('auctions', 'Table');
			$bid =& JTable::getInstance('rbids', 'Table');

			if (!$bid->load($bid_id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_SELECT_BID_TO_ACCEPT"));
				return;
			}
			if ($bid->cancel) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_BID_WAS_CANCELED"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($bid->auction_id, null, false));
				return;
			}
			if ($bid->bid_price <= 0) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_THE_BID_IS_NOT_VALID"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($bid->auction_id, null, false));
				return;
			}

			if (!$auction->load($bid->auction_id)) {
				echo JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST");
				JError::raiseWarning(510, JText::_("COM_RBIDS_THE_BID_IS_NOT_VALID"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($bid->auction_id, null, false));
				return;
			}

			if (!$auction->isMyAuction()) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_THE_AUCTION_DOES_NOT_BELONG_TO_YOU"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($bid->auction_id, null, false));
				return;
			}
			if ($auction->close_offer) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_IS_CLOSED"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($bid->auction_id, null, false));
				return;
			}
			if ($auction->published != 1) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_IS_NOT_PUBLISHED"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($bid->auction_id, null, false));
				return;
			}
			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}


			$user1 =& JTable::getInstance("user");
			if (!$user1->load($bid->userid) || $user1->block) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_USER_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($bid->auction_id, null, false));
				return;
			}

			@ignore_user_abort(true);

			$auction->close_offer = 1;
			$auction->winner_id = $bid->userid;
			$auction->closed_date = gmdate('Y-m-d H:i:s');

			JTheFactoryEventsHelper::triggerEvent('onBeforeAcceptBid', array($auction, $bid));

			$auction->store(true);

			$bid->accept = 1;
			$bid->store();

			JTheFactoryEventsHelper::triggerEvent('onAfterAcceptBid', array($auction, $bid));

			$auction->sendNewMessage(JText::_("COM_RBIDS_BID_ACCEPTED"), $auction->winner_id);

			$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction->id, null, false), JText::_("COM_RBIDS_BID_ACCEPTED"));
		}


		public function Terms_and_Conditions()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			echo $cfg->terms_and_conditions;
		}

		public function Report_Auction()
		{
			$auction_id = JRequest::getInt('id');
			$auction = & JTable::getInstance('auctions', 'Table');
			if (!$auction->load($auction_id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false));
				return;
			}
			if ($auction->published != 1) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_IS_NOT_PUBLISHED"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false));
				return;
			}
			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}

			$view = $this->getView('rbid', 'html');
			$view->assign("auction", $auction);
			$view->display("t_reportauction.tpl");
		}

		public function do_Report()
		{

			$my = & JFactory::getUser();
			$database = & JFactory::getDBO();

			$auction_id = JRequest::getInt('id');
			$message = $database->getEscaped(JRequest::getVar('message', ''));

			$auction = & JTable::getInstance('auctions', 'Table');
			if (!$auction->load($auction_id)) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_DOES_NOT_EXIST"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false));
				return;
			}
			if ($auction->published != 1) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_AUCTION_IS_NOT_PUBLISHED"));
				$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false));
				return;
			}
			if ($auction->close_by_admin) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute(null, false), JText::_("COM_RBIDS_THIS_AUCTION_WAS_BANNED_BY_THE_SITE_ADMINISTRATOR"));
				return;
			}
			if (!$message) {
				JError::raiseWarning(510, JText::_("COM_RBIDS_MESSAGE_CAN_NOT_BE_EMPTY"));
				$this->setRedirect(RBidsHelperRoute::getReportAuctionRoute($auction_id, false));
				return;
			}
			$reported =& JTable::getInstance('Report_Auctions', 'Table');
			$reported->auction_id = $auction_id;
			$reported->userid = $my->id;
			$reported->message = $message;
			$reported->modified = gmdate('Y-m-d H:i:s');
			$reported->store();


			JTheFactoryEventsHelper::triggerEvent('onAuctionReported', array($auction, $message));
			$this->setRedirect(RBidsHelperRoute::getAuctionDetailRoute($auction_id, null, false), JText::_("COM_RBIDS_AUCTION_REPORTED"));

		}

	}

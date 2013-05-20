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

	class UserController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		/**
		 * My Profile Details
		 */
		public function UserDetails()
		{
			if ($r = RBidsHelperTools::redirectToProfile()) {
				//3rd Party Profile integration
				$this->setRedirect($r);
				return;
			}

			$user = RBidsHelperTools::getUserProfileObject();
			$user->getUserProfile();

			JFilterOutput::objectHTMLSafe($user, ENT_QUOTES);

			$lists = array();
			$lists["country"] = JHtml::_('country.selectlist', 'country', 'id="country" class="inputbox"', $user->country);
			$lists["activity_domains"] = JHtml::_('categories.selectlist', 'activity_domains[]', 'id="activity_domains" class="inputbox" multiple="multiple"size="7"', explode(',', $user->activity_domains));
			JTheFactoryHelper::modelIncludePath('payments');
			$balance =& JModel::getInstance('balance', 'JTheFactoryModel');
			$lists["balance"] = $balance->getUserBalance();
			$lists["links"] = array(
				"upload_funds" => RBidsHelperRoute::getAddFundsRoute(),
				"payment_history" => RBidsHelperRoute::getPaymentsHistoryRoute()
			);

			$editor = & JFactory::getEditor();
			$lists["about_me"] = $editor->display('about me', $user->about_me, '100%', '400', '70', '15');

			$fields =& CustomFieldsFactory::getFieldsList("user_profile");
			$fields_html = JHtml::_('customfields.displayfieldshtml', $user, $fields);
			JHtml::_('behavior.tooltip');
			$view = $this->getView('user', 'html');
			$view->assign("lists", $lists);
			$view->assign("user", $user);
			$view->assign("custom_fields_html", $fields_html);
			$view->assign("page_title", JText::_("COM_RBIDS_USER_DETAILS"));
			$view->display("t_myuserdetails.tpl");
		}

		/**
		 * saveUserDetails
		 */
		public function saveUserDetails()
		{
			if (isset($_POST['cancel'])) {
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
				return;
			}
			$my = & JFactory::getUser();
			$model = & $this->getModel('User', 'rbidsModel');
			if ($model->saveUserDetails())
				$msg = JText::_("COM_RBIDS_DETAILS_SAVED");
			else
				$msg = JText::_("COM_RBIDS_THERE_WAS_PROBLEM_SAVING_USER_DETAILS");
			$this->setRedirect(RBidsHelperRoute::getUserdetailsRoute(), $msg);
		}

		/**
		 * UserProfile
		 */
		public function UserProfile()
		{
			$id = JRequest::getInt('id');
			if (!$id) {
				JError::raiseWarning(501, JText::_("COM_RBIDS_SELECT_AN_USER"));
				$this->setRedirect(RBidsHelperRoute::getAuctionListRoute());
			}
			if ($r = RBidsHelperTools::redirectToProfile($id)) {
				//3rd Party Profile integration
				$this->setRedirect($r);
				return;
			}

			$user = RBidsHelperTools::getUserProfileObject();
			$user->getUserProfile($id);
			$user->paypalemail = RBidsHelperTools::cloack_email($user->paypalemail);
			$userActivityDomains = RBidsHelperTools::getUserActivityDomains($user->activity_domains);

			// Insert filter catId | userId for link to auctions listing
			// Just because smarty don't know to work  with array literals as functions parameters
			if (is_array($userActivityDomains) && count($userActivityDomains)) {
				foreach ($userActivityDomains as $userActDom) {
					$userActDom->aucLinkFilterCatIdUserId = array('cat' => $userActDom->id, 'userid' => $id);
				}
			}
			$user->activity_domains = $userActivityDomains;

			$ratingModel = & $this->getModel('Ratings', 'rbidsModel');
			$lists = array();
			$lists['ratings'] = $ratingModel->getRatingsList($id);

			$view = $this->getView('user', 'html');
			$view->assign("lists", $lists);
			$view->assign("user", clone $user); //using CLONE since the profile object might be reset in onBeforeDisplay
			$view->assign("page_title", JText::_("COM_RBIDS_USER_PROFILE") . ": " . $user->username);

			$view->display("t_userdetails.tpl");
		}

		/**
		 * searchUsers
		 */
		public function searchUsers()
		{
			$app = & JFactory::getApplication();
			$params = $app->getParams();

			$fields = & CustomFieldsFactory::getSearchableFieldsList('user_profile');

			$lists = array();
			$lists['country'] = JHtml::_('country.selectlist', 'country', 'id="country"', '', true);
			$lists['custom_fields'] = JHtml::_('customfields.displaysearchhtml', $fields);
			$view = $this->getView('user', 'html');
			$view->assign("page_title", JText::_("COM_RBIDS_SEARCH_USERS"));
			$view->assign("custom_fields", $fields);
			$view->assign("lists", $lists);
			$view->display("t_search_users.tpl");
		}

		/**
		 * showUsers
		 */
		public function showUsers()
		{
			$uri = & JFactory::getURI();
			$model = & JModel::getInstance('User', 'rbidsModel');
			$ratingsmodel =& JModel::getInstance('Ratings', 'rbidsModel');

			$items = $model->loadItems();

			for ($key = 0; $key < count($items); $key++) {
				$user = & $items[$key];
				$user->link = RBidsHelperRoute::getUserdetailsRoute($user->userid);
				$user->ratings = $ratingsmodel->getUserRatings($user->userid);
			}
			$filters = $model->getFilters();

			$pagination = $model->get('pagination');

			$view = $this->getView('user', 'html');
			$view->assign("action", JRoute::_(JFilterOutput::ampReplace($uri->toString())));
			$view->assign("users", $items);
			$view->assign("sfilters", $filters);
			$view->assign("pagination", $pagination);
			$view->assign("page_title", JText::_("COM_RBIDS_USER_SEARCH_RESULT"));
			$view->display("t_showusers.tpl");
		}

	} // End Class

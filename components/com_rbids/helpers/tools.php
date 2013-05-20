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
	jimport('joomla.html.parameter');
	class RBidsHelperTools
	{

		/**
		 * @param $mail_address
		 *
		 * @return string
		 */
		public static function cloack_email($mail_address)
		{
			$cfg =& JTheFactoryHelper::getConfig();

			if ($cfg->enable_antispam_bot == 1 && $cfg->choose_antispam_bot) {
				if ($cfg->choose_antispam_bot == "recaptcha") {
					require(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'recaptcha' . DS . 'recaptchalib.php');
					$mail = recaptcha_mailhide_url("01WxCXdKklKdG2JpOlMY15jw==", "2198178B23BFFB00CBAEA6370CE7A0B2", $mail_address);
					return "<a href=\"$mail.\" onclick=\"window.open('$mail', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0, menubar=0,resizable=0,width=500,height=300');	return false;\" title=\"Reveal this e-mail address\">" . JText::_("COM_RBIDS_SHOW_EMAIL") . "</a>";
				} elseif ($cfg->choose_antispam_bot == "joomla") {

					// Discutable if use Content Mail Plugin or ... just JHTML_('email.cloak' it does the same .. just global configuration is in question
//					global $mainframe;
//					$plugin = & JPluginHelper::getPlugin('content', 'emailcloak');
//					$pluginParams = new JParameter($plugin->params);
//					require_once (JPATH_SITE . DS . 'plugins' . DS . 'content' . DS . 'emailcloak' . DS . 'emailcloak.php');

					return JHtml::_('email.cloak', $mail_address);
//					return $mail_address;
				} elseif ($cfg->choose_antispam_bot == "smarty") {
					$smarty = new JTheFactorySmarty();
					require_once(SMARTY_DIR . "plugins/function.mailto.php");
					return RBidsSmarty::smarty_rbids_print_encoded(array("address" => $mail_address, "encode" => "hex"), $smarty);
				}
			} else
				return $mail_address;
		}

		/**
		 * Finds The Menu Item Of the Component
		 *  by the needles as params
		 *
		 * needle example: 'view' => 'category'
		 *
		 *
		 * @param $needles
		 *
		 * @internal param array $needle
		 *
		 * @return null
		 * @since    1.5.0
		 */
		public static function getMenuItemId($needles)
		{

			if (!is_array($needles)) {
				if ($needles)
					$needles = array($needles);
				else
					$needles = array();
			}
			$needles['option'] = 'com_rbids';

			$menus = & JApplication::getMenu('site', array());
			$items = $menus->getItems('query', $needles);

			if (!count($items))
				return null; //no extension menu items

			$match = reset($items); //fallback first encountered Menuitem

			foreach ($items as $item) {
				if ($match->access != 0 && $item->access == 0) {
					$match = $item; //even better fallback is one that has public access
					continue;
				}

				$xssmatch1 = array_intersect_assoc($item->query, $needles);
				$xssmatch2 = array_intersect_assoc($match->query, $needles);
				var_dump($xssmatch1, $xssmatch2);
				if (count($xssmatch1) > count($xssmatch2)) { //better needlematch
					$match = $item; //even better fallback is one that has public access
					continue;
				}
			}

			return $match->id;
		}

		/**
		 * @return string
		 */
		public function getProfileMode()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			return ($cfg->profile_mode) ? $cfg->profile_mode : "component";
		}

		/**
		 * @return JTheFactoryUserProfile
		 */
		public static function getUserProfileObject()
		{
			$profile_mode = self::getProfileMode();
			return JTheFactoryUserProfile::getInstance($profile_mode);
		}

		/**
		 * getUserActivityDomains
		 *
		 * @param null $actDom
		 *
		 * @return mixed|string
		 */
		public static function getUserActivityDomains($actDom = null)
		{
			if (!$actDom) {
				return '-';
			}
			$db = JFactory::getDbo();
			$query = "SELECT `id`, `catname` FROM `#__rbid_categories` WHERE `id` IN({$actDom})";
			$db->setQuery($query);
			return $db->loadObjectList();
		}

		/**
		 * @param null $id
		 *
		 * @return mixed
		 */
		public static function redirectToProfile($id = null)
		{
			$userprofile = self::getUserProfileObject();
			return $userprofile->getProfileLink($id);
		}

		/**
		 * @param null $userid
		 *
		 * @return bool
		 */
		public static function isSeller($userid = null)
		{
			if (!$userid) {
				$user =& JFactory::getUser();
				$userid = $user->id;
			}
			if (!$userid) return false;
			$cfg =& JTheFactoryHelper::getConfig();
			if (!$cfg->enable_acl) return true;
			$user_groups = JAccess::getGroupsByUser($userid);

			return count(array_intersect($user_groups, $cfg->seller_groups)) > 0;

		}

		/**
		 * @param null $userid
		 *
		 * @return bool
		 */
		public static function isBidder($userid = null)
		{
			if (!$userid) {
				$user =& JFactory::getUser();
				$userid = $user->id;
			}
			if (!$userid) return false;
			$cfg =& JTheFactoryHelper::getConfig();
			if (!$cfg->enable_acl) return true;
			$user_groups = JAccess::getGroupsByUser($userid);

			return count(array_intersect($user_groups, $cfg->bidder_groups)) > 0;

		}

		/**
		 * @param null $userid
		 *
		 * @return mixed
		 */
		public static function isVerified($userid = null)
		{
			$userprofile = RBidsHelperTools::getUserProfileObject();
			$userprofile->getUserProfile($userid);
			return $userprofile->verified;
		}


		/**
		 * @return JRBidsACL|object
		 */
		public static function &getRbidsACL()
		{
			static $acl = null;
			if (is_object($acl)) return $acl;
			require_once(JPATH_COMPONENT_SITE . DS . 'rbids.acl.php');
			$acl = new JRBidsACL();
			return $acl;
		}

		/**
		 * @return mixed
		 */
		public static function getItemsPerPage()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$config = & JFactory::getConfig();
			if (intval($cfg->nr_items_per_page) > 0)
				return $cfg->nr_items_per_page;
			else
				return $config->getValue('config.list_limit');
		}

		/**
		 * @return mixed
		 */
		public static function &getCategoryModel()
		{
			JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'thefactory' . DS . 'category' . DS . 'models');
			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			return $catModel;
		}

		/**
		 * @return mixed
		 */
		public static function &getCategoryTable()
		{
			JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'thefactory' . DS . 'category' . DS . 'tables');
			$catTable =& JTable::getInstance('Category', 'JTheFactoryTable');
			return $catTable;
		}

		/**
		 *
		 */
		public static function getAdminSubmenu()
		{

			// Insert admin submenu
			$menu = JToolBar::getInstance('submenu');
			if (!count($menu->getItems())) {
				$lang =& JFactory::getLanguage();
				$lang->load('com_rbids.sys');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_LIST'), 'index.php?option=com_rbids&task=offers');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_PAYMENTS'), 'index.php?option=com_rbids&task=payments.listing');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_MESSAGES'), 'index.php?option=com_rbids&task=comments_administrator');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_RATINGS'), 'index.php?option=com_rbids&task=reviews_administrator');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_REPORTED'), 'index.php?option=com_rbids&task=reported_offers');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_USERS'), 'index.php?option=com_rbids&task=users');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_SETTINGS'), 'index.php?option=com_rbids&task=settingsmanager');
				JSubMenuHelper::addEntry(JText::_('COM_RBIDS_MENU_ABOUT'), 'index.php?option=com_rbids&task=about.main');
			}
		}

		/**
		 * Display payment manager icons
		 *
		 * @static
		 *
		 */
		public static function getPaymentManagerIcons()
		{
			echo "<div id = 'cpanel' class='payment_manager_icons'>";

			$link = 'index.php?option=com_rbids&amp;task=orders.listing';
			JTheFactoryAdminHelper::quickiconButton($link, 'admin/paymentitems.png', JText::_("COM_RBIDS_VIEW_ORDERS"));

			$link = 'index.php?option=com_rbids&amp;task=payments.listing';
			JTheFactoryAdminHelper::quickiconButton($link, 'admin/payments.png', JText::_("COM_RBIDS_VIEW_PAYMENTS"));

			$link = 'index.php?option=com_rbids&amp;task=balances.listing';
			JTheFactoryAdminHelper::quickiconButton($link, 'admin/payments.png', JText::_("COM_RBIDS_USER_PAYMENT_BALANCES"));


			echo "</div>
				 <div style = 'height:100px'>&nbsp;</div>
				 <div style = 'clear:both;'></div>
				 ";


		} // End Method

		/**
		 * Get active theme
		 *
		 * @return mixed
		 */
		public static function getActiveTheme()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			return $cfg->theme;
		}


	} // End Class

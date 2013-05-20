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

	/**
	 * JHTMLAuctiontype
	 */
	abstract class JHTMLInvite
	{
		/**
		 *  selectInviteGroups
		 *
		 * @param $auctionInvited
		 *
		 * @return mixed
		 */
		static function selectInviteGroups($auctionInvited)
		{
			$cfg =& JTheFactoryHelper::getConfig();

			if ('users' == $cfg->aucttype_invite_interface) {
				return null;
			}

			$almostInvited = JRequest::getVar('almostinvited', array(), 'cookie', 'array');
			$almostInvitedGroups = isset($almostInvited['groups']) ? $almostInvited['groups'] : array();

			$invited = array_merge($auctionInvited, $almostInvitedGroups);

			return JHtml::_('access.usergroup', 'invitegroups[]', $invited, 'multiple="multiple" size="10" style="width:154px;height:195px;"', false);

		}

		/**
		 * selectInviteUsers
		 *
		 * @param $auctionInvited
		 *
		 * @return mixed
		 */
		static function selectInviteUsers($auctionInvited)
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration("
					MyUtil = new Object();
					MyUtil.selectFilterData = new Object();
					MyUtil.selectFilter = function(selectId, filter) {
						var list = document.getElementById(selectId);
						//if we don't have a list of all the options, cache them now'
						if(!MyUtil.selectFilterData[selectId]) {
							MyUtil.selectFilterData[selectId] = new Object();
							for(var i = 0; i < list.options.length; i++){
							        MyUtil.selectFilterData[selectId][list.options[i].value] = list.options[i];
							 }
						}

						//go through all the options in the current list and set the selection status to the cached options
						for(var i = 0; i < list.options.length; i++) {
							MyUtil.selectFilterData[selectId][list.options[i].value]  = list.options[i];
						}

						//remove all elements from the list
						list.options.length = 0;

						//add elements from cache if they match filter
						for(var id in MyUtil.selectFilterData[selectId]) {
							var o = MyUtil.selectFilterData[selectId][id];
							if(o.text.toLowerCase().indexOf(filter.toLowerCase()) >= 0) list.add(o, null);
						}
					}
			");

			if ('groups' == $cfg->aucttype_invite_interface) {
				return null;
			}

			$almostInvited = JRequest::getVar('almostinvited', array(), 'cookie', 'array');
			$almostInvitedUsers = isset($almostInvited['users']) ? $almostInvited['users'] : array();


			$invited = array_merge($auctionInvited, $almostInvitedUsers);

			$userProfile = RBidsHelperTools::getUserProfileObject();
			$sellers = $userProfile->getUserList(0, 1000, array('isBidder' => 1));

			$opts = array();
			foreach ($sellers as $seller) {
				// Use joomla user id not user integration id

				// Checking for userid due to Love Integration using user_id instead of userid used by reverse
				$sellerId = isset($seller->userid) ? $seller->userid : $seller->user_id;
				if ($sellerId) {
					// Create option only for users that have user profile for component used by profile integration
					$opts[] = JHTML::_('select.option', $sellerId, $seller->username);
				}
			}

			$r = "<input type='text'
					 id='filterInviteUsers'
					 style='width:150px;height:15px;margin-bottom: 5px;'
					 value=''
					 onkeyup=\"MyUtil.selectFilter('inviteusers', this.value)\" /> <br />";
			$r .= JHTML::_('select.genericlist', $opts, 'inviteusers[]', 'multiple="multiple" size="10" style="width:154px;height:175px;" ', 'value', 'text', $invited);
			return $r;
		}

		/**
		 * buttonInvitesCookie
		 *
		 * @return string
		 */
		static function buttonInvitesCookie()
		{
			return '<input type="button"  class="button" value="' . JText::_('Invite bidders') . '" onclick="cookInvites();" />';
		}

		/**
		 * buttonInvitesReset
		 *
		 * @return string
		 */
		static function buttonInvitesReset()
		{
			return '<input type="button"  class="button" value="' . JText::_('Cancel') . '" onclick="resetCookInvites();" />';
		}

		/**
		 * selectSeller
		 *
		 * @param $auction
		 *
		 * @return mixed
		 */
		static function selectSeller($auction)
		{

			if (!$auction->isEditable()) {
				return null;
			}

			$userProfile = RBidsHelperTools::getUserProfileObject();
			$sellers = $userProfile->getUserList(0, 1000, array('isSeller' => 1));

			$opts = array();
			$opts[] = JHTML::_('select.option', '', JText::_('COM_RBIDS_SELECT_SELLER'));
			foreach ($sellers as $seller) {
				// Checking for userid due to Love Integration using user_id instead of userid used by reverse
				$sellerId = isset($seller->userid) ? $seller->userid : $seller->user_id;
				if ($sellerId) {
					// Create option only for users that have user profile for component used by profile integration
					$opts[] = JHTML::_('select.option', $sellerId, $seller->username);
				}
			}

			return JHTML::_('select.genericlist', $opts, 'userid', 'class="required"', 'value', 'text', $auction->userid);
		}


		/**
		 * invitesButton
		 *
		 * @param $auction
		 *
		 * @return null|string
		 */
		static function invitesButton($auction)
		{
			if ($auction->auction_type != AUCTION_TYPE_INVITE) {
				return null;
			}
			$rbidsImagesFolder = JURI::root() . 'components/' . APP_EXTENSION . '/images';
			$html = JHTML::link('index.php?option=' . APP_EXTENSION . '&controller=invites&task=getLists&auctionId=' . $auction->id . '&mode=form&tmpl=component',
				'<input type="image" src="' . $rbidsImagesFolder . '/user_invitation.png" alt="' . JText::_('COM_RBIDS_INVITE_BIDDERS') . '" title="' . JText::_('COM_RBIDS_INVITE_BIDDERS') . '" />',
				'style="text - decoration:none;" class="modal" rel="{
			handler:\'iframe\', size: {x:550,y:400}, ajaxOptions: { method: \'get\' } }"');
			/*			$html = JHTML::link('index.php?option=' . APP_EXTENSION . '&controller=invites&task=getLists&auctionId=' . $auction->id . '&mode=form&tmpl=component',
							'<input type="button" class="button" value="' . JText::_('COM_RBIDS_INVITE_BIDDERS') . '" />',
							'style="text-decoration:none;" class="modal" rel="{handler:\'iframe\', size: {x:550,y:400}, ajaxOptions: { method: \'get\' } }"');*/

			return $html;
		}

		/**
		 * @param $auction
		 *
		 * @return string
		 */
		static function invitesFormHiddenInputs($auction)
		{
			$html = '<input type="hidden" name="option" value="com_rbids" />
                                    <input type="hidden" name="controller" value="invites" />
                                    <input type="hidden" name="task" value="save" />
				    <input type="hidden" name="mode" value="form" />
                                    <input type="hidden" name="auctionId" value="' . $auction->id . '" />';


			return $html;
		}

		/**
		 * @return string
		 */
		static function invitesFormSubmit()
		{
			return '<input type="submit" class="button" value="' . JText::_('COM_RBIDS_INVITE_BIDDERS') . '" />
				   <input type="button" class="button" value="' . JText::_('COM_RBIDS_CANCEL') . '" onclick="window.parent.SqueezeBox.close();" />

			';
		}

		/**
		 * invitedAuctions
		 *
		 * @return bool|string
		 */
		static function invitedAuctions()
		{
			$my = JFactory::getUser();
			// Guest not allowed by default
			if (!$my->id) {
				return false;
			}

			$userGroups = implode(',', $my->groups);

			$db = JFactory::getDbo();
			// Use distinct to prevent doubled listing
			// when invitation is created using user and group methods
			$db->setQuery("SELECT DISTINCT `a`.`id`,
								       `a`.`title`,
								       `a`.`userid`,
								       `u`.`username`,
								       IF(`a`.`end_date` > NOW(), 'published', 'expired') AS `status`
						 FROM `#__rbid_auctions` AS `a`
						 LEFT JOIN `#__users` AS `u` ON `u`.`id` = `a`.`userid`
						 LEFT JOIN `#__rbid_invites` AS `i`
						 ON `a`.`id` = `i`.`auctionId`
						 WHERE ((`i`.`guestType` = 'user' AND `i`.`guestId` = '{$my->id}')
						 OR (`i`.`guestType` = 'group' AND `i`.`guestId` IN('{$userGroups}')))
					");
			$auctions = $db->loadObjectList();

			// Invited for auctions
			return $auctions;
		}

	} // End Class

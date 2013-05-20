<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	-------------------------------------------------------------------------*/


	defined('_JEXEC') or die('Restricted access');

	/**
	 * InvitesController
	 */
	class InvitesController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		/**
		 * Constructor
		 *
		 * @param array $config
		 */
		public function __construct($config = array())
		{
			parent::__construct($config);
		}

		/**
		 * Load users | groups list to invite
		 * Modal
		 */
		public function getLists()
		{

			$cfg =& JTheFactoryHelper::getConfig();
			$auctionId = JRequest::getInt('auctionId', 0);
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration("
				/* Styles available in modal */
				.button{
					background: url('')  #FFFFFF !important;
					margin-right:30px;
				}
				.label {
					font-size:11px;
					font-weight:bold;
				}
			");


			$model = $this->getModel('invites', 'rbidsModel');
			$model->loadAuction($auctionId);
			$layout = '';

			if ($model->getAuction()->id && !$model->getAuction()->isMyAuction()) {
				return false;
			}
			// Mode -> cookie|form
			$mode = JRequest::getCmd('mode', 'cookie');

			$almostInvitedUsers = $almostInvitedGroups = array();
			if ($mode == 'cookie') {
				$almostInvited = JRequest::getVar('rbidsCookInvites', '', 'cookie', 'string');

				@list($control, $u, $g) = explode('#', $almostInvited);
				if ($control) {
					$almostInvitedUsers = empty($u) ? array() : explode(',', $u);
					$almostInvitedGroups = empty($g) ? array() : explode(',', $g);
				}

				$layout = 't_invitescookie.tpl';

			} elseif ($mode == 'form') {
				$layout = 't_invitesform.tpl';
			}

			switch ($cfg->aucttype_invite_interface) {
				case 'users':
					$model->loadInvites('user', $almostInvitedUsers);
					break;
				case 'groups':
					$model->loadInvites('group', $almostInvitedGroups);
					break;
				case 'both':
					$model->loadInvites('user', $almostInvitedUsers);
					$model->loadInvites('group', $almostInvitedGroups);
					break;
			}

			$view = $this->getView('inviteForm');
			$view->setModel($model);

			$view->display($layout);
		}

		/**
		 * Save invites for Form mode (in my auction details page)
		 */
		public function save()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$auctionId = JRequest::getInt('auctionId', 0);
			$mode = JRequest::getWord('mode', 'cookie');

			$model = $this->getModel('invites', 'rbidsModel');

			if (!$model->loadAuction($auctionId) || !$model->getAuction()->isMyAuction()) {
				return;
			}

			if ('users' == $cfg->aucttype_invite_interface) {
				$inviteUsers = JRequest::getVar('inviteusers', array(), 'default', 'array');
				$model->save('user', $inviteUsers);

			} elseif ('groups' == $cfg->aucttype_invite_interface) {
				$inviteGroups = JRequest::getVar('invitegroups', array(), 'default', 'array');
				$model->save('group', $inviteGroups);

			} elseif ('both' == $cfg->aucttype_invite_interface) {
				$inviteUsers = JRequest::getVar('inviteusers', array(), 'default', 'array');
				$inviteGroups = JRequest::getVar('invitegroups', array(), 'default', 'array');

				$model->save('user', $inviteUsers);
				$model->save('group', $inviteGroups);

			}

			if ('cookie' == $mode) {
				//$this->setRedirect('index.php?option=com_rbids&task=viewbids&id=' . $model->getAuction()->id, JText::_('COM_RBIDS_INVITES_SAVED'));
			} else {
				$doc = JFactory::getDocument();
				$doc->addScriptDeclaration("
					window.parent.SqueezeBox.close();
				");
			}
		}
	} // End Class

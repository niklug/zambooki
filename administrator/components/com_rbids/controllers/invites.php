<?php

	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');

	class JRbidsAdminControllerInvites extends JController
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

			$modelInvites = $this->getModel('invites', 'JRbidsAdminModel');
			$modelInvites->loadAuction($auctionId);
			$layout = '';

			if ($modelInvites->getAuction()->id && !$modelInvites->getAuction()->isMyAuction()) {
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

				$layout = 'invitescookie';

			} elseif ($mode == 'form') {
				$layout = 'invitesform';
			}

			switch ($cfg->aucttype_invite_interface) {
				case 'users':
					$modelInvites->loadInvites('user', $almostInvitedUsers);
					break;
				case 'groups':
					$modelInvites->loadInvites('group', $almostInvitedGroups);
					break;
				case 'both':
					$modelInvites->loadInvites('user', $almostInvitedUsers);
					$modelInvites->loadInvites('group', $almostInvitedGroups);
					break;
			}

			$view = $this->getView('Inviteform', 'html', 'JRBidsAdminView');
			$view->setModel($modelInvites);
			$view->setLayout($layout);

			$view->display();
		}

		/**
		 * Save invites for Form mode (in my auction details page)
		 */
		public function save()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$auctionId = JRequest::getInt('auctionId', 0);
			$mode = JRequest::getWord('mode', 'cookie');

			$model = $this->getModel('invites', 'JRbidsAdminModel');

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

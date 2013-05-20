<?php
	jimport('joomla.application.component.view');
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	-------------------------------------------------------------------------*/

	class JRBidsAdminViewInviteform extends JView
	{
		/**
		 * @param null $tmpl
		 *
		 * @return mixed|void
		 */
		public function display($tmpl = null)
		{

			$doc = JFactory::getDocument();
			$doc->addScript(JURI::root() . 'components/com_rbids/js/invite.js');


			$model = $this->getModel('invites');
			$invites = $model->getInvites();
			$auction = $model->getAuction();

			$lists = array();
			$lists['users'] = JHTML::_('Invite.selectInviteUsers', isset($invites['user']) ? $invites['user'] : array());
			$lists['groups'] = JHTML::_('Invite.selectInviteGroups', isset($invites['group']) ? $invites['group'] : array());
			// for invitescookie template
			$lists['buttonInvite'] = JHTML::_('Invite.buttonInvitesCookie');
			$lists['buttonReset'] = JHTML::_('Invite.buttonInvitesReset');
			// for invitesform template
			$lists['hiddenInputs'] = JHTML::_('Invite.invitesFormHiddenInputs', $auction);
			$lists['submitInvites'] = JHTML::_('Invite.invitesFormSubmit');


			$this->assign('lists', $lists);

			parent::display($tmpl);
		}
	} // End Class

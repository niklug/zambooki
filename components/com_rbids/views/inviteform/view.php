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


	class rbidsViewInviteForm extends JTheFactorySmartyView
	{
		/**
		 * @param null $tmpl
		 */
		public function display($tmpl = null)
		{

			$doc = JFactory::getDocument();
			$doc->addScript(JURI::root() . 'components/' . APP_EXTENSION . '/js/jquery/jquery.js');
			$doc->addScript(JURI::root() . 'components/' . APP_EXTENSION . '/js/jquery/jquery.noconflict.js');
			$doc->addScript(JURI::root() . 'components/' . APP_EXTENSION . '/js/invite.js');
			$model = $this->getModel('invites');
			$invites = $model->getInvites();
			$auction = $model->getAuction();

			$lists = array();
			$lists['users'] = JHTML::_('Invite.selectInviteUsers', isset($invites['user']) ? $invites['user'] : array());
			$lists['groups'] = JHTML::_('Invite.selectInviteGroups', isset($invites['group']) ? $invites['group'] : array());
			// for t_invitescookie.tpl
			$lists['buttonInvite'] = JHTML::_('Invite.buttonInvitesCookie');
			$lists['buttonReset'] = JHTML::_('Invite.buttonInvitesReset');
			// for t_invitesform.tpl
			$lists['hiddenInputs'] = JHTML::_('Invite.invitesFormHiddenInputs', $auction);
			$lists['submitInvites'] = JHTML::_('Invite.invitesFormSubmit');


			$this->assign('lists', $lists);

			parent::display($tmpl);
		}
	} // End Class

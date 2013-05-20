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
	 * @subpackage: Events
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryEventPermissions extends JTheFactoryEvents
	{
		public function onBeforeExecuteTask(&$stopexecution)
		{
			$task = JRequest::getCmd('task', 'listauctions');
			$controllerClass = JRequest::getWord('controller');

			$RBAcl = &RBidsHelperTools::getRbidsACL();
			$app =& JFactory::getApplication();
			$cfg =& JTheFactoryHelper::getConfig();
			$user =& JFactory::getUser();
			if (strpos($task, '.') !== FALSE) {
				$task = explode('.', $task);
				$controllerClass = $task[0];
				$task = $task[1];
			}
			if (in_array($task, $RBAcl->publicTasks))
				return; //Anon Task ok
			if (!$user->id) {
				//Unlogged user, check if task is in ANON TASKS
				//By default tasks need to be done by logged users

				$app->redirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_YOU_NEED_TO_LOGIN_IN_ORDER_TO_ACCESS_THIS_SECTION"));
				$stopexecution = true;
				return;
			}
			//Only Logged user from now on

			$userprofile = null;
			if (!in_array($task, $RBAcl->anonTasks)) {
				//User must have his profile Filled for this task
				$userprofile = RBidsHelperTools::getUserProfileObject();
				if (!$userprofile->checkProfile($user->id)) {
					//Profile is not filled! we must redirect
					if (!$r = RBidsHelperTools::redirectToProfile()) {
						$r = RBidsHelperRoute::getUserdetailsRoute(null, false);
					}
					$app->redirect($r, JText::_("COM_RBIDS_ERR_MORE_USER_DETAILS"));
					$stopexecution = true;
					return;
				}
			}

			if (!$cfg->enable_acl || !isset($RBAcl->taskmapping[$task]))
				return; // no need to check other ACL Seller/Bidder taskmap

			if (!$userprofile)
				$userprofile = RBidsHelperTools::getUserProfileObject();

			$userprofile->getUserProfile();

			//$cfg->bidder_groups
			//$cfg->seller_groups
			$user_groups = JAccess::getGroupsByUser($user->id);

			$isBidder = count(array_intersect($user_groups, $cfg->bidder_groups)) > 0;
			$isSeller = count(array_intersect($user_groups, $cfg->seller_groups)) > 0;

			if ($RBAcl->taskmapping[$task] == 'seller' && !$isSeller) {
				//Task allows only SELLERS
				$app->redirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_YOU_NEED_TO_BE_A_SELLER_IN_ORDER_TO_ACCESS_THIS_SECTION"));
				$stopexecution = true;
				return;

			}

			if ($RBAcl->taskmapping[$task] == 'bidder' && !$isBidder) {
				//Task allows only BIDDERS
				$app->redirect(RBidsHelperRoute::getAuctionListRoute(), JText::_("COM_RBIDS_YOU_NEED_TO_BE_A_BIDDER_IN_ORDER_TO_ACCESS_THIS_SECTION"));
				$stopexecution = true;
				$app->close();
				return;

			}
		}

	} // End Class

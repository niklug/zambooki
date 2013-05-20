<?php
	/**------------------------------------------------------------------------
	thefactory - The Factory Class Library - v 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : thefactory
	 * @subpackage: payments
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryBalanceController extends JController
	{
		var $name = 'Balance';
		var $_name = 'Balance';
		var $description = 'Add funds to your balance';

		public function __construct($config = array())
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$lang =& JFactory::getLanguage();
			$lang->load('thefactory.payments');

			$config['view_path'] = $MyApp->app_path_front . 'payments' . DS . "views";

			parent::__construct($config);
			JTheFactoryHelper::modelIncludePath('payments');
			JTheFactoryHelper::tableIncludePath('payments');

		}

		public function getView($name = '', $type = 'html', $prefix = '', $config = array())
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$config['template_path'] = $MyApp->app_path_front . 'payments' . DS . "views" . DS . strtolower($name) . DS . "tmpl";
			return parent::getView($name, $type, 'JTheFactoryViewBalance', $config);
		}

		public function addFunds()
		{
			$Itemid = JRequest::getInt('Itemid');
			$balance =& JModel::getInstance('balance', 'JTheFactoryModel');
			$currency =& JModel::getInstance('currency', 'JTheFactoryModel');
			$user_balance = $balance->getUserBalance();
			$default_currency = $currency->getDefault();
			$view = $this->getView('balance');
			$view->assign('balance', $user_balance);
			$view->assign('currency', $default_currency);
			$view->assign('Itemid', $Itemid);
			$view->display('addfunds');

		}

		public function checkout()
		{
			$Itemid = JRequest::getInt('Itemid');
			$price = JRequest::getFloat('amount');
			if ($price <= 0) {
				JError::raiseWarning(510, JText::_("FACTORY_AMOUNT_MUST_BE"));
				$this->setRedirect("index.php?option=" . APP_EXTENSION . "&task=payments.history&Itemid=" . $Itemid);
				return;
			}
			$currency =& JModel::getInstance('currency', 'JTheFactoryModel');
			$default_currency = $currency->getDefault();

			$modelorder = JTheFactoryPricingHelper::getModel('orders');
			$item = new stdClass();
			$item->itemname = $this->name;
			$item->itemdetails = JText::_($this->description);
			$item->iteminfo = null;
			$item->price = $price;
			$item->currency = $default_currency;
			$item->quantity = 1;
			$item->params = '';

			$order = $modelorder->createNewOrder($item, $price, $default_currency, null, 'P');
			$this->setRedirect("index.php?option=" . APP_EXTENSION . "&task=orderprocessor.checkout&orderid=$order->id&Itemid=" . $Itemid);

		}

		public function reqWithdraw()
		{
			$reqAmount = JRequest::getFloat('amount');
			$currency = JRequest::getString('currency');
			$user = JFactory::getUser();
			$Itemid = JRequest::getInt('Itemid');

			$modelBalance = JModel::getInstance('balance', 'JTheFactoryModel');
			$userBalance = $modelBalance->getUserBalance();

			// Amount requested must be positive
			if ($reqAmount <= 0) {
				JError::raiseWarning(510, JText::_("FACTORY_AMOUNT_MUST_BE"));
				$this->setRedirect("index.php?option=" . APP_EXTENSION . "&task=payments.history&Itemid=" . $Itemid);
				return;
			}

			// Amount requested must be lower that balance
			if ((RBidsHelperPrices::convertToDefaultCurrency($reqAmount, $currency) +
				RBidsHelperPrices::convertToDefaultCurrency($userBalance->req_withdraw, $userBalance->currency)) >
				RBidsHelperPrices::convertToDefaultCurrency($userBalance->balance, $userBalance->currency)
			) {
				JError::raiseWarning(510, JText::_("FACTORY_REQ_AMOUNT_EXCEED_YOUR_BALANCE"));
				$this->setRedirect("index.php?option=" . APP_EXTENSION . "&task=payments.history&Itemid=" . $Itemid);
				return;
			}

			// Everything is ok we can set now user request
			if ($modelBalance->setReqWithdraw($reqAmount)) {
				// Create email notifications event
				JTheFactoryEventsHelper::triggerEvent('onAfterReqWithdraw', array($user->id));

				$msg = JText::_('FACTORY_REQUESTED_WITHDRAW_OK');
				$this->setRedirect("index.php?option=" . APP_EXTENSION . "&task=payments.history&Itemid=" . $Itemid, $msg);
				return;

			}


		}
	}

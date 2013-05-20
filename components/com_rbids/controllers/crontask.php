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

	class CronTaskController extends JController
	{
		var $_name = 'cronTask';
		var $name = 'cronTask';

		/**
		 * CronTaskController constructor
		 *
		 * @param array $config
		 */
		public function __construct($config = array())
		{
			$config['default_task'] = 'cron';
			parent::__construct($config);
		}

		/**
		 * cron task
		 */
		public function cron()
		{

			/* cron script Pass and authentication*/
			$cfg =& JTheFactoryHelper::getConfig();
			$config =& JFactory::getConfig();
			$pass = JRequest::getVar('pass');
			$debug = JRequest::getVar('debug', 0);

			$date = new JDate();
                      
			$nowMysql = $date->toSQL(false);
                        
                    
			$db = JFactory::getDbo();

			JTheFactoryHelper::modelIncludePath('payments');
			JTheFactoryHelper::tableIncludePath('payments');

			$log = JTable::getInstance('Log', 'Table');
			$log->priority = 'log';
			$log->event = 'cron';
			$log->logtime = $nowMysql;
			$logtext = "";

			if ($cfg->cron_password !== $pass) {
				//Bad Password, log and exit;
				$log->log = JText::_("COM_RBIDS_BAD_PASSWORD_USED") . " > $pass";
				$log->store();
				die(JText::_("COM_RBIDS_ACCESS_DENIED"));
			}
			@set_time_limit(0);
			@ignore_user_abort(true);

			/*********************************************************************
			 * Close all expired auctions
			 **********************************************************************/
			$query = "SELECT `a`.*
					  FROM `#__rbid_auctions` AS `a`
					  WHERE '$nowMysql' > `a`.`end_date`
					  AND `a`.`close_offer` != 1
					  AND `a`.`published` = 1
					  AND `a`.`close_by_admin` != 1
				";
			$db->setQuery($query);
			$rows = $db->loadObjectList();
                        
                     
			$auction =& JTable::getInstance('auctions', 'Table');
			if (count($rows)) {
				$logtext .= sprintf("Closed %d auctions\r\n", count($rows));
				foreach ($rows as $r) {

					$auction->bind($r);
					$auction->close_offer = 1; //close expired
					$auction->closed_date = $nowMysql;
					$auction->modified = $nowMysql;

					if ($auction->store(true)) //store with quicksave
					{
						JTheFactoryEventsHelper::triggerEvent('onAfterCloseAuction', array($auction));

						//Choose a winner
						if ($cfg->select_winner_automatic) {
							//Winner is chosen automatically
							$auction->ChooseWinner();
						} else {
							//Manual winner selection
							$usr = JFactory::getUser($auction->userid);
							$auction->SendMails(array($usr), 'bid_choose_winner');
						}
					}
				}
			} /* End Close all expired auctions */

			/***********************************************************************
			 * Daily Jobs (things that should run once a day )
			 ***********************************************************************/
			$daily = JRequest::getVar('daily', '');
			if ($daily) {
				/*********************************************************************
				 * Notify upcoming expirations
				 **********************************************************************/
				$query = "SELECT `a`.*
						 FROM `#__rbid_auctions` AS `a`
						 WHERE '{$nowMysql}' >= DATE_ADD(`end_date`, INTERVAL -1 DAY)
						 AND `a`.`close_offer` != 1
						 AND `published` = 1
					";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
                          
                         
				if (count($rows) > 0) {
					$logtext .= sprintf("Soon to expire: %d auctions\r\n", count($rows));
					foreach ($rows as $row) {
						$auction->bind($row);
						$usr = JFactory::getUser($auction->userid);

						$auction->SendMails(array($usr), 'bid_your_will_expire');

						$query = "SELECT `u`.*
								  FROM `#__users` AS `u`
								  LEFT JOIN `#__rbid_watchlist` AS `w` ON `u`.`id` = `w`.`userid`
								  WHERE `w`.`auction_id` = '{$auction->id}'
        						";

						$db->setQuery($query);
						$users_with_watchlist = $db->loadObjectList();

						$auction->SendMails($users_with_watchlist, 'bid_watchlist_will_expire');
					}
				}

				/********************************************************************
				 *  Conversion Rate Change
				 *
				 *********************************************************************/

				$model =& JModel::getInstance('Currency', 'JTheFactoryModel');
				$currtable =& JTable::getInstance('CurrencyTable', 'JTheFactory');

				$currencies = $model->getCurrencyList();
				$default_currency = $model->getDefault();
				$results = array();
				foreach ($currencies as $currency) {
					if ($currency->name == $default_currency) {
						$currtable->load($currency->id);
						$currtable->convert = 1;
						$currtable->store();
						$results[] = $currency->name . " ---> " . $default_currency . " = 1";
						continue;
					}
					$conversion = $model->getGoogleCurrency($currency->name, $default_currency);
					if ($conversion === false) {
						$results[] = JText::_("COM_RBIDS_ERROR_CONVERTING") . " {$currency->name} --> $default_currency";
						continue;
					}
					$currtable->load($currency->id);
					$currtable->convert = $conversion;
					$currtable->store();
					$results[] = $currency->name . " ---> " . $default_currency . " = $conversion ";
				}

				JTheFactoryEventsHelper::triggerEvent('onConversionRateChange');

				$logtext .= implode("\r\n", $results);
				$logtext .= "\r\n";

				/********************************************************************
				 *  Convert users balance to default currency
				 *********************************************************************/

				//Get balances which are not in default currency
				$query = "SELECT `id`, `balance`, `currency`
						 FROM `#__rbid_payment_balance`
						 WHERE `currency` != '{$default_currency}'
					";
				$db->setQuery($query);
				$toConvertCurrency = $db->loadObjectList();

				$balanceTable = & JTable::getInstance('BalanceTable', 'JTheFactory');

				if (count($toConvertCurrency)) {

					foreach ($toConvertCurrency as $item) {
						// Prevent accidental insertions
						if ($balanceTable->load(array('id' => $item->id))) {
							$converted = RbidsHelperPrices::convertCurrency($item->balance, $item->currency, $default_currency);

							$balanceTable->balance = $converted;
							$balanceTable->currency = $default_currency;
							$balanceTable->store();
						}
					}

					$logtext .= sprintf("Converted %d balance%s to default currency\r\n",
						count($toConvertCurrency), count($toConvertCurrency) > 1 ? 's' : '');
				}

				/**********************************************************************************************
				 *  1. Change currency for all payment items to default currency
				 *  2. Update categories price for all payment items changed
				 **********************************************************************************************/
				// 1.
				// Get Payment items that are not in default currency
				$query = "SELECT `pp`.`id`,
							 `pp`.`itemname`,
							 `pp`.`price`,
							 `pp`.`currency`
						  FROM `#__rbid_pricing` AS `pp`
						  WHERE `pp`.`pricetype` = 'fixed'
						  AND `pp`.`currency` != '{$default_currency}'
					  ";
				$db->setQuery($query);
				$paymentItemsPrice = $db->loadObjectList('id');

				$pricingTable = & JTable::getInstance('PricingTable', 'JTheFactory');
				if (count($paymentItemsPrice)) {
					foreach ($paymentItemsPrice as $item) {
						// Prevent accidental insertions
						if ($pricingTable->load(array('id' => $item->id))) {
							$converted = RbidsHelperPrices::convertCurrency($item->price, $item->currency, $default_currency);

							$pricingTable->price = $converted;
							$pricingTable->currency = $default_currency;
							$pricingTable->store();
						}

						// 2.
						// Get Payment Categories related to current itemname to update them price
						$query = "SELECT `pc`.`id`, `pc`.`price`
								  FROM `#__rbid_pricing_categories` AS `pc`
								  WHERE `pc`.`itemname` = '{$item->itemname}'
							";
						$db->setQuery($query);
						$paymentCategories = $db->loadObjectList('id');
						$pricingCategoriesTable = & JTable::getInstance('PricingCategoriesTable', 'JTheFactory');

						if (count($paymentCategories)) {
							foreach ($paymentCategories as $itemCat) {
								// Prevent accidental insertions
								if ($pricingCategoriesTable->load(array('id' => $itemCat->id))) {
									$converted = RbidsHelperPrices::convertCurrency($itemCat->price, $item->currency, $default_currency);

									$pricingCategoriesTable->price = $converted;
									$pricingCategoriesTable->store();
								}
							}
						}


					}
					$logtext .= sprintf("Converted %d payment item%s to default currency\r\n",
						count($paymentItemsPrice), count($paymentItemsPrice) > 1 ? 's' : '');
				}


				/********************************************************************
				 * SOME CLEANUP
				 * Prune old closed auctions
				 * *********************************************************************/
				$interval = intval($cfg->archive);
				if ($interval > 0) {
					$query = "SELECT id
            				FROM #__rbid_auctions
            				WHERE '$nowMysql' > DATE_ADD( closed_date, INTERVAL $interval MONTH )
            				AND close_offer =1
            				AND published =0
            				LIMIT 0 , 50";
					$db->setQuery($query);
					$ids = $db->loadResultArray();
					if (count($ids))
						foreach ($ids as $id)
							$auction->delete($id);
					$logtext .= sprintf("Flushed %d auctions\r\n", count($rows));
				}

			}
			$log->log = $logtext;
			$log->store();
			if ($debug) return;
			ob_clean();
			exit();


		}
	} // End Class

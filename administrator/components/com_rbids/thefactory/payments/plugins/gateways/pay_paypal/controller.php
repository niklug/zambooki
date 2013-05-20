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

	require_once(realpath(dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..' . DS . 'classes' . DS . 'gateways.php'));

	class Pay_Paypal extends TheFactoryPaymentGateway
	{
		var $name = 'pay_paypal';
		var $fullname = 'Paypal Payment Method';
		var $action = '';

		/**
		 * Generate payment form
		 *
		 * @param  object           $order       Order object
		 * @param  object           $items       Items order object
		 * @param  array            $urls        Gateway return | notify | cancel urls
		 * @param  null             $shipping
		 * @param  null             $tax
		 *
		 * @return string
		 */
		public function getPaymentForm($order, $items, $urls, $shipping = null, $tax = null)
		{
			$modelGateways =& JModel::getInstance('Gateways', 'JTheFactoryModel');
			$params = $modelGateways->loadGatewayParams($this->name);

			if ($params->get('use_sandbox', 0)) {
				$this->action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			} else {
				$this->action = "https://www.paypal.com/cgi-bin/webscr";
			}
			$paypal_address = $params->get('paypalemail', '');
			$result = "<form name='paypalForm' action='" . $this->action . "' method='post'>";
			$result .= "<input type='hidden' name='cmd' value='_cart' /><input type='hidden' name='upload' value='1' />";
			$result .= "<input type='hidden' name='business' value='" . $paypal_address . "' />";
			$result .= "<input type='hidden' name='invoice' value='" . $order->id . "' />";
			$result .= "<input type='hidden' name='return' value='" . $urls['return_url'] . "' />";
			$result .= "<input type='hidden' name='cancel_return' value='" . $urls['cancel_url'] . "' />";
			$result .= "<input type='hidden' name='notify_url' value='" . $urls['notify_url'] . "' />";
			$result .= "<input type='hidden' name='rm' value='2' />";
			$result .= "<input type='hidden' name='no_note' value='1' />";
			$result .= "<input type='hidden' name='currency_code' value='" . $order->order_currency . "' />";
			$result .= "<input type='image' src='https://www.paypal.com/en_US/i/btn/x-click-but23.gif' border='0' name='submit' alt='" . JText::_("FACTORY_BUY_NOW") . "' style='margin-left: 30px;' />";

			if (!$shipping)
				$result .= "<input type='hidden' name='no_shipping' value='1' />";
			if (!$tax)
				$result .= "<input type='hidden' name='tax' value='0' />";
			$discount = 0;
			for ($i = 0; $i < count($items); $i++)
				if ($items[$i]->price > 0) {
					$result .= "<input type='hidden' name='item_name_" . ($i + 1) . "' value='" . $items[$i]->itemdetails . " ' />";
					$result .= "<input type='hidden' name='item_number_" . ($i + 1) . "' value='" . $items[$i]->itemname . " ' />";
					$result .= "<input type='hidden' name='amount_" . ($i + 1) . "' value='" . $items[$i]->price . " ' />";
					$result .= "<input type='hidden' name='quantity_" . ($i + 1) . "' value='" . $items[$i]->quantity . " ' />";
				} else $discount += $items[$i]->price;
			$discount = -$discount;
			if ($discount > 0)
				$result .= "<input type='hidden' name='discount_amount_cart' value='{$discount}' />";
			$result .= "</form>";
			return $result;
		}

		/**
		 * Generate withdraw payment form
		 */
		public function getWithdrawPaymentForm($order, $items, $urls, $receiver = null, $shipping = null, $tax = null, $info = null)
		{
			if (!$receiver) return null;

			$modelGateways =& JModel::getInstance('Gateways', 'JTheFactoryModel');
			$params = $modelGateways->loadGatewayParams($this->name);

			$this->action = "https://www.paypal.com/cgi-bin/webscr";
			if ($params->get('use_sandbox', 0)) {
				$this->action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			}
			// Site business PayPal email
			$paypal_address = $params->get('paypalemail', '');

			$result = $info;
			$result .= "<form name='paypalForm' action='" . $this->action . "' method='post' target='_blank'>";
			$result .= "<input type='hidden' name='cmd' value='_cart' /><input type='hidden' name='upload' value='1' />";
			$result .= "<input type='hidden' name='business' value='" . $receiver->paypalemail . "' />";
			$result .= "<input type='hidden' name='invoice' value='" . $order->id . "' />";
			$result .= "<input type='hidden' name='custom' value='withdraw' />";
			$result .= "<input type='hidden' name='return' value='" . $urls['return_url'] . "' />";
			$result .= "<input type='hidden' name='cancel_return' value='" . $urls['cancel_url'] . "' />";
			$result .= "<input type='hidden' name='notify_url' value='" . $urls['notify_url'] . "' />";
			$result .= "<input type='hidden' name='rm' value='2' />";
			$result .= "<input type='hidden' name='no_note' value='1' />";
			$result .= "<input type='hidden' name='currency_code' value='{$order->order_currency}' />";
			$result .= "<input type='image' src='" . JURI::base() . "components/" . APP_EXTENSION . "/assets/images/pay_now_big.png' border='0' name='submit' alt='" . JText::_("FACTORY_PAY_NOW") . "' title='" . JText::_("FACTORY_PAY_NOW") . "' style='width:150px;height:60px; border:none;background:none;margin-left: 30px;float:left;' />";
			// Back to listing
			$result .= JHtml::link('index.php?option=' . APP_EXTENSION . '&task=balances.listing', JText::_('FACTORY_NOT_RIGHT_NOW'), array('class' => 'pay_not_now'));

			if (!$shipping)
				$result .= "<input type='hidden' name='no_shipping' value='1' />";
			if (!$tax)
				$result .= "<input type='hidden' name='tax' value='0' />";
			$discount = 0;
			for ($i = 0; $i < count($items); $i++)
				if ($items[$i]->price > 0) {
					$result .= "<input type='hidden' name='item_name_" . ($i + 1) . "' value='" . $items[$i]->itemdetails . " ' />";
					$result .= "<input type='hidden' name='item_number_" . ($i + 1) . "' value='" . $items[$i]->itemname . " ' />";
					$result .= "<input type='hidden' name='amount_" . ($i + 1) . "' value='" . $items[$i]->price . " ' />";
					$result .= "<input type='hidden' name='quantity_" . ($i + 1) . "' value='" . $items[$i]->quantity . " ' />";
				} else $discount += $items[$i]->price;
			$discount = -$discount;
			if ($discount > 0)
				$result .= "<input type='hidden' name='discount_amount_cart' value='" . $discount . "' />";
			$result .= "</form>";
			return $result;
		}

		/**
		 * Process IPN
		 *
		 * @return mixed
		 */
		public function processIPN()
		{
			$model =& JModel::getInstance('Gateways', 'JTheFactoryModel');
			$params = $model->loadGatewayParams($this->name);
			$paypal_address = $params->get('paypalemail', '');

			$paylog =& JTable::getInstance('PaymentLogTable', 'JTheFactory');
			$date = new JDate();
			$paylog->date = $date->toSQL();
			$paylog->amount = JRequest::getVar('mc_gross');
			$paylog->currency = JRequest::getVar('mc_currency');
			$paylog->refnumber = JRequest::getVar('txn_id');
			$paylog->invoice = JRequest::getVar('invoice');
			$paylog->ipn_response = print_r($_REQUEST, true);
			$paylog->ipn_ip = $_SERVER['REMOTE_ADDR'];
			$paylog->status = 'error';
			$paylog->userid = null;
			$paylog->orderid = JRequest::getVar('invoice');
			$paylog->payment_method = $this->name;

			$receiver_email = JRequest::getVar('receiver_email');
			$payment_status = JRequest::getVar('payment_status');
			switch ($payment_status) {
				case "Completed":
				case "Processed":
					$paylog->status = 'ok';
					break;
				case "Failed":
				case "Denied":
				case "Canceled-Reversal":
				case "Canceled_Reversal":
				case "Expired":
				case "Voided":
				case "Reversed":
				case "Refunded":
					$paylog->status = 'error';
					break;
				default:
				case "In-Progress":
				case "Pending":
					$paylog->status = 'manual_check';
					break;
			}
			if ('withdraw' != JRequest::getWord('custom')) {
				if (!$this->validate_ipn() || ($receiver_email <> $paypal_address)) {
					$paylog->status = 'error';
				} elseif (!$params->get('auto_accept', 1)) {
					$paylog->status = 'manual_check';
				}
			}
			$paylog->store();

			return $paylog;
		}

		/**
		 * Validate Remote IP
		 *
		 * @return bool
		 */
		public function validate_remote_ip()
		{
			//DEV PURPOSE
			//return true;
			$paypal_iplist = gethostbynamel('www.paypal.com');
			$paypal_iplist2 = gethostbynamel('notify.paypal.com');
			$paypal_iplist = array_merge($paypal_iplist, $paypal_iplist2);

			$paypal_sandbox_hostname = 'ipn.sandbox.paypal.com';
			$remote_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

			$valid_ip = false;

			if ($paypal_sandbox_hostname == $remote_hostname) {
				$valid_ip = true;
				$hostname = 'www.sandbox.paypal.com';
			} else {
				$ips = "";
				// Loop through all allowed IPs and test if the remote IP connected here
				// is a valid IP address
				foreach ($paypal_iplist as $ip) {
					$ips .= "$ip,\n";
					$parts = explode(".", $ip);
					$first_three = $parts[0] . "." . $parts[1] . "." . $parts[2];
					if (preg_match("/^$first_three/", $_SERVER['REMOTE_ADDR'])) {
						$valid_ip = true;
					}
				}
				$hostname = 'www.paypal.com';
			}
			return $valid_ip;
		}

		/**
		 * Validate IPN
		 *
		 * @return bool|string
		 */
		public function validate_ipn()
		{

			// parse the PayPal URL
//			$url_parsed = parse_url($this->action);
			$ch = curl_init(); // Start the curl handler
//			$url_parsed = parse_url($_SERVER["HTTP_REFERER"]);

			// generate the post string from the _POST vars as well as load the
			// _POST vars into an array so we can play with them from the calling
			// script.
			$post_string = '';
			foreach ($_POST as $field => $value) {
				$post_string .= $field . '=' . urlencode($value) . '&';
			}
			$post_string .= "cmd=_notify-validate"; // append ipn command

			// open the connection to PayPal
			if (!$ch) {
				$log = JTable::getInstance('Log', 'Table');
				$log->priority = 'log';
				$log->event = 'ipn';
				$log->logtime = date('Y-m-d');
				$log->log = 'IPN not verified for invoice ' . JRequest::getVar('invoice');
				$log->store();
				return false;
			} else {
				curl_setopt($ch, CURLOPT_URL, $this->action);
				curl_setopt($ch, CURLOPT_FAILONERROR, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
				$response = curl_exec($ch); // Put result in a variable
				curl_close($ch);
			}

			if (preg_match("/VERIFIED/i", $response)) {
				return true;
			}
			return false;
			/*			$fp = fsockopen($url_parsed['host'], "80", $err_num, $err_str, 30);
						$response = '';
						if (!$fp) {
							return false;
						} else {
							// Post the data back to paypal
							fputs($fp, "POST " . $url_parsed['path'] . " HTTP/1.1\r\n");
							fputs($fp, "Host: " . $url_parsed['host'] . "\r\n");
							fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
							fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
							fputs($fp, "Connection: close\r\n\r\n");
							fputs($fp, $post_string . "\r\n\r\n");
							// loop through the response from the server and append to variable
							while (!feof($fp)) {
								$response .= fgets($fp, 1024);
							}
							fclose($fp); // close connection
						}

						return stristr($response, "VERIFIED");*/

		}

	}



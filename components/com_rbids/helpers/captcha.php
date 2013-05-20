<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');
	class RBidsHelperCaptcha
	{
		static function init_captcha()
		{
			$cfg =& JTheFactoryHelper::getConfig();
			if ($cfg->enable_captcha && $cfg->recaptcha_public_key) {
				if (!function_exists('recaptcha_get_html'))
					require(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'recaptcha' . DS . 'recaptchalib.php');
				$params["theme"] = $cfg->recaptcha_theme;
				return recaptcha_get_html($cfg->recaptcha_public_key, $params);
			}
		}

		function verify_captcha()
		{
			$cfg =& JTheFactoryHelper::getConfig();

			if (!$cfg->recaptcha_private_key)
				return true;

			if (!function_exists('recaptcha_get_html')) {
				require(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'recaptcha' . DS . 'recaptchalib.php');
			}

			$recaptcha_challenge_field = JRequest::getVar('recaptcha_challenge_field');
			$recaptcha_response_field = JRequest::getVar('recaptcha_response_field');

			$resp = recaptcha_check_answer($cfg->recaptcha_private_key, $_SERVER["REMOTE_ADDR"], $recaptcha_challenge_field, $recaptcha_response_field);
			return $resp->is_valid;
		}

	}

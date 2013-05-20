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
	 * @subpackage: Smarty
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class RBidsSmarty extends JTheFactorySmarty
	{
		function __construct()
		{
			parent::__construct();

			$this->register_function('printdate', array($this, 'smarty_printdate'));
			$this->register_function('set_css', array($this, 'smarty_set_css'));
			$cfg =JTheFactoryHelper::getConfig();
			if ($cfg->theme)
				$this->template_dir = JPATH_COMPONENT_SITE . DS . 'templates' . DS . $cfg->theme . DS;

			$this->assign('cfg', $cfg);
			$this->assign('IMAGE_ROOT', JURI::root() . 'components/com_rbids/images/');
			$this->assign('AUCTION_PICTURES', AUCTION_PICTURES);
			$this->assign('AUCTION_TYPES', array(
				'AUCTION_TYPE_PUBLIC' => AUCTION_TYPE_PUBLIC,
				'AUCTION_TYPE_PRIVATE' => AUCTION_TYPE_PRIVATE,
				'AUCTION_TYPE_ONE_ON_ONE' => AUCTION_TYPE_ONE_ON_ONE,
				'AUCTION_TYPE_LIMITED' => AUCTION_TYPE_LIMITED,
				'AUCTION_TYPE_INVITE' => AUCTION_TYPE_INVITE
			));
			$this->assign('TEMPLATE_IMAGES', JURI::root() . 'components/com_rbids/templates/' . $cfg->theme . '/images/');
			$this->assign('links', new RBidsHelperRoute());
		}

		function display($tpl_name)
		{
//    var image_link_dir='/components/com_rbids/images/';
			$doc =JFactory::getDocument();
			$doc->addScriptDeclaration("var image_link_dir='" .
				JURI::root() . 'components/com_rbids/images/'
				. "';");

			parent::display($tpl_name);
		}

		function smarty_printdate($params, &$smarty)
		{
			$res = "";
			if (!empty($params['date'])) {
				$cfg =JTheFactoryHelper::getConfig();
				$dateformat = $cfg->date_format;
				if ($params['use_hour'])
					$dateformat .= " H:i";
				$res = JHtml::date($params['date'], $dateformat, false);
			}
			return $res;
		}

		//Used for cloaking emails
		function smarty_rbids_print_encoded($params, &$smarty)
		{
			$extra = '';

			if (empty($params['address'])) {
				return "";
			} else {
				$address = $params['address'];
			}

			$text = $address;
			$encode = (empty($params['encode'])) ? 'none' : $params['encode'];

			if (!in_array($encode, array('javascript', 'javascript_charcode', 'hex', 'none'))) {
				$smarty->trigger_error("print_encoded: 'encode' parameter must be none, javascript or hex");
				return;
			}

			if ($encode == 'javascript') {
				$string = 'document.write(\'' . $text . '\');';

				$js_encode = '';
				for ($x = 0; $x < strlen($string); $x++) {
					$js_encode .= '%' . bin2hex($string[$x]);
				}

				return '<script type="text/javascript">eval(unescape(\'' . $js_encode . '\'))</script>';

			} elseif ($encode == 'javascript_charcode') {
				$string = $text;

				for ($x = 0, $y = strlen($string); $x < $y; $x++) {
					$ord[] = ord($string[$x]);
				}

				$_ret = "<script type=\"text/javascript\" language=\"javascript\">\n";
				$_ret .= "<!--\n";
				$_ret .= "{document.write(String.fromCharCode(";
				$_ret .= implode(',', $ord);
				$_ret .= "))";
				$_ret .= "}\n";
				$_ret .= "//-->\n";
				$_ret .= "</script>\n";

				return $_ret;


			} elseif ($encode == 'hex') {

				preg_match('!^(.*)(\?.*)$!', $address, $match);
				if (!empty($match[2])) {
					$smarty->trigger_error("mailto: hex encoding does not work with extra attributes. Try javascript.");
					return;
				}
				$text_encode = '';
				for ($x = 0; $x < strlen($text); $x++) {
					$text_encode .= '&#x' . bin2hex($text[$x]) . ';';
				}

				$mailto = "&#109;&#97;&#105;&#108;&#116;&#111;&#58;";
				return $text_encode;

			} else {
				// no encoding
				return $text;

			}


		}

		function smarty_set_css($params, &$smarty)
		{
			$cfg =JTheFactoryHelper::getConfig();
			$doc =JFactory::getDocument();
			if ($cfg->theme != "" && file_exists(JPATH_ROOT . "/components/com_rbids/templates/" . strtolower($cfg->theme) . "/bid_template.css"))
				$doc->addStyleSheet(JURI::root() . "components/com_rbids/templates/" . strtolower($cfg->theme) . "/bid_template.css");
			else
				$doc->addStyleSheet(JURI::root() . "components/com_rbids/templates/default/bid_template.css");
		}


	}

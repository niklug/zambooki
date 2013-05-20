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

	abstract class JHTMLAuctionDate
	{
		static function calendar($isodate, $name, $attr = array())
		{
			$cfg =& JTheFactoryHelper::getConfig();

			$result = JHtml::_('calendar', $isodate, $name, $name, RBidsHelperDateTime::dateFormatConversion($cfg->date_format), $attr);

			if ($isodate) //ISODATES and JHtml::_('calendar') doesn't take kindly all formats
				$result = str_replace(' value="' . htmlspecialchars($isodate, ENT_COMPAT, 'UTF-8') . '"',
					' value="' . htmlspecialchars(JHtml::date($isodate, $cfg->date_format, false), ENT_COMPAT, 'UTF-8') . '"',
					$result
				);

			return $result;

		}

	}

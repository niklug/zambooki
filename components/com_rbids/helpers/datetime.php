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
	class RBidsHelperDateTime
	{
		/**
		 * @static
		 *
		 * @param $isodate
		 *
		 * @return string
		 */
		public static function isoDateToUTC($isodate)
		{
			$DateTime = JFactory::getDate($isodate, self::getTimeZone());
			return $DateTime->toSQL();
		}

		/**
		 * Returns the userTime zone if the user has set one,
		 * or the global config one
		 *
		 * @static
		 * @return mixed
		 */
		public static function getTimeZone()
		{
			$config = & JFactory::getConfig();
			$userTz = JFactory::getUser()->getParam('timezone');
			$timeZone = $config->getValue('config.offset');

			if ($userTz) {
				$timeZone = $userTz;
			}
			return $timeZone;
		}


		/**
		 * @static
		 *
		 * @param $isodate
		 *
		 * @return int
		 */
		public static function dateDiff($isodate)
		{
			$config = & JFactory::getConfig();
			$d1 = JFactory::getDate($isodate);
			$d2 = JFactory::getDate('now', self::getTimeZone());
			$diff = $d2->toUnix() - $d1->toUnix();

			return $diff;
		}

		/**
		 * @static
		 *
		 * @param $isodate
		 *
		 * @return string
		 */
		public static function dateToCountdown($isodate)
		{

			$diff = -self::dateDiff($isodate);

			if ($diff > 0) {
				$s = sprintf("%02d", $diff % 60);
				$diff = intval($diff / 60);
				$m = sprintf("%02d", $diff % 60);
				$diff = intval($diff / 60);
				$h = sprintf("%02d", $diff % 24);
				$d = intval($diff / 24);
				if ($d > 0)
					return "$d " . JText::_("COM_RBIDS_DAYS") . ", $h:$m:$s";
				else
					return "$h:$m:$s";
			} else
				return JText::_("COM_RBIDS_EXPIRED");


		}

		/**
		 * @static
		 *
		 * @param $dateformat
		 *
		 * @return mixed
		 */
		public static function dateFormatConversion($dateformat)
		{
			$strftime_format = $dateformat;
			$strftime_format = str_replace('%', '%%', $strftime_format);
			$strftime_format = str_replace('Y', '%Y', $strftime_format);
			$strftime_format = str_replace('y', '%y', $strftime_format);
			$strftime_format = str_replace('d', '%d', $strftime_format);
			$strftime_format = str_replace('D', '%A', $strftime_format);
			$strftime_format = str_replace('m', '%m', $strftime_format);
			$strftime_format = str_replace('F', '%B', $strftime_format);
			return $strftime_format;
		}

		/**
		 * @static
		 *
		 * @param $date
		 *
		 * @return string
		 */
		public static function DateToIso($date, $date_format = null)
		{
			if (isset($date_format)) {
				$df = $date_format;
			} else {
				$cfg =& JTheFactoryHelper::getConfig();
				$df = $cfg->date_format;
			}

			if (!$date)
				return $date; //empty date
			if ($df == 'Y-m-d') {
				return $date;
			}

			if ($df == 'm/d/Y') {
				if (preg_match("/([0-9]+)\/([0-9]+)\/([0-9]+)/", $date, $matches))
					return $matches[3] . "-" . $matches[1] . "-" . $matches[2];
			}
			if ($df == 'd/m/Y') {
				if (preg_match("/([0-9]+)\/([0-9]+)\/([0-9]+)/", $date, $matches))
					return $matches[3] . "-" . $matches[2] . "-" . $matches[1];
			}
			if ($df == 'd.m.Y') {
				if (preg_match("/([0-9]+)\.([0-9]+)\.([0-9]+)/", $date, $matches))
					return $matches[3] . "-" . $matches[2] . "-" . $matches[1];
			}
			if ($df == 'D, F d Y') {
				$d = strtotime($date);
				return date("Y-m-d", $d);
			}
			return $date;
		}

	} // End Class

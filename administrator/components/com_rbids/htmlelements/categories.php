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

	abstract class JHTMLCategories
	{
		/**
		 * Generate Categories drop-down list
		 *
		 * @param string $name
		 * @param string $attributes
		 * @param null   $defaultvalue
		 *
		 * @return mixed
		 */
		public static function selectlist($name = 'categories', $attributes = '', $defaultvalue = null)
		{

			$db =& JFactory::getDbo();
			$db->setQuery("SELECT `catname` AS `text`,
							 `id` AS `value`
						  FROM `#__" . APP_PREFIX . "_categories`
						  WHERE `status`= 1
						  AND `parent` = 0
						  ORDER BY `catname` ASC
					");
			$country = $db->loadObjectList();

			return JHtml::_('select.genericlist', $country, $name, $attributes, 'value', 'text', $defaultvalue);
		}

	} //End Class

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

	class JHTMLListAuctions
	{

		function selectOrderDir($selected)
		{

			$opts = array();
			$opts[] = JHTML::_('select.option', 'DESC', JText::_('COM_RBIDS_BID_ORDER_DESC'));
			$opts[] = JHTML::_('select.option', 'ASC', JText::_('COM_RBIDS_BID_ORDER_ASC'));

			return JHTML::_('select.genericlist', $opts, 'filter_order_Dir', 'class="inputbox" onchange="document.auctionForm.submit();"', 'value', 'text', $selected);
		}

		function inputKeyword($value)
		{

			return '<input type="text" id="search_box" name="keyword" size="30" value="' . $value . '" />';
		}

		function inputsHiddenFilters($filters)
		{

			$html = '';

			foreach ($filters as $name => $filter) {
				if (empty($filter)) {
					continue;
				}

				if (is_array($filter)) {
					foreach ($filter as $opt) {
						$html .= '<input type="hidden" name="' . $name . '[]" value="' . $opt . '" />' . PHP_EOL;
					}
				} else {
					$html .= '<input type="hidden" name="' . $name . '" value="' . $filter . '" />' . PHP_EOL;
				}
			}

			return $html;
		}

		function htmlLabelFilters($filters, $asArray = true)
		{

			$database = & JFactory::getDBO();
			$cfg =& JTheFactoryHelper::getConfig();

			$searchstrings = array();
			if ($filters->get('keyword')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_KEYWORD')] = $filters->get('keyword');
			}

			if ($filters->get('userid')) {
				$u = &JFactory::getUser($filters->get('userid'));
				if ($u && !$u->block) {
					$searchstrings[JText::_('COM_RBIDS_FILTER_USERS')] = $u->username;
				}
			}

			if ($filters->get('cat')) {
				$database->setQuery("select catname from #__rbid_categories where id='" . $database->getEscaped($filters->get('cat')) . "'");
				$catname = $database->loadResult();
				$searchstrings[JText::_('COM_RBIDS_FILTER_CATEGORY')] = $catname;
			}
			if ($filters->get('afterd')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_START_DATE')] = $filters->get('afterd');
			}

			if ($filters->get('befored')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_END_DATE')] = $filters->get('befored');
			}

			if ($filters->get('tag')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_TAGS')] = $filters->get('tag');
			}

			if ($filters->get('auction_nr')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_AUCTION_NUMBER')] = $filters->get('auction_nr');
			}

			if ($filters->get('inarch')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_ARCHIVE')] = ($filters->get('inarch') == 1) ? JText::_('COM_RBIDS_YES') : JText::_('COM_RBIDS_NO');
			}

			if ($filters->get('filter_rated')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_RATED')] = JText::_('COM_RBIDS_UNRATED');
			}

			if ($filters->get('country')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_COUNTRY')] = $filters->get('country');
			}

			if ($filters->get('city')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_CITY')] = $filters->get('city');
			}

			if ($filters->get('area')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_AREA')] = $filters->get('area');
			}

			if ($filters->get('startprice')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_START_PRICE')] = $filters->get('startprice') . ' ' . $filters->get('currency');
			}

			if ($filters->get('endprice')) {
				$searchstrings[JText::_('COM_RBIDS_FILTER_END_PRICE')] = $filters->get('endprice') . ' ' . $filters->get('currency');
			}

			//integration filter labels
			$profile = rBidsHelperTools::getUserProfileObject();
			$integrationArray = $profile->getIntegrationArray();
			foreach ($integrationArray as $alias => $fieldName) {
				if ($fieldName) {
					if ($fValue = $filters->get('user_profile%' . $fieldName)) {
						$searchstrings[JText::_(strtoupper('RBID_' . $alias))] = $fValue;
					}
				}
			}

			//custom fields filter labels
			$searchableFields = &CustomFieldsFactory::getSearchableFieldsList();
			foreach ($searchableFields as $field) {
				$requestKey = $field->page . '%' . $field->db_name;
				if ($filters->get($requestKey)) {
					$ftype = &CustomFieldsFactory::getFieldType($field->ftype);
					$searchstrings[JText::_($field->name)] = $ftype->htmlSearchLabel($field, $filters->get($requestKey));
					;
				}
			}

			return $searchstrings;
		}
	}

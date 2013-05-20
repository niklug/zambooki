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

	jimport('joomla.application.component.view');

	class rbidsViewrbids extends RBidsSmartyView
	{

		function display($template)
		{
			$cfg =& JTheFactoryHelper::getConfig();
			$model =& JModel::getInstance('RBids', 'rbidsModel');

			JHTML::script("auctions.js", 'components/com_rbids/js/');
			JHTML::_("behavior.modal");
			JTheFactoryHelper::tableIncludePath('category');

			$items = $model->loadItems();

			$gallery = RBidsHelperGallery::getGalleryPlugin();
			$gallery->writeJS();

			$auction_list = array();

			for ($key = 0; $key < count($items); $key++) {
				$auction = JTable::getInstance('Auctions', 'Table');
				$auction->bind($items[$key]);
				$auction->setCacheObject($items[$key]);
				$auction_list[] = $auction;
			}


			$filter_order_Dir = $model->getState('filters.filter_order_Dir');
			$filter_order = $model->getState('filters.filter_order');
			$filter_archive = $model->getState('filters.filter_archive');
			$filter_myauctions = $model->getState('filters.filter_myauctions');
			$pagination =& $model->get('pagination');
			$filters = $model->getFilters();

			$this->assign("auction_rows", $auction_list);
			$this->assign("cfg", $cfg);

			$this->assign("filter_order_Dir", $filter_order_Dir);
			$this->assign("filter_order", $filter_order);
			$this->assign("filter_archive", $filter_archive);
			$this->assign("filter_myauctions", $filter_myauctions);
			$this->assign("pagination", $pagination);

			$this->assign('inputsHiddenFilters', JHTML::_('listAuctions.inputsHiddenFilters', $filters));
			$this->assign('htmlLabelFilters', JHTML::_('listAuctions.htmlLabelFilters', $filters, false));

			parent::display($template);
		}
	}

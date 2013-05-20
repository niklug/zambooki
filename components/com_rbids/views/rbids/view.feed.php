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

	jimport('joomla.application.component.view');

	class rbidsViewrbids extends JView
	{

		function display($tpl)
		{

			$app = JFactory::getApplication();
			// Get the page/component configuration

			$doc =& JFactory::getDocument();
			$siteEmail = $app->getCfg('mailfrom');

			$doc->link = JRoute::_("index.php?option=com_rbids&view=rbids&format=feed");
			$model = & JModel::getInstance('RBids', 'rbidsModel');

			$items = $model->loadItems();

			$catObj = & RBidsHelperTools::getCategoryTable();

			if ($items)

				for ($key = 0; $key < count($items); $key++) {
					$auction = $items[$key];

					$title = $this->escape($auction->title);
					$title = html_entity_decode($title);

					$item = new JFeedItem();

					$item->title = $title;
					$item->link = JRoute::_(JURI::root() . "index.php?option=com_rbids&amp;&amp;task=viewbids&amp;id=$auction->id");
					$item->description = $auction->description;
					$item->date = $auction->start_date;
					$catObj->load($auction->category);
					$item->category = $catObj->catname;
					$item->author = $auction->user_id;
					$item->authorEmail = $siteEmail;

					$doc->addItem($item);
				}

		}

	} // End Class

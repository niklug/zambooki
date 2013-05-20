<?php
	/**------------------------------------------------------------------------
	thefactory - The Factory Class Library - v 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : thefactory
	 * @subpackage: category
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	jimport('joomla.application.component.model');

	class JTheFactoryCategoryController extends JTheFactoryController
	{
		var $_name = 'Category';
		var $name = 'Category';

		/**
		 *
		 */
		function Categories()
		{

			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$cats = $catModel->getCategoryTree(0, true);

			$view = $this->getView();
			$view->assignRef('categories', $cats);
			$view->display();
		}

		/**
		 *
		 */
		function CategoryJSON()
		{
			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$startnode = JRequest::getInt('node', 0);
			if ($startnode <= 0) $startnode = 0;

			$cats = $catModel->getCategoriesNested($startnode, false);

			foreach ($cats as $cat) {
				$cats_arr[] = array(
					'id' => $cat->id,
					'name' => $cat->catname,
					'lft' => $cat->left + 1,
					'rht' => $cat->right + 1,
					'description' => $cat->description
				);
			}
			$obj = new stdClass();
			$obj->success = true;
			$obj->data = $cats_arr;
			header('Content-type:application/json');
			echo json_encode($obj);
			exit;
		}

		/**
		 *
		 */
		function NewCatJSON()
		{
			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$parent = JRequest::getInt('parent', 0);
			if ($parent <= 0) $parent = 0;

			$data = JRequest::getInt('data');

			$newcat = json_decode($data);


		}

		/**
		 *
		 */
		function CategoryWidget()
		{

			$view = $this->getView();
			$view->display('widget');
		}

		/**
		 *
		 */
		function DelCategories()
		{
			$cids = JRequest::getVar("cid", array());

			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$nr = $catModel->delCategory($cids);
			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=category.categories', $nr . " " . JText::_('FACTORY_CATEGORIES_DELETED'));

		}

		/**
		 *
		 */
		function EditCat()
		{
			$cid = JRequest::getVar("cid", array());
			if (is_array($cid)) $cid = $cid[0];

			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');

			$cattable = &JTable::getInstance('Category', 'JTheFactoryTable');
			$cattable->load($cid);

			$cats = $catModel->getCategoryTree(0, true);

			$view = $this->getView();
			$view->assignRef('row', $cattable);
			$view->assignRef('cats', $cats);
			$view->display('form');
		}

		/**
		 *
		 */
		function SaveCat()
		{
			$id = JRequest::getInt('cid', null);
			$catobj =& JTable::getInstance('Category', 'JTheFactoryTable');
			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$lang = JFactory::getLanguage();

			$catobj->bind(JRequest::get('post'), array('parent'));
			$catobj->id = $id;
			$catobj->hash = $lang->transliterate($catobj->catname);
			$catobj->hash = md5(strtolower(JFilterOutput::stringURLSafe($catobj->hash)));
			$catobj->ordering = $catobj->ordering ? $catobj->ordering : $catModel->getMaxOrdering() + 1;

			if ($catobj->store()) {
				JRequest::setVar('cid', $catobj->id, 'post');
				$this->doMoveCategories();
			}
			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=category.categories', JText::_('FACTORY_CATEGORY_SAVED'));
		}

		/**
		 *
		 */
		function NewCat()
		{
			$cattable = &JTable::getInstance('Category', 'JTheFactoryTable');

			$view = $this->getView();
			$view->assignRef('row', $cattable);
			$view->display('form');


		}

		/**
		 *
		 */
		function saveCatOrder()
		{

			$cat =& JTable::getInstance('Category', 'JTheFactoryTable');

			foreach ($_REQUEST as $k => $v) {
				if (substr($k, 0, 6) == 'order_') {
					$id = substr($k, 6);
					$cat->load($id);
					$cat->ordering = $v;
					$cat->store();
				}
			}
			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=category.categories', JText::_('FACTORY_ORDERING_SAVED'));
		}

		/**
		 * Quick endless level categories to ROOT
		 *
		 * @modified: 23/09/09
		 **/
		function QuickAddCat()
		{

			$textcats = JRequest::getVar('quickadd', '');
			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$catModel->quickAddFromText($textcats);
			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=category.categories', JText::_('FACTORY_CATEGORY_ADDED'));
		}


		/**
		 * showMoveCategories - Class Method
		 *
		 * Move categories in other parent category
		 */
		function showMoveCategories()
		{

			$database = & JFactory::getDBO();
			$cid_arr = JRequest::getVar('cid', array(0), '', 'array');
			$cid_list = implode(",", $cid_arr);

			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');

			$database->setQuery("SELECT * FROM `" . $catModel->category_table . "` WHERE id IN ($cid_list);");
			$changed_cats = $database->loadObjectList();

			$html_tree = JHtml::_('factorycategory.select', 'parent', '', 0, true, false, true, JText::_('FACTORY_ROOT_CATEGORY'));

			$view = $this->getView();
			$view->assignRef('cid_list', $cid_list);
			$view->assignRef('cats', $changed_cats);
			$view->assignRef('parent', $html_tree);
			$view->display('movecat');
		}


		/**
		 * doMoveCategories - Class Method
		 *
		 * Move categories in tree
		 *
		 *  // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
		 *  // Old Model                                                                         CAT_ROOT  --->      3.cat3 -> 2                                         //
		 *  // 3.cat3 -> 2    =>   1.cat1 -> 3          parent 3 switch with children 1                             |___> 1.cat 1 -> 3                        //
		 *  // 4.cat4 -> 1                                  category 4 is children for parent 1                                           |___> 4.cat4 -> 1        //
		 *  // 5.cat5 -> 1                                                                                                                            |___> 5.cat5 -> 1        //
		 *  //                                                                                                                                                                          //
		 *  // New Model                                                                        CAT_ROOT  --->      1.cat1 -> 2                                        //
		 *  // 1.cat1 -> 2          3.cat3 - > 1         now category 1 is parent for category 3                    |___> 3.cat3 -> 1                        //
		 *  // 4.cat4 -> 3                                  category 4 is children for category 3                                        |___> 4.cat4 -> 3        //
		 *  // 5.cat5 -> 3                                  category 5 is children for category 3                                        |___> 5.cat5 -> 3        //
		 *  // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
		 *
		 */
		function doMoveCategories()
		{

			defined('CAT_ROOT') || define('CAT_ROOT', 0);

			$database = & JFactory::getDBO();
			// Cid format 3,5,6,8,12
			$cid = JRequest::getString('cid', "0");
			// Category to switch on
			$categoryMoveTo = JRequest::getString('parent', "0");
			// Parent of parent of categoryMoveTo;
			$parentOfCategoryMoveFrom = CAT_ROOT;
			// Old Model 3 became 1 and 1 became 3
			$isParentSwitchWithChild = false;

			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');

			// Categories Root cannot be moved
			if (CAT_ROOT == $cid) {
				return null;
			}
			// Get parent of categoryMoveTo
			$database->setQuery("SELECT `parent`
							   FROM `{$catModel->category_table}`
							   WHERE `id` = '{$categoryMoveTo}'
					");
			// Old Model -> 3
			$parentOfCategoryMoveTo = $database->loadResult();

			// Check if parent of newParent exist in cid list. If true get parent of cid item (get parent of old parent that became new child)
			if (in_array($parentOfCategoryMoveTo, explode(',', $cid))) {
				// Get parent of parent of newParent (parent of oldParent)
				$database->setQuery("SELECT `parent`
								   FROM `{$catModel->category_table}`
								   WHERE `id` = '{$parentOfCategoryMoveTo}'
						");
				// Old Model -> 2
				$parentOfCategoryMoveFrom = $database->loadResult();
				$isParentSwitchWithChild = true;
			}

			// Move cid to new parent
			// New Model set parent 1 for category 3
			$database->setQuery("UPDATE `{$catModel->category_table}`
							   SET `parent` = '{$categoryMoveTo}'
							   WHERE `id` IN ('{$cid}')
				");
			$database->query();
			$nr = $database->getAffectedRows();

			if ($isParentSwitchWithChild) {
				// Set parent of categoryMoveTo with parent of old parent
				// New Model set parent 2 for category 1
				$database->setQuery("UPDATE `{$catModel->category_table}`
				                                   SET `parent` = '{$parentOfCategoryMoveFrom}'
				                                   WHERE `id` = '{$categoryMoveTo}'
				                        ");
				$database->query();

				// Change parent for all categories that was children for categoryMoveTo
				// New Model set parent 3 for categories 4,5
				$database->setQuery("UPDATE `{$catModel->category_table}`
								   SET `parent` = '{$parentOfCategoryMoveTo}'
								   WHERE `parent` = '{$categoryMoveTo}'
								   AND `id` NOT IN ('{$cid}')
					");
				$database->query();
			}


			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=category.categories', $nr . " " . JText::_('FACTORY_CATEGORIES_MOVED'));
		}


		/**
		 *
		 */
		function Publish_Cat()
		{

			$cids = JRequest::getVar("cid", array());
			$nr = count($cids);
			$cids_string = implode(",", $cids);

			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$db =& JFactory::getDbo();
			$db->setQuery("UPDATE `" . $catModel->category_table . "` SET status = 1 WHERE id in ($cids_string)");
			$db->query();

			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=category.categories', $nr . " " . JText::_('FACTORY_CATEGORIES_PUBLISHED'));
		}

		/**
		 *
		 */
		function Unpublish_Cat()
		{

			$cids = JRequest::getVar("cid", array());
			$nr = count($cids);
			$cids_string = implode(",", $cids);

			$catModel =& JModel::getInstance('Category', 'JTheFactoryModel');
			$db =& JFactory::getDbo();
			$db->setQuery("UPDATE `" . $catModel->category_table . "` SET status = 0 WHERE id in ($cids_string)");
			$db->query();

			$this->setRedirect('index.php?option=' . APP_EXTENSION . '&task=category.categories', $nr . " " . JText::_('FACTORY_CATEGORIES_UNPUBLISHED'));
		}

	}

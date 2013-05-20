<?php
/**------------------------------------------------------------------------
com_rbids - Reverse Auction Factory 3.0.0
------------------------------------------------------------------------
 * @author TheFactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thefactory.ro
 * Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
 * @build: 01/04/2012
 * @package: RBids
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class JRbidsAdminControllerCountries extends JController
{

    function execute($task)
    {
        if (file_exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'toolbar.countries.php'))
            require JPATH_COMPONENT_ADMINISTRATOR.DS.'toolbar.countries.php';

        parent::execute($task);
    }
    function Listing()
    {

		$db		=& JFactory::getDBO();
		$app	= &JFactory::getApplication();

		$context			= 'com_rbids.countrylist';
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		$activefilter	= $app->getUserStateFromRequest($context.'activefilter', 'activefilter', 2, 'int');
		$searchfilter	= $app->getUserStateFromRequest($context.'searchfilter', 'searchfilter', '', 'string');


		$options = array();
		$options[] = JHTML::_('select.option',"2",JText::_("COM_RBIDS_ALL"));
		$options[] = JHTML::_('select.option',"1",JText::_("COM_RBIDS_PUBLISHED"));
		$options[] = JHTML::_('select.option',"0",JText::_("COM_RBIDS_UNPUBLISHED"));
		$active_html = JHTML::_('select.genericlist', $options, "activefilter",'class="inputbox" onchange="javascript:document.adminForm.submit();" ','value', 'text', $activefilter);

		$where = array();
		if($activefilter!="2"){
			$where[]= " active ='{$activefilter}' ";
		}
		if($searchfilter!=""){
			$where[]= " name LIKE '%{$searchfilter}%' ";
		}

		$whereSQL = "";
		if(count($where)>0){
			$whereSQL = "WHERE ".implode("AND",$where);
		}

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );

		$db->setQuery("SELECT COUNT(*) FROM #__rbid_country $whereSQL ");
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);

		$db->setQuery("SELECT * FROM #__rbid_country $whereSQL ", $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();

        $view=$this->getView('country','html');
        $view->assignRef('countries',$rows);
        $view->assignRef('pagination',$pagination);

        $view->assignRef('active_filter',$active_html);
        $view->assign('search',$searchfilter);
        $view->display();

    }
    function Toggle()
    {
		$cid	= JRequest::getVar( 'cid', array(), '', 'array' );
		if(count($cid>0)){
			$cids = implode(",",$cid);
    		$db		=& JFactory::getDBO();
			$db->setQuery("UPDATE #__rbid_country SET active = 1- active WHERE id IN ($cids)");
			$db->query();
		}
		$this->setRedirect("index.php?option=com_rbids&task=countries.listing",JText::_("COM_RBIDS_COUNTRIES_PUBLISHING_TOGGLED"));

    }
    
}

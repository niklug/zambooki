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

abstract class JHTMLRBidUser
{
    static function selectlist($name='userid',$attributes='',$defaultvalue=null)
    {
        $db = &JFactory::getDbo();
        $query="select distinct u.id as `value`, u.username as `text` from #__users u where u.block!=1 order by username";
        $db->setQuery($query);
        $users = $db->loadObjectList();
        $users= array_merge(array(JHTML::_("select.option","",JText::_("COM_RBIDS__ANY_USER"))),$users);
        return JHTML::_("select.genericlist",$users,$name,$attributes,'value', 'text', $defaultvalue);
    }

}

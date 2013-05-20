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

abstract class JHTMLAuctionpublished
{
    static function selectlist($name='published',$attributes='',$defaultvalue=null)
    {
        $opts = array();
        $opts[] = JHTML::_('select.option', 1,JText::_("COM_RBIDS_PUBLISHED"));
        $opts[] = JHTML::_('select.option', 0,JText::_("COM_RBIDS_UNPUBLISHED"));
        return JHTML::_('select.radiolist',  $opts, $name, $attributes ,  'value', 'text', $defaultvalue);
    }

}

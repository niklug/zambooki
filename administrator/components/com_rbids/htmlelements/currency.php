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

abstract class JHTMLCurrency
{
    static function selectlist($name='currency',$attributes='',$defaultvalue=null)
    {
        $db=&JFactory::getDbo();
        $db->setQuery("select `name` as text,`name` as value from `#__".APP_PREFIX."_currency` order by `name`");
        $currencies=$db->loadObjectList();
        if(!$defaultvalue){
            $db->setQuery("select `name` as value from `#__".APP_PREFIX."_currency` where `default`=1");
            $defaultvalue=$db->loadResult();
        }

        return JHtml::_('select.genericlist',$currencies,$name,$attributes,'value','text',$defaultvalue);
    }

}

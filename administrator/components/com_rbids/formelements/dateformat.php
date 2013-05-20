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

jimport('joomla.form.formfield');

class JFormFieldDateFormat extends JFormField
{
	protected $type = 'DateFormat';
	protected function getInput()
	{
        $date_format[] = JHTML::_("select.option", 'Y-m-d', 'Y-m-d');
        $date_format[] = JHTML::_("select.option", 'm/d/Y', 'm/d/Y');
        $date_format[] = JHTML::_("select.option", 'd/m/Y', 'd/m/Y');
        $date_format[] = JHTML::_("select.option", 'd.m.Y', 'd.m.Y');
        $date_format[] = JHTML::_("select.option", 'D, F d Y', 'D, F d Y');
        $html= JHTML::_("select.genericlist",$date_format,$this->name,"" ,'value', 'text',$this->value);
        $html.="&nbsp;<span id='{$this->id}_span'>&nbsp;</span>";
		return $html;
	}
}

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

class JFormFieldGalleryList extends JFormField
{
	protected $type = 'GalleryList';

	protected function getInput()
	{
        $galleries_plugins[] = JHTML::_('select.option', 'scrollgallery', 'Scroll Gallery');
        $galleries_plugins[] = JHTML::_('select.option', 'lytebox', 'Lytebox');
        $galleries_plugins[] = JHTML::_('select.option', 'slider', 'Picture Slider');
        return JHTML::_("select.genericlist", $galleries_plugins,$this->name,"" ,'value', 'text',$this->value);
	}
}

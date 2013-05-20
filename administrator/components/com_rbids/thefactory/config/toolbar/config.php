<?php
/**------------------------------------------------------------------------
thefactory - The Factory Class Library - v 2.0.0
------------------------------------------------------------------------
 * @author TheFactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thefactory.ro
 * Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
 * @build: 01/04/2012
 * @package: thefactory
 * @subpackage: config
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class JTheFactoryConfigToolbar
{
    function display($task=null)
    {
        JToolBarHelper::title( JText::_( 'FACTORY_GLOBAL_CONFIGURATION' ));
        switch($task)
        {
            default:
        		JToolBarHelper::save( 'config.savesettings',JText::_("FACTORY_SAVE"));
		        JToolBarHelper::custom( 'settingsmanager', 'back', 'back', JText::_('COM_RBIDS_BACK'), false );
            break;
         }

    }
}

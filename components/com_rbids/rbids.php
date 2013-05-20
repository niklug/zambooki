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

jimport('joomla.application.component.controller');
//Load Framework
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'thefactory'.DS.'application'.DS.'application.class.php');
$MyApp = &JTheFactoryApplication::getInstance(null,true);

require_once(JPATH_COMPONENT_SITE.DS.'defines.php');
require_once(JPATH_COMPONENT_SITE.DS.'options.php');

if(!JFolder::exists(AUCTION_PICTURES_PATH)) JFolder::create(AUCTION_PICTURES_PATH);
if(!JFolder::exists(AUCTION_UPLOAD_FOLDER)) JFolder::create(AUCTION_UPLOAD_FOLDER);
if(!JFolder::exists(AUCTION_TEMPLATE_CACHE)) JFolder::create(AUCTION_TEMPLATE_CACHE);

//Load Classes
require_once JPATH_COMPONENT_SITE.DS.'classes'.DS.'rbids_smarty.php';
require_once JPATH_COMPONENT_SITE.DS.'classes'.DS.'rbids_smartyview.php';
require_once JPATH_COMPONENT_SITE.DS.'classes'.DS.'rbids_model.php';

//Load Helper Classes
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'rbids.php';
RBidsHelper::LoadHelperClasses();
//Set HTML includes
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'htmlelements');
// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rbids'.DS.'tables');

$stopexecution=false;
JTheFactoryEventsHelper::triggerEvent('onBeforeExecuteTask',array(&$stopexecution));
if ($stopexecution) return;

$task = JRequest::getCmd('task','listauctions');

$controller=JTheFactoryHelper::loadController($task ); //Try to load Framework controllers
if (!$controller)
{
    $controllerClass = JRequest::getWord('controller');
    if (!$controllerClass && strpos($task,'.')!==FALSE) //task=controller.task?
    {
        $task=explode('.',$task);
        $controllerClass=$task[0];
    }
    if ($controllerClass)
    {
      $path = JPATH_COMPONENT.DS.'controllers'.DS.basename($controllerClass).'.php';
      file_exists($path) ? require_once($path) : JError::raiseError(500, JText::_('COM_RBIDS_ERROR_CONTROLLER_NOT_FOUND'));
      $controllerClass=$controllerClass.'Controller';
      $controller=new $controllerClass;
      
    }else{
        require_once (JPATH_COMPONENT.DS.'controller.php');
        $controller = new RbidsController();
    }
}
if (strpos($task,'.')!==FALSE) //task=controller.task?
{
    $task=explode('.',$task);
    $task=$task[1];
}

$controller->execute($task);

JTheFactoryEventsHelper::triggerEvent('onAfterExecuteTask',array($controller));

$controller->redirect();

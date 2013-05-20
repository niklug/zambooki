<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

// Add admin stylesheet and toolbar icons
	$doc = JFactory::getDocument();
	$doc->addStyleSheet(JURI::base(true) . '/components/com_rbids/assets/css/rbids.css');
	$doc->addScript(JURI::base(true) . '/components/com_rbids/js/rbids_joomla_override.js');
	$doc->addStyleDeclaration("
			.icon-48-generic {
					background-image: url('" . JURI::base() . "components/com_rbids/assets/images/toolbar/rbids_logo_icon_toolbar.png');
			}"
	);

//Load Framework
jimport('joomla.form.helper');

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'thefactory' . DS . 'application' . DS . 'application.class.php');
$MyApp = JTheFactoryApplication::getInstance();
// Include dependencies
$MyApp->Initialize();

if (!JFolder::exists(AUCTION_PICTURES_PATH)) JFolder::create(AUCTION_PICTURES_PATH);
if (!JFolder::exists(AUCTION_UPLOAD_FOLDER)) JFolder::create(AUCTION_UPLOAD_FOLDER);
if (!JFolder::exists(AUCTION_TEMPLATE_CACHE)) JFolder::create(AUCTION_TEMPLATE_CACHE);

$MyApp->dispatch();
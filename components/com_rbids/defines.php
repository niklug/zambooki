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

	define('AUCTION_PICTURES', JURI::root() . '/media/com_rbids/images');
	define('AUCTION_PICTURES_PATH', JPATH_ROOT . DS . 'media' . DS . 'com_rbids' . DS . 'images' . DS);
	define('AUCTION_UPLOAD_FOLDER', JPATH_ROOT . DS . 'media' . DS . 'com_rbids' . DS . 'files' . DS);
	define('AUCTION_TEMPLATE_CACHE', JPATH_ROOT . DS . 'cache' . DS . 'com_rbids' . DS . 'templates');

	define('AUCTION_TYPE_PUBLIC', 1);
	define('AUCTION_TYPE_PRIVATE', 2);
	define('AUCTION_TYPE_ONE_ON_ONE', 3);
	define('AUCTION_TYPE_LIMITED', 4);
	define('AUCTION_TYPE_INVITE', 5);

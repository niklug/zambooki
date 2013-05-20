<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class RBidsHelperGallery
	{

		static function &getGalleryPlugin()
		{
			static $gallery;
			if (isset($gallery) && is_object($gallery))
				return $gallery;

			$cfg =& JTheFactoryHelper::getConfig();
			$gallery_name = "gl_" . $cfg->gallery;
			require_once(JPATH_COMPONENT_SITE . DS . "gallery" . DS . $gallery_name . ".php");

			$gallery = new $gallery_name(AUCTION_PICTURES,
				$cfg->medium_width,
				$cfg->medium_height,
				$cfg->thumb_width,
				$cfg->thumb_height
			);
			return $gallery;
		}

	} // End Class

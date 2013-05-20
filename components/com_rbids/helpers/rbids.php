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

	class RBidsHelper
	{
		function FileName2ClassName($prefix, $filename, $suffix)
		{
			jimport('joomla.filesystem.file');
			$class_name = $prefix . ucfirst(strtolower(preg_replace('/\s/', '_', JFile::stripExt($filename)))) . $suffix;
			return $class_name;
		}

		static function LoadHelperClasses()
		{
			$helperfolder = JPATH_COMPONENT_SITE . DS . 'helpers';

			$files = JFolder::files($helperfolder, '\.php$');
			if (count($files))
				foreach ($files as $helperfile) {
					if ($helperfile == basename(__FILE__))
						continue;
					$class_name = self::FileName2ClassName('RBidsHelper', $helperfile, '');
					JLoader::register($class_name, $helperfolder . DS . $helperfile);
				}
		}

	} // End Class

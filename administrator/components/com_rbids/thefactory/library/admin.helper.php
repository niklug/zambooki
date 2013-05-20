<?php
	/**------------------------------------------------------------------------
	thefactory - The Factory Class Library - v 2.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : thefactory
	 * @subpackage: library
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class JTheFactoryAdminHelper extends JObject
	{

		public static function quickiconButton($link, $image, $text)
		{
			?>
		<div style = "float:left;">
			<div class = "icon" style = "text-align: center;">
				<a href = "<?php echo $link; ?>">
					<?php
					echo JHTML::_('image.administrator', $image, '../components/' . APP_EXTENSION . '/images/', NULL, NULL, $text, "class ='quick_icon_image_button' style='margin-left:25%;' ");
					?>
					<span style = "clear: both;"><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
		}

		public static function getConfigFile()
		{
			$MyApp =& JTheFactoryApplication::getInstance();
			$configfile = $MyApp->getIniValue('option_file');
			return JPATH_COMPONENT_SITE . DS . $configfile;
		}
	} // End Class

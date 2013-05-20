<?php

	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');

	class JHTMLRBidSettings
	{

		public static function quickIconButton($link, $image, $text)
		{
			$html = "<div style=\"float:left;\">
    		<div class=\"icon\">
    			<a href=\"{$link}\">" .
				JHTML::_('image.administrator', $image, '../components/' . APP_EXTENSION . '/images/menu/', NULL, NULL, $text)
				. "<span>$text</span>
    			</a>
    		</div>
    	</div>";
			return $html;
		}
	} // End Class
